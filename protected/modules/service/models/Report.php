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
use app\modules\availability\models\SlotBooking;

/**
 * This is the model class for table "tbl_service_report".
 *
 * @property integer $id
 * @property string $title
 * @property string $zipcode
 * @property string $city
 * @property string $state
 * @property string $address
 * @property string $description
 * @property integer $age
 * @property integer $booking_id
 * @property integer $service_id
 * @property integer $user_id
 * @property integer $dependant_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class Report extends \app\components\TActiveRecord
{

    const TYPE_SELF = 0;

    const TYPE_DEPENDANT = 1;

    public function __toString()
    {
        return (string) $this->title;
    }

    public static function getBookingOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getBooking()
    {
        $list = self::getBookingOptions();
        return isset($list[$this->booking_id]) ? $list[$this->booking_id] : 'Not Defined';
    }

    public static function getServiceOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getService()
    {
        $list = self::getServiceOptions();
        return isset($list[$this->service_id]) ? $list[$this->service_id] : 'Not Defined';
    }

    public static function getUserOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getUser()
    {
        $list = self::getUserOptions();
        return isset($list[$this->user_id]) ? $list[$this->user_id] : 'Not Defined';
    }

    public static function getDependantOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getDependant()
    {
        $list = self::getDependantOptions();
        return isset($list[$this->dependant_id]) ? $list[$this->dependant_id] : 'Not Defined';
    }

    const STATE_PENDING = 0;

    const STATE_COMPLETE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_PENDING => "Pending",
            self::STATE_COMPLETE => "Completed",
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
            self::STATE_PENDING => "secondary",
            self::STATE_COMPLETE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_PENDING => "Deactivate",
            self::STATE_COMPLETE => "Activate",
            self::STATE_DELETED => "Delete"
        ];
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_SELF => "User",
            self::TYPE_DEPENDANT => "Dependant"
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
        return '{{%service_report}}';
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
                    'booking_id',
                    'created_on',
                    'created_by_id'
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
                    'age',
                    'booking_id',
                    'service_id',
                    'user_id',
                    'dependant_id',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'service_provided'
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
                    'zipcode'
                ],
                'string',
                'max' => 16
            ],
            [
                [
                    'city'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'state'
                ],
                'string',
                'max' => 64
            ],
            [
                [
                    'address'
                ],
                'string',
                'max' => 512
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
                    'zipcode',
                    'city',
                    'state',
                    'address'
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
            'title' => Yii::t('app', 'User'),
            'zipcode' => Yii::t('app', 'Zipcode'),
            'city' => Yii::t('app', 'City'),
            'state' => Yii::t('app', 'State'),
            'address' => Yii::t('app', 'Address'),
            'description' => Yii::t('app', 'Description'),
            'age' => Yii::t('app', 'Age'),
            'booking_id' => Yii::t('app', 'Booking'),
            'service_id' => Yii::t('app', 'Service'),
            'user_id' => Yii::t('app', 'User'),
            'dependant_id' => Yii::t('app', 'Dependant'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Provider')
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

    public function getUserDetail()
    {
        return $this->hasOne(User::className(), [
            'id' => 'user_id'
        ])->cache(5);
    }

    public function getBookingDetail()
    {
        return $this->hasOne(SlotBooking::className(), [
            'id' => 'booking_id'
        ]);
    }

    public function getDependantDetail()
    {
        return $this->hasOne(User::className(), [
            'id' => 'dependant_id'
        ])->cache(5);
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
        $relations['dependant_id'] = [
            'dependantDetail',
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

    public function getUserAttributeValue($attribute)
    {
        $bookingUser = ($this->type_id == self::TYPE_SELF) ? $this->userDetail : $this->dependantDetail;
        return ! empty($bookingUser) ? $bookingUser->$attribute : self::STATE_PENDING;
    }

    public function getBookingDate()
    {
        $booking = $this->bookingDetail;
        return ! empty($booking) ? date('Y-m-d', strtotime($booking->start_time)) : '';
    }

    public function getBookingTime()
    {
        $booking = $this->bookingDetail;
        return ! empty($booking) ? date('h-i a', strtotime($booking->start_time)) . ' - ' . date('h-i a', strtotime($booking->end_time)) : '';
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['user_name'] = $this->title;
        $json['ic_number'] = $this->getUserAttributeValue('identity_number');
        $json['gender'] = $this->getUserAttributeValue('gender');
        $json['email'] = $this->getUserAttributeValue('email');
        $json['booking_date'] = $this->getBookingDate();
        $json['booking_time'] = $this->getBookingTime();
        $json['zipcode'] = $this->zipcode;
        $json['city'] = $this->city;
        $json['state'] = $this->state;
        $json['address'] = $this->address;
        $json['service_provided'] = $this->service_provided;
        $json['description'] = $this->description;
        $json['age'] = $this->age;
        $json['booking_id'] = $this->booking_id;
        $json['service_id'] = $this->service_id;
        $json['user_id'] = $this->user_id;
        $json['dependant_id'] = $this->dependant_id;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['provider_name'] = ! empty($this->createdBy) ? $this->createdBy->full_name : '';
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
            $model->zipcode = $faker->text(10);
            $model->city = $faker->text(10);
            $model->state = 0;
            $model->address = $faker->text(10);
            $model->description = $faker->text;
            $model->age = $faker->text(10);
            $model->booking_id = 1;
            $model->service_id = 1;
            $model->user_id = 1;
            $model->dependant_id = 1;
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

            $model->zipcode = isset($item['zipcode']) ? $item['zipcode'] : $faker->text(10);

            $model->city = isset($item['city']) ? $item['city'] : $faker->text(10);

            $model->state = isset($item['state']) ? $item['state'] : 0;

            $model->address = isset($item['address']) ? $item['address'] : $faker->text(10);

            $model->description = isset($item['description']) ? $item['description'] : $faker->text;

            $model->age = isset($item['age']) ? $item['age'] : $faker->text(10);

            $model->booking_id = isset($item['booking_id']) ? $item['booking_id'] : 1;

            $model->service_id = isset($item['service_id']) ? $item['service_id'] : 1;

            $model->user_id = isset($item['user_id']) ? $item['user_id'] : 1;

            $model->dependant_id = isset($item['dependant_id']) ? $item['dependant_id'] : 1;
            $model->state_id = self::STATE_COMPLETE;

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
