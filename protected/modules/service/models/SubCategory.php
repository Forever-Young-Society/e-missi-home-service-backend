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
namespace app\modules\service\models;

use app\models\User;
use Yii;
use yii\helpers\Url;
use app\models\UserSubcategory;
use app\modules\workzone\models\Postcode;
use app\models\UserWorkzone;
use app\modules\workzone\models\Location;
use app\models\UserCategory;

/**
 * This is the model class for table "tbl_service_sub_category".
 *
 * @property integer $id
 * @property string $title
 * @property string $image_file
 * @property integer $category_id
 * @property integer $type_id
 * @property integer $state_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class SubCategory extends \app\components\TActiveRecord
{

    public $json_data;

    public $sub_service;

    public $zipcode;

    public $service_ids;

    const TYPE_SUB_SERVICE_YES = 0;

    const TYPE_SUB_SERVICE_NO = 1;

    const SERVICE_TYPE_NORMAL = 0;

    const SERVICE_TYPE_DIRECT_BOOKING = 1;

    public function __toString()
    {
        return (string) $this->title;
    }

    public static function getCategoryOptions()
    {
        return self::listData(Category::findActive()->all());
    }

    public function getCategory()
    {
        $list = self::getCategoryOptions();
        return isset($list[$this->category_id]) ? $list[$this->category_id] : 'Not Defined';
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

    public function checkIsSkillSelectedByProvider()
    {
        return UserSubcategory::find()->where([
            'sub_category_id' => $this->id
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

    public function getSelectedSubServices()
    {
        $list = [];
        $subserviceModel = $this->getSubServices()->active();
        foreach ($subserviceModel->each() as $value) {
            $data = [];
            $data['sub_service'] = ! empty($value->title) ? $value->title : '';
            $list[] = $data;
        }
        return $list;
    }

    public function getServiceTypeProviderIds()
    {
        return UserCategory::findActive()->select('created_by_id')
            ->andWhere([
            'category_id' => $this->category_id
        ])
            ->distinct()
            ->column();
    }

    public function getLocationProviderIds($zipcode)
    {
        $arr = [];
        $provider_ids = $this->getServiceTypeProviderIds();
        $locationModel = Location::find()->alias('l')
            ->joinWith([
            'primaryZipcodes as z'
        ])
            ->active()
            ->andWhere([
            'z.post_code' => $zipcode
        ])
            ->one();

        if (! empty($locationModel)) {
            if ($locationModel->isProviderExists($provider_ids)) {
                return UserWorkzone::findActive()->select('created_by_id')
                    ->andWhere([
                    'workzone_id' => $locationModel->id
                ])
                    ->andWhere([
                    'in',
                    'created_by_id',
                    $provider_ids
                ])
                    ->distinct()
                    ->column();
            } else {
                $secondary_location = Location::findOne([
                    'primary_location' => $locationModel->secondary_location
                ]);
                if (! empty($secondary_location)) {
                    if ($secondary_location->isProviderExists($provider_ids)) {
                        return UserWorkzone::findActive()->select('created_by_id')
                            ->andWhere([
                            'workzone_id' => $secondary_location->id
                        ])
                            ->andWhere([
                            'in',
                            'created_by_id',
                            $provider_ids
                        ])
                            ->distinct()
                            ->column();
                    }
                }
                $second_secondary_location = Location::findOne([
                    'primary_location' => $locationModel->second_secondary_location
                ]);
                if (! empty($second_secondary_location)) {
                    return UserWorkzone::findActive()->select('created_by_id')
                        ->andWhere([
                        'workzone_id' => $second_secondary_location->id
                    ])
                        ->andWhere([
                        'in',
                        'created_by_id',
                        $provider_ids
                    ])
                        ->distinct()
                        ->column();
                }
            }
        }
        return $arr;
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%service_sub_category}}';
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
                    'category_id',
                    'created_on',
                    'created_by_id',
                    'price',
                    'provider_price'
                ],
                'required'
            ],
            // [
            // 'title',
            // 'unique'
            // ],
            [
                [
                    'category_id',
                    'type_id',
                    'state_id',
                    'created_by_id',
                    'id',
                    'parent_id',
                    'combination_count',
                    'service_type'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'zipcode',
                    'price',
                    'provider_price',
                    'json_data',
                    'sub_service',
                    'service_ids'
                ],
                'safe'
            ],

            [
                [
                    'combination_count'
                ],
                'integer',
                'min' => 1,
                'max' => 5
            ],

            [
                [
                    'price',
                    'provider_price'
                ],
                'number',
                'min' => 0,
                'max' => 100000
            ],

            [
                [
                    'title',
                    'image_file'
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
                'targetClass' => User::class,
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
            [
                [
                    'title',
                    'image_file'
                ],
                'trim'
            ],
            [
                [
                    'image_file'
                ],
                'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg,jpeg'
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
            'title' => Yii::t('app', 'Title'),
            'image_file' => Yii::t('app', 'Image File'),
            'category_id' => Yii::t('app', 'Qualification'),
            'type_id' => Yii::t('app', 'Type'),
            'state_id' => Yii::t('app', 'State'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By'),
            'price' => Yii::t('app', 'User Price'),
            'provider_price' => Yii::t('app', 'Provider Price')
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

    public function getSubServices()
    {
        return $this->hasMany(self::class, [
            'parent_id' => 'id'
        ])->andOnCondition([
            'state_id' => self::STATE_ACTIVE
        ]);
    }

    public function getParentService()
    {
        return $this->hasOne(self::class, [
            'id' => 'parent_id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryDetail()
    {
        return $this->hasOne(Category::class, [
            'id' => 'category_id'
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
        $relations['category_id'] = [
            'categoryDetail',
            'Category',
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

        // Delete actual file
        $filePath = UPLOAD_PATH . $this->image_file;

        if (is_file($filePath)) {
            unlink($filePath);
        }

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

    public function asBookingJson()
    {
        $json = [];
        $json['id'] = $this->id;
        $json['category_id'] = $this->category_id;
        $json['title'] = $this->title;
        $json['user_price'] = $this->price;
        $json['combinationCount'] = $this->combination_count;
        $json['provider_price'] = $this->provider_price;
        $json['service_type'] = $this->service_type;
        return $json;
    }

    public function asSubJson()
    {
        $json = [];
        $json['id'] = $this->id;
        $json['category_id'] = $this->category_id;
        $json['parent_id'] = $this->parent_id;
        $json['title'] = $this->title;
        $json['user_price'] = $this->price;
        $json['service_type'] = $this->service_type;
        $json['combinationCount'] = ! empty($this->parentService) ? $this->parentService->combination_count : $this->combination_count;
        $json['provider_price'] = $this->provider_price;
        return $json;
    }

    public function asJson($with_relations = true)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        $json['category_id'] = $this->category_id;
        $json['combinationCount'] = $this->combination_count;
        $json['type_id'] = $this->type_id;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['service_type'] = $this->service_type;
        if ($this->type_id == self::TYPE_SUB_SERVICE_YES) {
            if ($with_relations) {
                // sub skills
                $list = $this->subServices;
                if (is_array($list)) {
                    $relationData = array_map(function ($item) {
                        return $item->asSubJson();
                    }, $list);

                    $json['sub_services'] = $relationData;
                } else {
                    $json['sub_services'] = $list;
                }
            }
        }
        return $json;
    }

    public function getControllerID()
    {
        return '/service/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->title = $faker->text(10);
            $model->image_file = $faker->text(10);
            $model->category_id = 1;
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

            $model->image_file = isset($item['image_file']) ? $item['image_file'] : $faker->text(10);

            $model->category_id = isset($item['category_id']) ? $item['category_id'] : 1;

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
