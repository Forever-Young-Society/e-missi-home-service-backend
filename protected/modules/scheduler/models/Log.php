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

use app\models\User;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "tbl_scheduler_log".
 *
 * @property integer $id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $result
 * @property integer $cronjob_id
 * @property string $scheduled_on
 * @property string $executed_on
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 * @property Cronjob $cronjob
 */
class Log extends \app\components\TActiveRecord
{

    public function __toString()
    {
        $title = (string) $this->id . ' : ' . $this->scheduled_on . '==> ';
        if ($this->cronjob) {
            $title .= $this->cronjob->command;
        }
        return $title;
    }

    const STATE_PENDING = 0;

    const STATE_COMPLETED = 1;

    const STATE_INPROGRESS = 2;

    const STATE_DELETED = 3;

    const STATE_FAILED = 4;

    public static function getStateOptions()
    {
        return [
            self::STATE_PENDING => "Pending",
            self::STATE_COMPLETED => "Completed",
            self::STATE_INPROGRESS => "In-progress",
            self::STATE_DELETED => "Deleted",
            self::STATE_FAILED => "Failed"
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
            self::STATE_PENDING => "secondary",
            self::STATE_COMPLETED => "success",
            self::STATE_INPROGRESS => "warning",
            self::STATE_DELETED => "danger",
            self::STATE_FAILED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_PENDING => "Pending",
            self::STATE_COMPLETED => "Complete",
            self::STATE_INPROGRESS => "In-progress",
            self::STATE_DELETED => "Delete",
            self::STATE_FAILED => "Fail"
        ];
    }

    public static function getTypeOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public static function getCronjobOptions()
    {
        return self::listData(Cronjob::findActive()->all());
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
            if (empty($this->scheduled_on)) {
                $this->scheduled_on = \date('Y-m-d H:i:s');
            }
        } else {}

        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%scheduler_log}}';
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
                    'state_id',
                    'type_id',
                    'cronjob_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'result'
                ],
                'string'
            ],
            [
                [
                    'cronjob_id',
                    'scheduled_on',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'scheduled_on',
                    'executed_on',
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'scheduled_on'
                ],
                'uniqueJobs'
            ],
            [
                [
                    'created_by_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
            [
                [
                    'cronjob_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => Cronjob::class,
                'targetAttribute' => [
                    'cronjob_id' => 'id'
                ]
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ],
            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
            ]
        ];
    }

    public function uniqueJobs($attribute, $params, $validator)
    {
        $value = $this->$attribute;
        $query = Log::find()->andWhere([
            'cronjob_id' => $this->cronjob_id,
            'state_id' => Log::STATE_PENDING,
            $attribute => $value
        ]);
        $data = $query->one();

        if ($data && $data->id != $this->id) {
            $this->addError($attribute, 'Duplocate job with same time:' . $data->id);
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
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'result' => Yii::t('app', 'Result'),
            'cronjob_id' => Yii::t('app', 'Cronjob'),
            'scheduled_on' => Yii::t('app', 'Scheduled On'),
            'executed_on' => Yii::t('app', 'Executed On'),
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
    public function getCronjob()
    {
        return $this->hasOne(Cronjob::className(), [
            'id' => 'cronjob_id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];

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
        $relations['cronjob_id'] = [
            'cronjob',
            'Cronjob',
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
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['result'] = $this->result;
        $json['cronjob_id'] = $this->cronjob_id;
        $json['scheduled_on'] = $this->scheduled_on;
        $json['executed_on'] = $this->executed_on;
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
            // cronjob
            $list = $this->cronjob;

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['cronjob'] = $relationData;
            } else {
                $json['cronjob'] = $list;
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
            $model->state_id = $states[rand(0, count($states))];
            $model->type_id = 0;
            $model->result = $faker->text;
            $model->cronjob_id = 1;
            $model->scheduled_on = \date('Y-m-d H:i:s');
            $model->executed_on = \date('Y-m-d H:i:s');
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
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;

            $model->result = isset($item['result']) ? $item['result'] : $faker->text;

            $model->cronjob_id = isset($item['cronjob_id']) ? $item['cronjob_id'] : 1;

            $model->scheduled_on = isset($item['scheduled_on']) ? $item['scheduled_on'] : \date('Y-m-d H:i:s');

            $model->executed_on = isset($item['executed_on']) ? $item['executed_on'] : \date('Y-m-d H:i:s');
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
        return parent::afterSave($insert, $changedAttributes);
    }

    function runAction()
    {
        self::log('runAction started => ' . $this->cronjob->command);

        $application = Yii::$app;
        $params = explode(' ', trim($this->cronjob->command));
        $cmd = array_shift($params);
        $results = '';
        ob_start();

        try {
            $application->runAction($cmd, $params);
            $this->state_id = self::STATE_COMPLETED;
        } catch (\Exception $ex) {
            self::log('Command Failed:' . $cmd);
            self::log($ex->getTraceAsString());
            $results = $ex->getMessage();
            $this->state_id = self::STATE_FAILED;
        } catch (\ErrorException $ex) {
            self::log('Command Failed:' . $cmd);
            self::log($ex->getTraceAsString());
            $results = $ex->getMessage();
            $this->state_id = self::STATE_FAILED;
        }

        $obData = ob_get_clean();
        self::log($obData);
        $results .= self::getLastLines($obData);
        $this->result = $results;
        $this->executed_on = \date('Y-m-d H:i:s');
        if ($this->save()) {
            self::log('Command saved => ' . $this->getState());
        } else {
            self::log('Command save failed => ' . $this->getState());
        }
        return $this->state_id == self::STATE_COMPLETED;
    }

    public static function getLastLines($string, $n = 30)
    {
        $lines = explode("\n", $string);

        $lines = array_slice($lines, - $n);

        return implode("\n", $lines);
    }
    
    protected function processFeed($insert, $changedAttributes)
    {
        // feeds not required 
    }
    
}
