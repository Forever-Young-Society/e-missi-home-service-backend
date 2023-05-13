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
namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_import_file".
 *
 * @property integer $id
 * @property string $name
 * @property integer $size
 * @property string $key
 * @property string $model_type
 * @property integer $model_id
 * @property integer $percentage
 * @property string $failure_reason
 * @property integer $total_count
 * @property integer $download_count
 * @property integer $type_id
 * @property integer $state_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class ImportFile extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->name;
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

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

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

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
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
        return '{{%import_file}}';
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
                    // 'name',
                    // 'model_type',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'size',
                    'model_id',
                    'percentage',
                    'total_count',
                    'download_count',
                    'type_id',
                    'state_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'failure_reason'
                ],
                'string'
            ],
            [
                [
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'name'
                ],
                'string',
                'max' => 1024
            ],
            [
                [
                    'key'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'model_type'
                ],
                'string',
                'max' => 128
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
                    'name',
                    'key',
                    'model_type'
                ],
                'trim'
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

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'size' => Yii::t('app', 'Size'),
            'key' => Yii::t('app', 'Key'),
            'model_type' => Yii::t('app', 'Model Type'),
            'model_id' => Yii::t('app', 'Model'),
            'percentage' => Yii::t('app', 'Percentage'),
            'failure_reason' => Yii::t('app', 'Failure Reason'),
            'total_count' => Yii::t('app', 'Total Count'),
            'download_count' => Yii::t('app', 'Download Count'),
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
        $json['name'] = $this->name;
        $json['size'] = $this->size;
        $json['key'] = $this->key;
        $json['model_type'] = $this->model_type;
        $json['model_id'] = $this->model_id;
        $json['percentage'] = $this->percentage;
        $json['failure_reason'] = $this->failure_reason;
        $json['total_count'] = $this->total_count;
        $json['download_count'] = $this->download_count;
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
        }
        return $json;
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->name = $faker->text(10);
            $model->size = $faker->text(10);
            $model->key = $faker->text(10);
            $model->model_type = $faker->text(10);
            $model->model_id = 1;
            $model->percentage = $faker->text(10);
            $model->failure_reason = $faker->text;
            $model->total_count = $faker->text(10);
            $model->download_count = $faker->text(10);
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

            $model->name = isset($item['name']) ? $item['name'] : $faker->text(10);

            $model->size = isset($item['size']) ? $item['size'] : $faker->text(10);

            $model->key = isset($item['key']) ? $item['key'] : $faker->text(10);

            $model->model_type = isset($item['model_type']) ? $item['model_type'] : $faker->text(10);

            $model->model_id = isset($item['model_id']) ? $item['model_id'] : 1;

            $model->percentage = isset($item['percentage']) ? $item['percentage'] : $faker->text(10);

            $model->failure_reason = isset($item['failure_reason']) ? $item['failure_reason'] : $faker->text;

            $model->total_count = isset($item['total_count']) ? $item['total_count'] : $faker->text(10);

            $model->download_count = isset($item['download_count']) ? $item['download_count'] : $faker->text(10);

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
        return parent::afterSave($insert, $changedAttributes);
    }
}
