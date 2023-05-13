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
namespace app\modules\seo\models;

use app\components\helpers\TDeviceHelper;
use app\models\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_seo_log".
 *
 * @property integer $id
 * @property string $referer_link
 * @property string $message
 * @property string $current_url
 * @property integer $state_id
 * @property integer $type_id
 * @property integer $user_id
 * @property string $user_ip
 * @property string $user_agent
 * @property string $created_on
 * @property integer $created_by_id
 */
class Log extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->referer_link;
    }

    const STATE_ALLOWED = 0;

    const STATE_BANNED = 1;

    const TYPE_IS_MOBILE = 1;

    const TYPE_IS_DESKTOP = 0;

    public static function getStateOptions()
    {
        return [
            self::STATE_BANNED => "Banned",
            self::STATE_ALLOWED => "Allowed"
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
            self::STATE_ALLOWED => "success",
            self::STATE_BANNED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_BANNED => "Banned",
            self::STATE_ALLOWED => "Allowed"
        ];
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_IS_MOBILE => "Mobile",
            self::TYPE_IS_DESKTOP => "Desktop"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->user_id)) {
                $this->user_id = self::getCurrentUser();
            }
            if (empty($this->created_on)) {
                $this->created_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
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
        return '{{%seo_log}}';
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
                    'user_id',
                    'created_by_id',
                    'view_count'
                ],
                'integer'
            ],
            [
                [
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'referer_link',
                    'created_on',
                    'current_url'
                ],
                'safe'
            ],
            [
                [
                    'user_ip',
                    'user_agent'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'message'
                ],
                'string',
                'max' => 1000
            ],
            [
                [

                    'referer_link',
                    'current_url'
                ],
                'string',
                'max' => 512
            ],
            [
                [
                    'referer_link',
                    'user_ip',
                    'user_agent',
                    'message',
                    'current_url'
                ],
                'trim'
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

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'referer_link' => Yii::t('app', 'Referer Link'),
            'message' => Yii::t('app', 'Message'),
            'current_url' => Yii::t('app', 'Current Url'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'user_id' => Yii::t('app', 'User'),
            'user_ip' => Yii::t('app', 'User Ip'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
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
        $relations['user_id'] = [
            'user',
            'User',
            'id'
        ];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
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
        $json['referer_link'] = $this->referer_link;
        $json['message'] = $this->message;
        $json['current_url'] = $this->current_url;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['user_id'] = $this->user_id;
        $json['user_ip'] = $this->user_ip;
        $json['user_agent'] = $this->user_agent;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {}
        return $json;
    }

    public function getControllerID()
    {
        return '/seo/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->referer_link = $faker->text(10);
            $model->message = $faker->text(10);
            $model->current_url = $faker->text(10);
            $model->state_id = $states[rand(0, count($states))];
            $model->type_id = 0;
            $model->user_id = 1;
            $model->user_ip = $faker->text(10);
            $model->user_agent = $faker->text(10);
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

            $model->referer_link = isset($item['referer_link']) ? $item['referer_link'] : $faker->text(10);

            $model->message = isset($item['message']) ? $item['message'] : $faker->text(10);

            $model->current_url = isset($item['current_url']) ? $item['current_url'] : $faker->text(10);
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;

            $model->user_id = isset($item['user_id']) ? $item['user_id'] : 1;

            $model->user_ip = isset($item['user_ip']) ? $item['user_ip'] : $faker->text(10);

            $model->user_agent = isset($item['user_agent']) ? $item['user_agent'] : $faker->text(10);
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

    public function getcreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ])->cache();
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), [
            'id' => 'user_id'
        ])->cache();
    }

    public function getUserOptions()
    {
        return self::listData(User::findActive()->all());
        
    }

    public static function add()
    {
        $log = Log::find()->where([
            'current_url' => \yii::$app->request->absoluteUrl,
            'user_ip' => \yii::$app->request->userIP
        ])->one();
        if ($log) {
            // $log->updateHistory('Same user ip access again');
            $log->updateCounters([
                'view_count' => 1
            ]);
            return true;
        }

        $log = new Log();
        $log->loadDefaultValues();
        $log->referer_link = yii::$app->request->referrer;
        if (! \Yii::$app->user->isGuest) {
            $log->user_id = \yii::$app->user->id;
        }
        $log->current_url = \yii::$app->request->absoluteUrl;

        if (strpos($log->current_url, '.') || strstr($log->current_url, 'file')) {
            // skip files
            return false;
        }
        $log->user_ip = \yii::$app->request->userIP;
        $log->user_agent = \yii::$app->request->userAgent;
        $log->type_id = TDeviceHelper::isMobile();

        if (strstr($log->user_agent, 'bot')) {
            // skip bot
            return false;
        }
        if ($log->save()) {
            return true;
        }
        return false;
    }

    protected function processFeed($insert, $changedAttributes)
    {
        // skip feeds for logs
    }

    /**
     * Get number of records created in each month
     *
     * @param integer $state
     * @param integer $created_by_id
     * @param string $dateAttribute
     * @return number[]
     */
    public static function monthly($state = null, $created_by_id = null, $dateAttribute = 'created_on')
    {
        $date = new \DateTime(date('Y-m'));

        $date->modify('-1 year');

        $count = [];
        $query = self::find();
        for ($i = 1; $i <= 12; $i ++) {
            $date->modify('+1 months');
            $year = $date->format('Y');
            $month = $date->format('m');

            $query->where([
                "MONTH($dateAttribute)" => $month,
                "YEAR($dateAttribute)" => $year
            ]);

            if ($created_by_id !== null) {
                $query->andWhere([
                    'created_by_id' => $created_by_id
                ]);
            }

            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }

            $count[$month] = (int) $query->count();
        }
        return $count;
    }
}
