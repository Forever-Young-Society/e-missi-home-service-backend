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
namespace app\modules\api\models;

use app\models\User;
use Yii;
use yii\web\HttpException;

/**
 * This is the model class for table "tbl_api_access_token".
 *
 * @property integer $id
 * @property string $access_token
 * @property string $device_token
 * @property string $device_name
 * @property string $device_type
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
class AccessToken extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->access_token;
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
            if (empty($this->created_on)) {
                $this->created_on = date('Y-m-d H:i:s');
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
        return '{{%api_access_token}}';
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
                    'access_token',
                    'device_token',
                    'device_type',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'type_id',
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
                    'access_token',
                    'device_token',
                    'device_name',
                    'device_type'
                ],
                'string',
                'max' => 256
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
                    'access_token',
                    'device_token',
                    'device_name',
                    'device_type'
                ],
                'trim'
            ],
            [
                [
                    'device_name'
                ],
                'app\components\validators\TNameValidator'
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
            'access_token' => Yii::t('app', 'Access Token'),
            'device_token' => Yii::t('app', 'Device Token'),
            'device_name' => Yii::t('app', 'Device Name'),
            'device_type' => Yii::t('app', 'Device Type'),
            'type_id' => Yii::t('app', 'Type'),
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
        return $this->hasOne(User::class, [
            'id' => 'created_by_id'
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
        $json['access_token'] = $this->access_token;
        $json['device_token'] = $this->device_token;
        $json['device_name'] = $this->device_name;
        $json['device_type'] = $this->device_type;
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
                $json['createdBy'] = $list;
            }
        }
        return $json;
    }

    public function getControllerID()
    {
        return '/api/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->access_token = $faker->text(10);
            $model->device_token = $faker->text(10);
            $model->device_name = $faker->name;
            $model->device_type = $faker->text(10);
            $model->type_id = 0;
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

            $model->access_token = isset($item['access_token']) ? $item['access_token'] : $faker->text(10);

            $model->device_token = isset($item['device_token']) ? $item['device_token'] : $faker->text(10);

            $model->device_name = isset($item['device_name']) ? $item['device_name'] : $faker->name;

            $model->device_type = isset($item['device_type']) ? $item['device_type'] : $faker->text(10);

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->save();
        }
    }

    public static function add($model, $access_token)
    {
        self::deleteOldAppData(Yii::$app->user->identity->id);

        $deviceDetail = new self();
        $deviceDetail->access_token = $access_token;
        $deviceDetail->created_by_id = Yii::$app->user->identity->id;
        $deviceDetail->device_token = $model->device_token;
        $deviceDetail->device_type = $model->device_type;
        $deviceDetail->device_name = isset($model->device_name) ? $model->device_name : 'NONE';
        if (! $deviceDetail->save()) {
            throw new HttpException(500, Yii::t('app', 'token not save' . $deviceDetail->getErrorsString()));
        }
        return $deviceDetail;
    }

    public static function deleteOldAppData($id)
    {
        self::deleteRelatedAll([
            'created_by_id' => $id
        ]);
        return true;
    }

    public function isAllowed()
    {
        if (User::isAdmin())
            return true;

        if ($this->hasAttribute('created_by_id')) {
            return ($this->created_by_id == Yii::$app->user->id);
        }

        return false;
    }
}
