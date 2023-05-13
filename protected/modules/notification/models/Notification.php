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
namespace app\modules\notification\models;

use app\models\EmailQueue;
use app\models\User;
use app\modules\notification\Module;
use Yii;

/**
 * This is the model class for table "tbl_notification".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $model_id
 * @property string $model_type
 * @property integer $is_read
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $to_user_id
 * @property integer $created_by_id
 */
class Notification extends \app\components\TActiveRecord
{

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    const TYPE_USER_NOTIFICATION = 0;

    const TYPE_BOOKING_NOTIFICATION = 1;

    public function __toString()
    {
        return (string) $this->title . ':' . $this->toUser;
    }

    public function getModel()
    {
        $modelType = $this->model_type;
        if (class_exists($modelType)) {
            return $modelType::find()->cache()
                ->where([
                'id' => $this->model_id
            ])
                ->one();
        }
        return null;
    }

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Archived"
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
            self::STATE_INACTIVE => "primary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    const IS_NOT_READ = 0;

    const IS_READ = 1;

    public static function getIsReadOptions()
    {
        return [
            self::IS_NOT_READ => "Not Read",
            self::IS_READ => "Read"
        ];
    }

    public function getIsRead()
    {
        $list = self::getIsReadOptions();
        return isset($list[$this->is_read]) ? $list[$this->is_read] : 'Not Defined';
    }

