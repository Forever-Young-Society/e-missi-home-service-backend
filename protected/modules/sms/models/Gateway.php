<?php

/**
 * This is the model class for table "tbl_sms_gateway".
 *
 * @property integer $id
 * @property string $title
 * @property string $value
 * @property integer $mode
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id

 * === Related data ===
 * @property User $createdBy
 */
namespace app\modules\sms\models;

use app\models\User;
use app\modules\sms\components\Sms;
use Yii;
use yii\helpers\Inflector;
use yii\helpers\Json;

class Gateway extends \app\components\TActiveRecord
{

    // Input types
    const KEY_TYPE_STRING = 0;

    const KEY_TYPE_BOOL = 1;

    const KEY_TYPE_INT = 2;

    const KEY_TYPE_EMAIL = 3;

    // Gateway types
    const GATEWAY_TYPE_TWILIO = 0;

    const GATEWAY_TYPE_BHASH = 1;

    const GATEWAY_TYPE_MVAAYOO = 2;

    const GATEWAY_TYPE_AQUA = 3;

    const GATEWAY_TYPE_INDIA_SMS = 4;

    // TODO
    // const GATEWAY_TYPE_HORIZON = 1;
    // const GATEWAY_TYPE_INDIA_HUB = 2;
    // const GATEWAY_TYPE_SMSZONE = 3;
    // const GATEWAY_TYPE_WAY2MINT = 4;
    public function __toString()
    {
        return (string) $this->title;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

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
            'class' => 'label label-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getTypeOptions()
    {
        return [
            self::GATEWAY_TYPE_TWILIO => "Twilio",
            self::GATEWAY_TYPE_BHASH => "Bhash sms",
            self::GATEWAY_TYPE_MVAAYOO => "Mvaayoo",
            self::GATEWAY_TYPE_AQUA => "Aqua Sms",
            self::GATEWAY_TYPE_INDIA_SMS => "India Sms"
            // self::GATEWAY_TYPE_INDIA_HUB => "Sms India Hub",
            // self::GATEWAY_TYPE_SMSZONE => "Sms Zone",
            // self::GATEWAY_TYPE_WAY2MINT => "Way2mint"
        ];
    }

    public function getType($type = null)
    {
        if ($type !== null) {
            $this->type_id = $type;
        }
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Invalid';
    }

    public static function gatewayFields()
    {
        return [
            self::GATEWAY_TYPE_TWILIO => [
                'twilio_account_sid' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'twilio_account_token' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'phone_number' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ]
            ],
            self::GATEWAY_TYPE_BHASH => [
                'username' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'password' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'sender_id' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ]
            ],
            self::GATEWAY_TYPE_MVAAYOO => [
                'username' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'sender_id' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ]
            ],
            self::GATEWAY_TYPE_AQUA => [
                'username' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'apikey' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'smstype' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'sendername' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ]
            ],
            self::GATEWAY_TYPE_INDIA_SMS => [
                'username' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'password' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'type' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ],
                'sender' => [
                    'type' => self::KEY_TYPE_STRING,
                    'value' => '',
                    'required' => true
                ]
            ]
        ];
    }

    public static function gatewayFormFields()
    {
        return self::gatewayFields();
    }

    public static function gatewayForm($type)
    {
        $list = self::gatewayFormFields();
        return isset($list[$type]) ? $list[$type] : [];
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
            if (! isset($this->updated_on))
                $this->updated_on = date('Y-m-d H:i:s');
            if (! isset($this->created_by_id))
                $this->created_by_id = Yii::$app->user->id;
        } else {
            $this->updated_on = date('Y-m-d H:i:s');
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms_gateway}}';
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
                    'created_on',
                    'created_by_id',
                    'state_id'
                ],
                'required'
            ],
            [
                [
                    'value'
                ],
                'string'
            ],
            [
                [
                    'mode',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'updated_on'
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
                    'title'
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
            'title' => Yii::t('app', 'Title'),
            'value' => Yii::t('app', 'Value'),
            'mode' => Yii::t('app', 'Mode'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Gateway Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
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
        ]);
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
        $json['value'] = $this->value;
        $json['mode'] = $this->mode;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // createdBy
            $list = $this->createdBy;

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['createdBy'] = $relationData;
            } else {
                $json['CreatedBy'] = $list;
            }
        }
        return $json;
    }

    public function getControllerID()
    {
        return '/sms/' . parent::getControllerID();
    }

    public static function generateField($key, $field)
    {
        $html = "";
        if (is_array($field)) {
            $required = (isset($field['required']) && ($field['required'] != false)) ? " required" : '';
            $value = isset($field['value']) ? $field['value'] : '';
            if (isset($field['type'])) {
                $html .= '<div class="form-group field-gateway-' . "{$key}{$required}" . '">';
                $html .= '<label class="control-label col-sm-3" for="gateway-' . $key . '">' . Inflector::titleize($key) . '</label><div class="col-sm-6">';

                switch ($field['type']) {
                    case self::KEY_TYPE_BOOL:
                        $html .= "<input type='checkbox' " . $required . " value='" . $value . "' class='form-control' name='Value[" . $key . "]' placeholder='" . Inflector::titleize($key) . "'>";
                        break;
                    case self::KEY_TYPE_STRING:
                        $html .= "<input type='text' " . $required . " value='" . $value . "' class='form-control' name='Value[" . $key . "]' placeholder='" . Inflector::titleize($key) . "'>";
                        break;
                    case self::KEY_TYPE_INT:
                        $html .= "<input type='number' " . $required . " value='" . $value . "' class='form-control' name='Value[" . $key . "]' placeholder='" . Inflector::titleize($key) . "'>";
                        break;
                    case self::KEY_TYPE_EMAIL:
                        $html .= "<input type='email' " . $required . " value='" . $value . "' name='Value[" . $key . "]' class='form-control' placeholder='" . Inflector::titleize($key) . "'>";
                        break;
                    default:
                        $html .= "<input type='text' " . $required . " value='" . $value . "' name='Value[" . $key . "]' class='form-control' placeholder='" . Inflector::titleize($key) . "'>";
                        break;
                }
            } else {
                $html .= '<div class="form-group field-gateway-' . $key . '" ' . $required . '>';
                $html .= '<label class="control-label col-sm-3 col-sm-3" for="gateway-' . $field . '">' . Inflector::titleize($key) . '</label><div class="col-sm-6">';
                $html .= "<input type='text' " . $required . " value='" . $value . "' name='Value[" . $key . "]' class='form-control' placeholder='" . Inflector::titleize($key) . "'>";
            }
            if ($required)
                $html .= '<div class="help-block help-block-error "></div>';
        } else {
            $html .= '<div class="form-group field-gateway-' . $field . '" required">';
            $html .= '<label class="control-label col-sm-3 col-sm-3" for="gateway-' . $field . '">' . Inflector::titleize($field) . '</label><div class="col-sm-6">';
            $html .= "<input type='text' class='form-control' name='Value[" . $field . "]' placeholder='" . Inflector::titleize($field) . "'>";
        }
        $html .= '</div></div>';
        return $html;
    }

    public function getGatewaySettings()
    {
        return Json::decode($this->value);
    }

    public function sendSms($model)
    {
        switch ($this->type_id) {
            case Gateway::GATEWAY_TYPE_TWILIO:
                $this->sendSmsViaTwilio($model);
                break;
            case Gateway::GATEWAY_TYPE_BHASH:
                $this->sendSmsViaBhash($model);
                break;
            case Gateway::GATEWAY_TYPE_MVAAYOO:
                $this->sendSmsViaMvaayoo($model);
                break;
            case Gateway::GATEWAY_TYPE_AQUA:
                $this->sendSmsViaAqua($model);
                break;
            case Gateway::GATEWAY_TYPE_INDIA_SMS:
                $this->sendSmsViaIndiaSms($model);
                break;
        }
    }

    function sendSmsViaTwilio($model)
    {
        $setting = $this->getGatewaySettings();
        $twilio = \Yii::createObject([
            'class' => 'dosamigos\twilio\TwilioComponent',
            'sid' => $setting['twilio_account_sid'],
            'token' => $setting['twilio_account_token'],
            'phoneNumber' => $setting['phone_number']
        ]);
        $result = $twilio->sms($model->to, $model->text, [
            'from' => $setting['phone_number']
        ]);
        if ($model->sms_detail == "SUBMIT_SUCCESS") {
            $model->sms_detail = $result;
            $model->save();
            \Yii::$app->session->setFlash('success', 'Messgae Sent');
        } else {
            \Yii::$app->session->setFlash('success', 'Messgae Not Sent');
        }
    }

    function sendSmsViaMvaayoo($model)
    {
        $setting = $this->getGatewaySettings();
        $result = Sms::getPostCurlResponse('http://api.mVaayoo.com/mvaayooapi/MessageCompose', array(
            'user' => $setting['username'],
            'senderID' => $setting['sender_id'],
            'receipientno' => $model->to,
            'msgtxt' => $model->text
        ));
        $model->sms_detail = $result;
        $model->save();
    }

    function sendSmsViaBhash($model)
    {
        $setting = $this->getGatewaySettings();
        $result = Sms::getGetCurlResponse('http://bhashsms.com/api/sendmsg.php', array(
            'user' => $setting['username'],
            'pass' => $setting['password'],
            'sender' => $setting['sender_id'],
            'phone' => $model->to,
            'text' => $model->text,
            'priority' => 'ndnd',
            'stype' => 'normal'
        ));
        $model->sms_detail = $result;
        $model->save();
    }

    function sendSmsViaAqua($model)
    {
        $setting = $this->getGatewaySettings();
        $result = Sms::getGetCurlResponse('http://login.aquasms.com/sendSMS', array(
            'username' => $setting['username'],
            'smstype' => $setting['smstype'],
            'apikey' => $setting['apikey'],
            'sendername' => $setting['sendername'],
            'message' => $model->text,
            'numbers' => $model->to
        ));
        $model->sms_detail = $result;
        $model->save();
    }

    function sendSmsViaIndiaSms($model)
    {
        $setting = $this->getGatewaySettings();

        $result = Sms::getGetCurlResponse('https://app.indiasms.com/sendsms/sendsms.php', array(
            'username' => $setting['username'],
            'password' => $setting['password'],
            'type' => $setting['type'],
            'sender' => $setting['sender'],
            'mobile' => $model->to,
            'message' => $model->text
        ));

        $model->sms_detail = $result;
        $model->save();
    }

    public function afterSave($insert = true, $changedAttributes = NULL)
    {
        if ($this->state_id == self::STATE_ACTIVE) {
            $findOldSettings = self::find()->andWhere([
                'not in',
                'id',
                $this->id
            ]);
            foreach ($findOldSettings->each() as $oldSetting) {
                $oldSetting->state_id = self::STATE_INACTIVE;
                $oldSetting->save(false, [
                    'state_id'
                ]);
            }
            \Yii::$app->session->setFlash('success', 'Only one gateway allowed at a time');
        }
        parent::afterSave($insert = true, $changedAttributes = NULL);
    }
}
