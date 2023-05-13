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
namespace app\modules\contact\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "tbl_contact_address".
 *
 * @property integer $id
 * @property string $title
 * @property string $address
 * @property string $email
 * @property string $tel
 * @property string $mobile
 * @property string $latitude
 * @property string $longitude
 * @property string $country
 * @property integer $state_id
 * @property integer $image_file
 * @property string $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class Address extends \app\components\TActiveRecord
{

    const TYPE_INDIA = 0;

    const TYPE_INTERNATIONAL = 1;

    const TYPE_US = 2;

    const TYPE_UK = 3;

    const TYPE_AUSTRALIA = 4;

    const TYPE_SINGAPORA = 5;

    const TYPE_CANADA = 6;

    public function __toString()
    {
        return (string) $this->title;
    }

    public static function getTypeOptions($id = null)
    {
        $list = array(
            self::TYPE_INDIA => "India",
            self::TYPE_INTERNATIONAL => "International",
            self::TYPE_US => "US",
            self::TYPE_UK => "UK",
            self::TYPE_AUSTRALIA => "Australia",
            self::TYPE_SINGAPORA => "Singapora",
            self::TYPE_CANADA => "Canada"
        );
        if ($id === null)
            return $list;
        return isset($list[$id]) ? $list[$id] : 'Not Defined';
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

    public static function getActionOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Archive"
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

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public static function getCreatedByOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
        // return ArrayHelper::Map ( CreatedBy::findActive ()->all (), 'id', 'title' );
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
            if (! isset($this->updated_on))
                $this->updated_on = date('Y-m-d H:i:s');
            if (! isset($this->created_by_id))
                $this->created_by_id = self::getCurrentUser();
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
        return '{{%contact_address}}';
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
                    'address',
                    'email',
                    'latitude',
                    'longitude',
                    'country',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'state_id',
                    'created_by_id',
                    'image_file'
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
                    'title',
                    'address',
                    'email',
                    'tel',
                    'mobile',
                    'latitude',
                    'longitude',
                    'country',
                    'type_id'
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
                    'title',
                    'address',
                    'email',
                    'tel',
                    'mobile',
                    'latitude',
                    'longitude',
                    'country',
                    'type_id'
                ],
                'trim'
            ],
            [
                [
                    'email'
                ],
                'email'
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
            'address' => Yii::t('app', 'Address'),
            'email' => Yii::t('app', 'Email'),
            'tel' => Yii::t('app', 'Tel'),
            'mobile' => Yii::t('app', 'Mobile'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'country' => Yii::t('app', 'Country'),
            'state_id' => Yii::t('app', 'State'),
            'image_file' => Yii::t('app', 'Image'),
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
        ])->cache();
    }
    
    public static function getAllAddress(){
        return Address::findActive();
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Phone::className(), [
            'country' => 'country'
        ]);
    }

    public function getActiveContacts()
    {
        return $this->getContacts()->active();
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

    public static function detail($type_id)
    {
        $response = self::findActive()->andwhere([
            'type_id' => $type_id
        ])
            ->orderBy([
            'id' => SORT_DESC
        ])
            ->one();
        return $response;
    }

    public static function detailAll($type_id)
    {
        $response = self::findActive()->andwhere([
            'type_id' => $type_id
        ])->all();
        return $response;
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
        $json['title'] = $this->title;
        $json['address'] = $this->address;
        $json['email'] = $this->email;
        $json['tel'] = $this->tel;
        $json['mobile'] = $this->mobile;
        $json['latitude'] = $this->latitude;
        $json['longitude'] = $this->longitude;
        $json['country'] = $this->country;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['image_file'] = $this->image_file;
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
        return '/contact/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->title = $faker->text(10);
            $model->address = $faker->text(10);
            $model->email = $faker->email;
            $model->tel = $faker->text(10);
            $model->mobile = $faker->text(10);
            $model->latitude = $faker->text(10);
            $model->longitude = $faker->text(10);
            $model->country = $faker->text(10);
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

            $model->address = isset($item['address']) ? $item['address'] : $faker->text(10);

            $model->email = isset($item['email']) ? $item['email'] : $faker->email;

            $model->tel = isset($item['tel']) ? $item['tel'] : $faker->text(10);

            $model->mobile = isset($item['mobile']) ? $item['mobile'] : $faker->text(10);

            $model->latitude = isset($item['latitude']) ? $item['latitude'] : $faker->text(10);

            $model->longitude = isset($item['longitude']) ? $item['longitude'] : $faker->text(10);

            $model->country = isset($item['country']) ? $item['country'] : $faker->text(10);
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

