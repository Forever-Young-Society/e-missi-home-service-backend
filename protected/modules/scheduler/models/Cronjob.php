<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\scheduler\models;

use Cron\CronExpression;
use app\models\User;
use app\modules\scheduler\Module;
use Yii;

/**
 * This is the model class for table "tbl_scheduler_cronjob".
 *
 * @property integer $id
 * @property string $title
 * @property string $when
 * @property string $command
 * @property integer $type_id
 * @property integer $state_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 * @property Log[] $logs
 * @property Type $type
 */
class Cronjob extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->id . ':' . $this->title . ':' . $this->when;
    }

    public static function getTypeOptions()
    {
        return self::listData(Type::findActive()->all());
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "Disable",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Deleted"
        ];
    }

    public function getState()
    {
        $list = self::getStateOptions();
        return isset($list[$this->state_id]) ? $list[$this->state_id] : 'Not Defined';
    }

    public function getStateBadge()
    {
        $list = [
            self::STATE_INACTIVE => "secondary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_INACTIVE => "Deactivate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_DELETED => "Delete"
        ];
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->created_on)) {
                $this->created_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
            }
        }

        if (empty($this->title)) {
            $this->title = $this->command;
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scheduler_cronjob}}';
    }

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'title',
                    'when',
                    'command',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'command'
                ],
                'string'
            ],
            [
                [
                    'type_id',
                    'state_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'title'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'when'
                ],
                'string',
                'max' => 32
            ],
            [
                [
                    'created_by_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
            [
                [
                    'type_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Type::className(),
                'targetAttribute' => [
                    'type_id' => 'id'
                ]
            ],
            [
                [
                    'title',
                    'when',
                    'command'
                ],
                'trim'
            ],

            [
                [
                    'when'
                ],
                'validateWhen'
            ],
            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ]
        ];
    }

    public function validateWhen($attribute, $params, $validator)
    {
        if (! preg_match("/(\*|[0-5]?[0-9]|\*\/[0-9]+)\s+" . "(\*|1?[0-9]|2[0-3]|\*\/[0-9]+)\s+" . "(\*|[1-2]?[0-9]|3[0-1]|\*\/[0-9]+)\s+" . "(\*|[0-9]|1[0-2]|\*\/[0-9]+|jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\s+" . "(\*\/[0-9]+|\*|[0-7]|sun|mon|tue|wed|thu|fri|sat)\s*" . "(\*\/[0-9]+|\*|[0-9]+)?/i", $this->$attribute, $matches)) {
            $this->addError($attribute, 'Bad cron time expression format');
        }
    }

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'when' => Yii::t('app', 'When'),
            'command' => Yii::t('app', 'Command'),
            'type_id' => Yii::t('app', 'Type'),
            'state_id' => Yii::t('app', 'State'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ])->cache();
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::className(), [
            'cronjob_id' => 'id'
        ]);
    }

    public function getLogsCompleted()
    {
        return $this->getLogs()
            ->andWhere([
            'state_id' => Log::STATE_COMPLETED
        ])
            ->orderBy('executed_on DESC');
    }

    public function getLogsPending()
    {
        return $this->getLogs()
            ->andWhere([
            'state_id' => Log::STATE_PENDING
        ])
            ->orderBy('scheduled_on DESC');
    }

    public function cancelPending()
    {
        foreach ($this->getLogsPending()->each() as $log) {
            $log->delete();
        }
    }

    /**
     *
     * @return Log
     */
    public function getLastRun()
    {
        return $this->getLogs()
            ->orderBy('executed_on DESC')
            ->one();
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), [
            'id' => 'type_id'
        ])->cache();
    }

    public static function getHasManyRelations()
    {
        $relations = [];

        $relations['Logs'] = [
            'logs',
            'Log',
            'id',
            'cronjob_id'
        ];
        $relations['feeds'] = [
            'feeds',
            'Feed',
            'model_id'
        ];
        return $relations;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        $relations['type_id'] = [
            'type',
            'Type',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        // TODO : start here
        Log::deleteRelatedAll([
            'cronjob_id' => $this->id
        ]);

        return true;
    }

    public function beforeSave($insert)
    {
        if (! parent::beforeSave($insert)) {
            return false;
        }
        // TODO : start here
        return true;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        $json['when'] = $this->when;
        $json['command'] = $this->command;
        $json['type_id'] = $this->type_id;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // createdBy
            $list = $this->createdBy;

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['createdBy'] = $relationData;
            } else {
                $json['createdBy'] = $list;
            }
            // logs
            $list = $this->logs;

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['logs'] = $relationData;
            } else {
                $json['logs'] = $list;
            }
            // type
            $list = $this->type;

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['type'] = $relationData;
            } else {
                $json['type'] = $list;
            }
        }
        return $json;
    }

    public function getControllerID()
    {
        return '/scheduler/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->title = $faker->text(10);
            $model->when = $faker->text(10);
            $model->command = $faker->text;
            $model->type_id = 0;
            $model->state_id = $states[rand(0, count($states))];
            $model->save();
        }
    }

    public static function addData($data)
    {
        if (self::find()->count() != 0) {
            return;
        }

        $faker = \Faker\Factory::create();
        foreach ($data as $item) {
            $model = new self();
            $model->loadDefaultValues();

            $model->title = isset($item['title']) ? $item['title'] : $faker->text(10);

            $model->when = isset($item['when']) ? $item['when'] : $faker->text(10);

            $model->command = isset($item['command']) ? $item['command'] : $faker->text;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->state_id = self::STATE_ACTIVE;
            $model->save();
        }
    }

    public function isAllowed()
    {
        if (User::isAdmin())
            return true;
        if ($this->hasAttribute('created_by_id') && $this->created_by_id == Yii::$app->user->id) {
            return true;
        }

        return User::isUser();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->cancelPending();

        return parent::afterSave($insert, $changedAttributes);
    }

    public function scheduleNext($now = false)
    {
        if (! $this->isActive() || $this->getLogsPending()->count() > 0) {
            return;
        }
        // Calculate a run date relative to a specific time
        $cron = new CronExpression($this->when);

        $executed_on = \date('Y-m-d H:i:00');
        $last = $this->getLastRun();
        if ($last != null && ! empty($last->executed_on)) {
            $executed_on = $last->executed_on;
        }
        $log = new Log();
        $log->loadDefaultValues();
        $log->cronjob_id = $this->id;
        $log->type_id = 1;
        $log->state_id = Log::STATE_PENDING;
        $log->scheduled_on = $cron->getNextRunDate($executed_on)->format('Y-m-d H:i:s');
        if ($now) {
            $log->scheduled_on = \date('Y-m-d H:i:s');
        }

        self::log('scheduling => ' . $this);
        self::log($executed_on . '  : scheduleNext  => ' . $log->scheduled_on);
        if (! $log->save()) {

            return null;
        }
        return $log;
    }

    public function exportText()
    {
        return $this->when . "\t" . $this->command . "\t\t\t\t\t #" . $this->title;
    }

    public static function importline($line)
    {
        if (empty($line)) {
            return;
        }
        $line = trim($line, PHP_EOL);

        if (empty($line)) {
            return;
        }
        self::log("importline  : " . $line);
        list ($when, $command) = explode("\t", $line);

        $model = new self();
        $model->loadDefaultValues();
        $model->state_id = self::STATE_ACTIVE;
        $model->when = $when;
        $model->command = $command;
        $model->type_id = 1;
        $model->save();
    }

    public static function importFile($file = null)
    {
        if ($file == null) {
            $file = Module::self()->defaultJobsFile;
        }

        if (is_file($file)) {
            self::log("importDefault  : " . $file);
            $lines = file($file);
            foreach ($lines as $line) {

                self::importline($line);
            }
        }
    }

    public static function disable($cmd)
    {
        $cron = self::findActive()->andWhere([
            'command' => $cmd,
            'state_id' => self::STATE_ACTIVE
        ])->one();
        if ($cron) {
            self::log("disable  : " . $cron);
            $cron->state_id == self::STATE_DELETED;
            $cron->save();
        }
    }
}
