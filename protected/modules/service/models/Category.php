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
use app\models\UserTerm;
use app\models\UserCategory;
use app\modules\availability\models\SlotBooking;

/**
 * This is the model class for table "tbl_service_category".
 *
 * @property integer $id
 * @property string $title
 * @property string $image_file
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class Category extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->title;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    const TYPE_NURSING_SERVICE = 0;

    const TYPE_PHYSIOTHERAPIST_SERVICE = 1;

    const TYPE_ACUPUNCTURE_SERVICE = 2;

    const TYPE_CAREGIVER_SERVICE = 3;

    public static function getTypeOptions()
    {
        return [
            self::TYPE_NURSING_SERVICE => "Nursing Services",
            self::TYPE_CAREGIVER_SERVICE => "Caregiver",
            self::TYPE_PHYSIOTHERAPIST_SERVICE => "Physiotherapist",
            self::TYPE_ACUPUNCTURE_SERVICE => "TCM Physicians"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public static function getTypeTitle($type)
    {
        $list = self::getTypeOptions();
        return isset($list[$type]) ? $list[$type] : 'Not Defined';
    }

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
        return '{{%service_category}}';
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
                    'type_id'
                ],
                'required'
            ],
            [
                'title',
                'unique'
            ],

            [
                [
                    'type_id'
                ],
                'checkUnique'
            ],

            [
                [
                    'state_id',
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
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ]
        ];
    }

    public function checkUnique($attribute)
    {
        $service = self::findOne([
            'type_id' => $this->type_id
        ]);
        if (! empty($service) && $service->id != $this->id) {
            return $this->addError($attribute, 'Already taken');
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
            'title' => Yii::t('app', 'Title'),
            'image_file' => Yii::t('app', 'Image File'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Title'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    public static function getServiceProviderCount($service_type)
    {
        $category = self::findOne([
            'type_id' => $service_type
        ]);
        if (empty($category)) {
            return self::STATE_INACTIVE;
        }
        return UserCategory::find()->where([
            'category_id' => $category->id
        ])->count();
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

    public static function getServiceBookingCount($service_type)
    {
        $category = self::findOne([
            'type_id' => $service_type
        ]);
        if (empty($category)) {
            return self::STATE_INACTIVE;
        }
        return SlotBooking::find()->alias('s')
            ->joinWith([
            'serviceDetail as sd'
        ])
            ->where([
            'sd.id' => $category->id,
            's.state_id' => SlotBooking::STATE_COMPLETED
        ])
            ->count();
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubCategories()
    {
        return $this->hasMany(SubCategory::class, [
            'category_id' => 'id'
        ])->andOnCondition([
            'service_type' => SubCategory::SERVICE_TYPE_NORMAL
        ]);
    }

    public function getDirectBookService()
    {
        return $this->hasMany(SubCategory::class, [
            'category_id' => 'id'
        ])->andOnCondition([
            'service_type' => SubCategory::SERVICE_TYPE_DIRECT_BOOKING
        ]);
    }

    public function getProviderSkills()
    {
        return $this->hasMany(ProviderSkill::class, [
            'category_id' => 'id'
        ]);
    }

    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::class, [
            'category_id' => 'id'
        ])->andOnCondition([
            'uc.created_by_id' => \Yii::$app->user->id
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
        SubCategory::deleteRelatedAll([
            'category_id' => $this->id
        ]);
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

    public function checkIsQualificationSelectedByProvider()
    {
        return UserCategory::find()->where([
            'category_id' => $this->id
        ])->exists();
    }

    public function getImageUrl($thumbnail = false)
    {
        $params = [
            $this->getControllerID() . '/image'
        ];
        $params['id'] = $this->id;

        $params['file'] = $this->image_file;
        if ($thumbnail)
            $params['thumbnail'] = is_numeric($thumbnail) ? $thumbnail : 150;

        return Yii::$app->getUrlManager()->createUrl($params);
    }

    public function checkIsTermAccepted()
    {
        if (! User::isGuest()) {
            return UserTerm::find()->where([
                'category_id' => $this->id
            ])
                ->my()
                ->exists();
        }
        return false;
    }

    public function asJson()
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        if (! empty($this->image_file)) {
            $json['image_file'] = $this->getImageUrl();
        } else {
            $json['image_file'] = '';
        }
        $json['state_id'] = $this->state_id;
        $json['term_accepted'] = $this->checkIsTermAccepted();
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
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
            $model->state_id = $states[rand(0, count($states))];
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

            $model->title = isset($item['title']) ? $item['title'] : $faker->text(10);

            $model->image_file = isset($item['image_file']) ? $item['image_file'] : $faker->text(10);
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
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
