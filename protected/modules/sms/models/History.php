<?php

/**
 * This is the model class for table "tbl_sms_history".
 *
 * @property integer $id
 * @property string $from
 * @property string $to
 * @property integer $model_id
 * @property string $model_type
 * @property string $text
 * @property integer $gateway_id
 * @property string $sms_detail
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
use Yii;

class History extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->to;
    }

    public static function getModelOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return ArrayHelper::Map ( Model::findActive ()->all (), 'id', 'title' );
    }

    public function getModel()
    {
        $list = self::getModelOptions();
        return isset($list[$this->model_id]) ? $list[$this->model_id] : 'Not Defined';
    }

    const STATE_PENDING = 0;

    const STATE_SENT = 1;

    const STATE_NOT_SENT = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_PENDING => "Pending",
            self::STATE_SENT => "Sent",
            self::STATE_NOT_SENT => "Not Sent"
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
            self::STATE_PENDING => "primary",
            self::STATE_SENT => "success",
            self::STATE_NOT_SENT => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'label label-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getTypeOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return ArrayHelper::Map ( Type::findActive ()->all (), 'id', 'title' );
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'from',
                    'to',
                    'gateway_id',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'model_id',
                    'gateway_id',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'updated_on',
                    'sms_detail'
                ],
                'safe'
            ],
            [
                [
                    'from',
                    'to',
                    'model_type',
                    'text'
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
                    'from',
                    'to',
                    'model_type',
                    'text',
                    'sms_detail'
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'model_id' => Yii::t('app', 'Model'),
            'model_type' => Yii::t('app', 'Model Type'),
            'text' => Yii::t('app', 'Text'),
            'gateway_id' => Yii::t('app', 'Gateway'),
            'sms_detail' => Yii::t('app', 'Sms Detail'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
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

    public function getGateway()
    {
        return $this->hasOne(Gateway::className(), [
            'type_id' => 'gateway_id'
        ])->one();
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
        $json['from'] = $this->from;
        $json['to'] = $this->to;
        $json['model_id'] = $this->model_id;
        $json['model_type'] = $this->model_type;
        $json['text'] = $this->text;
        $json['gateway_id'] = $this->gateway_id;
        $json['sms_detail'] = $this->sms_detail;
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

    public function getCreatedById()
    {
        if (isset(\Yii::$app->user->id))
            return \Yii::$app->user->id;
        $model = User::find()->select([
            'id'
        ])
            ->where([
            'role_id' => User::ROLE_ADMIN
        ])
            ->one();
        if (! empty($model))
            return $model->id;
        return null;
    }
}
