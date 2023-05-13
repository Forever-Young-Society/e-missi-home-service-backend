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
namespace app\modules\logger\models;

use app\models\EmailQueue;
use Yii;
use app\models\User;

/**
 * This is the model class for table "tbl_logger_log".
 *
 * @property integer $id
 * @property string $error
 * @property string $description
 * @property integer $state_id
 * @property string $link
 * @property integer $type_id
 * @property string $referer_link
 * @property string $user_ip
 * @property integer $user_id
 * @property string $created_on
 */
class Log extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->error;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    const TYPE_WEB = 0;

    const TYPE_API = 1;

    const TYPE_APP = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
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

    public static function getTypeOptions()
    {
        return [
            "Web",
            "API",
            "App",
            "Others"
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
                $this->created_on = date('Y-m-d H:i:s');
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
        return '{{%logger_log}}';
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
                    'error',
                    'link',
                    'type_id',
                    'user_ip',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'description'
                ],
                'string'
            ],
            [
                [
                    'state_id',
                    'type_id',
                    'user_id'
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
                    'error'
                ],
                'string',
                'max' => 256
            ],
            [
                [
                    'link',
                    'referer_link',
                    'user_ip'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'error',
                    'link',
                    'referer_link',
                    'user_ip'
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
            'error' => Yii::t('app', 'Error'),
            'description' => Yii::t('app', 'Description'),
            'state_id' => Yii::t('app', 'State'),
            'link' => Yii::t('app', 'Link'),
            'type_id' => Yii::t('app', 'Type'),
            'referer_link' => Yii::t('app', 'Referer Link'),
            'user_ip' => Yii::t('app', 'User Ip'),
            'user_id' => Yii::t('app', 'User'),
            'created_on' => Yii::t('app', 'Created On')
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), [
            'id' => 'user_id'
        ])->cache();
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
        $json['error'] = $this->error;
        $json['description'] = $this->description;
        $json['state_id'] = $this->state_id;
        $json['link'] = $this->link;
        $json['type_id'] = $this->type_id;
        $json['referer_link'] = $this->referer_link;
        $json['user_ip'] = $this->user_ip;
        $json['user_id'] = $this->user_id;
        $json['created_on'] = $this->created_on;
        if ($with_relations) {}
        return $json;
    }

    public function getControllerID()
    {
        return '/logger/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->error = $faker->text(10);
            $model->description = $faker->text;
            $model->state_id = $states[rand(0, count($states))];
            $model->link = $faker->text(10);
            $model->type_id = 0;
            $model->referer_link = $faker->text(10);
            $model->user_ip = $faker->text(10);
            $model->user_id = 1;
            $model->save();
        }
    }

    public static function addData($data)
    {
        $faker = \Faker\Factory::create();
        if (self::find()->count() != 0)
            return;
        foreach ($data as $item) {
            $model = new self();
            $model->loadDefaultValues();

            $model->error = isset($item['error']) ? $item['error'] : $faker->text(10);

            $model->description = isset($item['description']) ? $item['description'] : $faker->text;
            $model->state_id = self::STATE_ACTIVE;

            $model->link = isset($item['link']) ? $item['link'] : $faker->text(10);

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;

            $model->referer_link = isset($item['referer_link']) ? $item['referer_link'] : $faker->text(10);

            $model->user_ip = isset($item['user_ip']) ? $item['user_ip'] : $faker->text(10);

            $model->user_id = isset($item['user_id']) ? $item['user_id'] : 1;
            $model->save();
        }
    }

    public function sendMailToAdmin()
    {
        $module = Yii::$app->getModule('logger');

        $moduleSettings = new SettingsForm();

        if (! $moduleSettings->enableEmails) {
            self::log("Logger Emails not enabled");
            return false;
        }
        if (! empty($moduleSettings->sendLogEmailsTo)) {
            $emails = $moduleSettings->sendLogEmailsTo;
        } else {
            $emails = $module->sendLogEmailsTo;
        }

        $data = [
            'subject' => $this->error,
            'html' => nl2br($this->description)
        ];
        if ($emails != null) {
            $emails = is_array($emails) ? $emails : [
                $emails
            ];

            foreach ($emails as $admin) {
                $data['to'] = $admin;
                EmailQueue::add($data, (YII_ENV == 'prod'));
            }
        } else {
            EmailQueue::sendEmailToAdmins($data);
        }
    }

    protected function processFeed($insert, $changedAttributes)
    {}

    public static function addException($exception, $status)
    {
        $contactToSupportEmail = Yii::$app->getModule('logger')->contactToSupportEmail;

        if (empty($contactToSupportEmail)) {
            $contactToSupportEmail = empty(Yii::$app->params['adminEmail']) ? null : Yii::$app->params['adminEmail'];
        }
        if (! empty($contactToSupportEmail)) {
            $name .= " Please Contact us:" . $contactToSupportEmail;
        }
        $user = 0;
        if (! \yii::$app->user->isGuest) {

            $user = \yii::$app->user->id;
        }

        $log = new Log();
        $log->loadDefaultValues();
        $log->link = \yii::$app->request->absoluteUrl;
        $log->referer_link = yii::$app->request->referrer;
        $log->user_ip = \yii::$app->request->userIP;
        $log->user_id = $user;
        $log->error = $status . ":  " . $exception->getMessage();
        $log->description = 'Url : ' . $log->link . PHP_EOL;

        $log->description .= 'User : ' . Yii::$app->user->userName . PHP_EOL;
        $log->description .= 'Referer : ' . $log->referer_link . PHP_EOL;
        $log->description .= 'Client : ' . $log->user_ip . PHP_EOL;
        $log->description .= 'Error : ' . $log->error . PHP_EOL;
        $log->description .= 'User Agent : ' . \Yii::$app->request->getUserAgent() . PHP_EOL;
        $log->description .= "---------------------" . PHP_EOL;
        $log->description .= $exception->getTraceAsString();

        if (\Yii::$app->controller->module->id == 'api') {
            $log->type_id = Log::TYPE_API;
        } else {
            $log->type_id = Log::TYPE_WEB;
        }
        if ($log->save()) {

            $log->sendMailToAdmin();
        }
    }

    /**
     * Toggle env
     *
     * @return boolean|number
     */
    public static function toggleEnv()
    {
        $devFile = realpath(".") . "/.dev";
        if (is_file($devFile)) { // prod mode on
            @unlink($devFile);
            \Yii::debug('Production mode enabled');
        } else {
            if (is_file($devFile)) {
                return true;
            }
            \Yii::debug('Dev mode enabled');
            return file_put_contents($devFile, '');
        }
    }
}