    public function getIsReadBadge()
    {
        $list = [
            self::IS_READ => "success",
            self::IS_NOT_READ => "danger"
        ];
        return isset($list[$this->is_read]) ? \yii\helpers\Html::tag('span', $this->getIsRead(), [
            'class' => 'badge badge-' . $list[$this->is_read]
        ]) : 'Not Defined';
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

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
            if (! isset($this->to_user_id))
                $this->to_user_id = self::getCurrentUser();
            if (! isset($this->created_by_id))
                $this->created_by_id = self::getCurrentUser();
        } else {}
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification}}';
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
                    'model_id',
                    'model_type',
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
                    'model_id',
                    'is_read',
                    'state_id',
                    'type_id',
                    'to_user_id',
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
                'max' => 1024
            ],
            [
                [
                    'model_type'
                ],
                'string',
                'max' => 256
            ],
            [
                [
                    'title',
                    'model_type',
                    'is_read'
                ],
                'trim'
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

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'model_id' => Yii::t('app', 'Model'),
            'model_type' => Yii::t('app', 'Model Type'),
            'is_read' => Yii::t('app', 'Is Read'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'to_user_id' => Yii::t('app', 'To User'),
            'created_by_id' => Yii::t('app', 'From User')
        ];
    }

    public function getToUser()
    {
        return $this->hasOne(User::className(), [
            'id' => 'to_user_id'
        ])->cache(10);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ])->cache(10);
    }

    public static function getHasManyRelations()
    {
        $relations = [];
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
        $relations['to_user_id'] = [
            'toUser',
            'User',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        return parent::beforeDelete();
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        $json['description'] = $this->description;
        $json['model_id'] = $this->model_id;
        // $json['model_type'] = $this->model_type;
        $json['is_read'] = $this->is_read;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['to_user_id'] = $this->to_user_id;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {}
        return $json;
    }

    public function getControllerID()
    {
        return '/notification/' . parent::getControllerID();
    }

    public function isAllowed()
    {
        if ($this->to_user_id == \Yii::$app->user->id) {
            return true;
        }
        return parent::isAllowed();
    }

    /**
     * Create Notification
     *
     * Use :
     *
     * Notification::create([
     * 'model' => $model,
     * 'to_user_id' => 3,
     * 'title' => 'Lead Reminder',
     * 'created_by_id' => $model->id
     * ]);
     */
    public static function create($param = [])
    {
        $notification = new self();
        $notification->loadDefaultValues();
        $notification->to_user_id = $param['to_user_id'];
        $notification->created_by_id = $param['created_by_id'];
        $notification->title = $param['title'];
        $notification->model_id = $param['model']->id;
        $notification->model_type = get_class($param['model']);
        $notification->type_id = isset($param['type_id']) ? $param['type_id'] : self::TYPE_USER_NOTIFICATION;
        $notification->is_read = Notification::IS_NOT_READ;
        if ($notification->save()) {
            $notification->sendNotificationOnApp();
            Yii::debug('notification created :' . $notification);
            return true;
        }
        return false;
    }

    /**
     * Check if notification send or not
     *
     * Use :
     *
     * Notification::create([
     * 'model' => $model,
     * 'to_user_id' => 3,
     * 'title' => 'Lead Reminder',
     * 'created_by_id' => $model->id
     * ]);
     */
    public static function isNotify($param = [])
    {
        $notification = self::find()->select([
            'id'
        ])
            ->where([
            'title' => $param['title'],
            'model_id' => $param['model']->id,
            'model_type' => get_class($param['model']),
            'to_user_id' => $param['to_user_id'],
            'created_by_id' => $param['created_by_id']
        ])
            ->one();
        return $notification;
    }

    public static function clear($model)
    {
        $query = self::find();
        $query->where([
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'to_user_id' => self::getCurrentUser()
        ]);
        self::log("Cleaning up  Notifications: " . $query->count());
        foreach ($query->each() as $notification) {
            self::log("Deleting Notification :" . $notification->id . ' - ' . $notification);

            $notification->delete();
        }
        return false;
    }

    public function sendNotificationOnApp()
    {
        $androidtoken = [];
        $iostoken = [];
        $tokens = "";
        $data = [];
        $data['controller'] = \yii::$app->controller->id;
        $data['action'] = \yii::$app->controller->action->id;
        $data['message'] = $this->title;
        $data['user_id'] = $this->to_user_id;
        $data['detail'] = $this->asJson();
        $user = User::findOne($this->to_user_id);

        if (! empty($user)) {

            $tokens = $user->authSessions;
            if (count($tokens) > 0) {

                foreach ($tokens as $token) {
                    if ($token->device_type == 1) {
                        $androidtoken[] = $token->device_token;
                    }
                    if ($token->device_type == 2)
                        $iostoken[] = $token->device_token;
                }
                if (! empty($androidtoken)) {
                    try {
                        $datas = Yii::$app->firebase->sendDataMessage($androidtoken, $data);
                    } catch (\Exception $e) {
                        \Yii::error(\yii\helpers\VarDumper::dumpAsString('android NOTIFICATION SEND ERRROR'));
                        \Yii::error(\yii\helpers\VarDumper::dumpAsString($e->getMessage()));
                    }
                }

                if (! empty($iostoken)) {
                    $out = '';
                    foreach ($iostoken as $tokn) {
                        try {
                            $url = "https://fcm.googleapis.com/fcm/send";
                            $serverKey = User::getFirebaseKey();
                            // $title ="notification title";

                            $body = $data['message'];
                            $notifyData = $data;
                            // Creating the notification array.
                            $notification = array(
                                // 'title' => $title,
                                'body' => $body,
                                'sound' => 'default',
                                'badge' => '1'
                            );
                            $arrayToSend = array(
                                'to' => $tokn,
                                'data' => $notifyData,
                                'notification' => $notification,
                                'priority' => 'high',
                                'mutable_content' => true,
                                'category' => "ImagePush"
                            );

                            $json = json_encode($arrayToSend);

                            $headers = array();
                            $headers[] = 'Content-Type: application/json';
                            $headers[] = 'Authorization: key=' . $serverKey;
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            // Send the request
                            curl_exec($ch);
                            \Yii::error('Apple Push Done');
                            curl_close($ch);
                        } catch (\Exception $e) {
                            \Yii::error('Apple Push ERROR' . \yii\helpers\VarDumper::dumpAsString($e->getMessage()));
                        }

                        return $out ? true : false;
                    }
                }
            }
        }
    }
}