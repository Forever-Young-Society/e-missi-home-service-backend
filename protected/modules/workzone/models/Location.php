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
namespace app\modules\workzone\models;

use app\models\User;
use Yii;
use app\models\UserWorkzone;

/**
 * This is the model class for table "tbl_workzone_location".
 *
 * @property integer $id
 * @property string $title
 * @property integer $primary_location
 * @property integer $secondary_location
 * @property integer $second_secondary_location
 * @property integer $type_id
 * @property integer $state_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class Location extends \app\components\TActiveRecord
{

    public $primary_zipcode;

    public $secondary_zipcode;

    public $file;

    const PRIMARY_ZIPCODE = 1;

    const SECONDARY_ZIPCODE = 2;

    const SECOND_SECONDARY_ZIPCODE = 3;

    public function __toString()
    {
        return (string) $this->title;
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

    public function getLocationOptions($id = null)
    {
        $query = Zone::findActive();
        if (! empty($id)) {
            $query->andWhere([
                '!=',
                'id',
                $id
            ]);
        }
        $query = $query->all();
        return self::listData($query);
    }

    public function checkIsWorkzoneSelectedByProvider()
    {
        return UserWorkzone::find()->where([
            'workzone_id' => $this->id
        ])->exists();
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

    public function getLocationZipcodes($type)
    {
        switch ($type) {
            case self::SECONDARY_ZIPCODE:
                $zipcode = $this->getSecondaryZipcodes();
                break;
            case self::SECOND_SECONDARY_ZIPCODE:
                $zipcode = $this->getSecondSecondaryZipcodes();
                break;
            default:
                $zipcode = $this->getPrimaryZipcodes();
                break;
        }

        $zipcode = $zipcode->active()
            ->select('post_code')
            ->column();

        return ! empty($zipcode) ? implode(',', $zipcode) : '';
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%workzone_location}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['add-location'] = [
            'title',
            'secondary_location',
            'primary_zipcode'
        ];

        return $scenarios;
    }

    public function isProviderExists($provider_ids)
    {
        return UserWorkzone::findActive()->andWhere([
            'workzone_id' => $this->id
        ])
            ->andWhere([
            'in',
            'created_by_id',
            $provider_ids
        ])
            ->exists();
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
                    'primary_location',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'type_id',
                    'state_id',
                    'created_by_id',
                    'primary_location',
                    'secondary_location',
                    'second_secondary_location'
                ],
                'integer'
            ],
            [
                [
                    'primary_location'
                ],
                'unique',
                'message' => \Yii::t('app', "Selected primary location already added")
            ],
            [
                [
                    'created_on',
                    'secondary_zipcode',
                    'primary_zipcode',
                    'file'
                ],
                'safe'
            ],
            [
                [
                    'title'
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
                    'title',
                    // 'secondary_location',
                    'primary_zipcode'
                ],
                'required',
                'on' => 'add-location'
            ],

            [
                [
                    'title'
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
            'title' => Yii::t('app', 'Primary Location'),
            'secondary_location' => Yii::t('app', 'Secondary Location'),
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
        return $this->hasOne(User::class, [
            'id' => 'created_by_id'
        ])->cache();
    }

    public function getPrimaryLocation()
    {
        return $this->hasOne(Zone::class, [
            'id' => 'primary_location'
        ])->cache();
    }

    public function getSecondaryLocation()
    {
        return $this->hasOne(Zone::class, [
            'id' => 'secondary_location'
        ])->cache();
    }

    public function getSecondSecondaryLocation()
    {
        return $this->hasOne(Zone::class, [
            'id' => 'second_secondary_location'
        ])->cache();
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrimaryZipcodes()
    {
        return $this->hasMany(Postcode::class, [
            'location_id' => 'primary_location'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSecondaryZipcodes()
    {
        return $this->hasMany(Postcode::class, [
            'location_id' => 'secondary_location'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSecondSecondaryZipcodes()
    {
        return $this->hasMany(Postcode::class, [
            'location_id' => 'second_secondary_location'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserWorkzones()
    {
        return $this->hasMany(UserWorkzone::class, [
            'workzone_id' => 'id'
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
        $relations['primary_location'] = [
            'primaryLocation',
            'Zone',
            'id'
        ];
        $relations['secondary_location'] = [
            'secondaryLocation',
            'Zone',
            'id'
        ];
        $relations['second_secondary_location'] = [
            'secondSecondaryLocation',
            'Zone',
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

    public function asJson()
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = ! empty($this->primaryLocation) ? $this->primaryLocation->title : '';
        $json['secondary_location'] = $this->secondary_location;
        $json['type_id'] = $this->type_id;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = $this->created_on;
        return $json;
    }

    public function getControllerID()
    {
        return '/workzone/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->title = $faker->text(10);
            $model->secondary_location = $faker->text(10);
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

            $model->secondary_location = isset($item['secondary_location']) ? $item['secondary_location'] : $faker->text(10);

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
