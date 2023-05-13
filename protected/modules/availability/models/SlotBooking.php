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
namespace app\modules\availability\models;

use app\models\User;
use Yii;
use app\modules\service\models\SubCategory;
use app\modules\service\models\Category;
use app\modules\notification\models\Notification;
use app\modules\workzone\models\Location;
use app\modules\service\models\Report;
use app\modules\rating\models\Rating;
use app\models\Earning;

/**
 * This is the model class for table "tbl_availability_slot_booking".
 *
 * @property integer $id
 * @property string $start_time
 * @property string $end_time
 * @property integer $provider_id
 * @property integer $dependant_id
 * @property integer $service_id
 * @property string $slot_id
 * @property string $payment_status
 * @property string $description
 * @property integer $provider_reschedule
 * @property integer $user_reschedule
 * @property integer $is_reschedule_confirm
 * @property string $old_start_time
 * @property string $old_end_time
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class SlotBooking extends \app\components\TActiveRecord
{

    public $service_ids;

    public $service_type;

    const STATE_PENDING = 0;

    const STATE_INPROGRESS = 1;

    const STATE_CANCELED = 2;

    const STATE_COMPLETED = 3;

    const PAYMENT_PENDING = 0;

    const PAYMENT_SUCCESS = 1;

    const PAYMENT_FAILED = 2;

    const TYPE_SELF = 0;

    const TYPE_DEPENDANT = 1;

    const APPOINTMENT_TYPE_UPCOMING = 1;

    const APPOINTMENT_TYPE_HISTORY = 2;

    const USER_RESCHEDULE_NO = 0;

    const USER_RESCHEDULE_YES = 1;

    const RESCHEDULE_CONFIRMED_YES = 1;

    const RESCHEDULE_CONFIRMED_NO = 0;

    const SUCCESS_RESPONSE = 'RECEIVEOK';

    public function __toString()
    {
        return (string) $this->start_time;
    }

    public static function getStateOptions()
    {
        return [
            self::STATE_PENDING => "Upcoming",
            self::STATE_INPROGRESS => "In Progress",
            self::STATE_CANCELED => "Canceled",
            self::STATE_COMPLETED => "Completed"
        ];
    }

    public static function getPaymentStateOptions()
    {
        return [
            self::PAYMENT_PENDING => "Pending",
            self::PAYMENT_SUCCESS => "Success",
            self::PAYMENT_FAILED => "Failed"
        ];
    }

    public function getPaymentState()
    {
        $list = self::getPaymentStateOptions();
        return isset($list[$this->payment_status]) ? $list[$this->payment_status] : 'Not Defined';
    }

    public function getPaymentStateBadge()
    {
        $list = [
            self::PAYMENT_PENDING => "secondary",
            self::PAYMENT_SUCCESS => "success",
            self::PAYMENT_FAILED => "danger"
        ];
        return isset($list[$this->payment_status]) ? \yii\helpers\Html::tag('span', $this->getPaymentState(), [
            'class' => 'badge badge-' . $list[$this->payment_status]
        ]) : 'Not Defined';
    }

    public static function getUserRescheduleTypeOptions()
    {
        return [
            self::USER_RESCHEDULE_NO => "No",
            self::USER_RESCHEDULE_YES => "Yes"
        ];
    }

    public function getUserRescheduleType()
    {
        $list = self::getUserRescheduleTypeOptions();
        return isset($list[$this->user_reschedule]) ? $list[$this->user_reschedule] : 'Not Defined';
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
            self::STATE_INPROGRESS => "warning",
            self::STATE_CANCELED => "danger",
            self::STATE_COMPLETED => "success"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_PENDING => "Pending",
            self::STATE_INPROGRESS => "Accept",
            self::STATE_CANCELED => "Cancel",
            self::STATE_COMPLETED => "Complete"
        ];
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_SELF => "Self",
            self::TYPE_DEPENDANT => "Dependant"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public static function getProviderOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getProvider()
    {
        $list = self::getProviderOptions();
        return isset($list[$this->provider_id]) ? $list[$this->provider_id] : 'Not Defined';
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
    
    public static function getServiceTypeOptions()
    {
        return self::listData(Category::findActive()->each());
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

    public static function getSlotOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getSlot()
    {
        $list = self::getSlotOptions();
        return isset($list[$this->slot_id]) ? $list[$this->slot_id] : 'Not Defined';
    }

    public static function getBookingStates($type)
    {
        if ($type == self::APPOINTMENT_TYPE_UPCOMING) {
            $arr = [
                self::STATE_PENDING,
                self::STATE_INPROGRESS
            ];
        } else {
            $arr = [
                self::STATE_COMPLETED,
                self::STATE_CANCELED
            ];
        }
        return $arr;
    }

    public static function getBookingCount($state = '')
    {
        $query = SlotBooking::find()->where([
            'payment_status' => SlotBooking::PAYMENT_SUCCESS
        ]);
        if ($state) {
            $query->andWhere([
                'state_id' => $state
            ]);
        }
        $object = new self();
        $from_date = $object->getDateRangeValue('from_date');
        $to_date = $object->getDateRangeValue('to_date');
        if (! empty($from_date) && ! empty($to_date)) {
            $query->andWhere([
                'and',
                [
                    '>=',
                    "date(start_time)",
                    $from_date
                ],
                [
                    '<=',
                    "date(start_time)",
                    $to_date
                ]
            ]);
        }
        return $query->count();
    }

    public static function getBookingReportCount()
    {
        return Report::find()->andState([
            Report::STATE_COMPLETE
        ])->count();
    }

    public static function getTodayBookingCount($date)
    {
        return SlotBooking::find()->where([
            'payment_status' => SlotBooking::PAYMENT_SUCCESS,
            'date(start_time)' => $date
        ])->count();
    }

    public function getAdminRevenue()
    {
        $user_amount = ! empty($this->user_amount) ? $this->user_amount : self::STATE_PENDING;
        $provider_amount = ! empty($this->provider_amount) ? $this->provider_amount : self::STATE_PENDING;
        $admin_amount = $user_amount - $provider_amount;
        return ! empty($admin_amount) ? strval(round($admin_amount, 2)) : strval(self::STATE_PENDING);
    }

    public static function getBookingRevenue($date = '')
    {
        $query = SlotBooking::find()->where([
            'payment_status' => SlotBooking::PAYMENT_SUCCESS,
            'state_id' => SlotBooking::STATE_COMPLETED
        ]);
        if (! empty($date)) {
            $query->andWhere([
                'date(start_time)' => $date
            ]);
        }
        $admin_amount = $query->sum('admin_revenue');

        return ! empty($admin_amount) ? round($admin_amount, 2) : self::STATE_PENDING;
    }

    /**
     * Return completed bookings revenue
     *
     * @return number|string
     */
    public static function getRevenue()
    {
        $query = SlotBooking::find()->where([
            'payment_status' => SlotBooking::PAYMENT_SUCCESS,
            'state_id' => SlotBooking::STATE_COMPLETED
        ]);
        $object = new self();
        $from_date = $object->getDateRangeValue('from_date');
        $to_date = $object->getDateRangeValue('to_date');
        if (! empty($from_date) && ! empty($to_date)) {
            $query->andWhere([
                'and',
                [
                    '>=',
                    "date(start_time)",
                    $from_date
                ],
                [
                    '<=',
                    "date(start_time)",
                    $to_date
                ]
            ]);
        }
        $admin_amount = $query->sum('admin_revenue');

        return ! empty($admin_amount) ? round($admin_amount, 2) : self::STATE_PENDING;
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

    public function saveEarning()
    {
        $exists = Earning::find()->where([
            'booking_id' => $this->id
        ])->exists();
        if ($exists) {
            return true;
        }
        $model = new Earning();
        $model->booking_id = $this->id;
        $model->provider_id = $this->provider_id;
        $model->provider_amount = $this->provider_amount;
        $model->booking_amount = strval($this->user_amount);
        $model->admin_amount = strval($this->admin_revenue);
        $model->state_id = Earning::STATE_ACTIVE;

        if ($model->save()) {
            return true;
        }
        return false;
    }

    public function sendBookingNotificationToUser()
    {
        $message = \Yii::t('app', 'Your payment is successful & your booking is confirmed');
        Notification::create([
            'html' => $message,
            'to_user_id' => $this->created_by_id,
            'created_by_id' => $this->provider_id,
            'title' => $message,
            'model' => $this,
            'type_id' => Notification::TYPE_BOOKING_NOTIFICATION
        ], false);
    }

    public function sendBookingNotificationToProvider($user_name)
    {
        $message = \Yii::t('app', 'You have a new booking from ' . $user_name);
        Notification::create([
            'html' => $message,
            'to_user_id' => $this->provider_id,
            'created_by_id' => $this->created_by_id,
            'title' => $message,
            'model' => $this,
            'type_id' => Notification::TYPE_BOOKING_NOTIFICATION
        ], false);
    }

    public static function getProviderBookingStates($type)
    {
        if ($type == self::APPOINTMENT_TYPE_UPCOMING) {
            $arr = [
                self::STATE_PENDING
            ];
        } else {
            $arr = [
                self::STATE_COMPLETED,
                self::STATE_CANCELED
            ];
        }
        return $arr;
    }

    public function sendRescheduleNotificationToUser()
    {
        $message = \Yii::t('app', 'Appointment rescheule successful.Your appointment has been updated');
        Notification::create([
            'html' => $message,
            'to_user_id' => $this->created_by_id,
            'created_by_id' => $this->provider_id,
            'title' => $message,
            'model' => $this,
            'type_id' => Notification::TYPE_BOOKING_NOTIFICATION
        ], false);
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%availability_slot_booking}}';
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
                    'start_time',
                    'end_time',
                    'provider_id',
                    'slot_id',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'start_time',
                    'end_time',
                    'old_start_time',
                    'old_end_time',
                    'created_on',
                    'transaction_id',
                    'cancel_date',
                    'cancel_reason',
                    'service_ids',
                    'address',
                    'order_id',
                    'description'
                ],
                'safe'
            ],
            [
                [
                    'provider_id',
                    'dependant_id',
                    'service_id',
                    'provider_reschedule',
                    'user_reschedule',
                    'is_reschedule_confirm',
                    'state_id',
                    'type_id',
                    'created_by_id',
                    'payment_status',
                    'workzone_id',
                    'service_type'
                ],
                'integer'
            ],

            [
                [
                    'slot_id',
                    'zipcode',
                    'user_amount',
                    'provider_amount',
                    'admin_revenue',
                    'order_id'
                ],
                'string',
                'max' => 16
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
                    'slot_id'
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
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'provider_id' => Yii::t('app', 'Service Provider'),
            'dependant_id' => Yii::t('app', 'Dependant'),
            'service_id' => Yii::t('app', 'Service'),
            'slot_id' => Yii::t('app', 'Slot'),
            'description' => Yii::t('app', 'Description'),
            'provider_reschedule' => Yii::t('app', 'Provider Reschedule'),
            'user_reschedule' => Yii::t('app', 'Rescheduled'),
            'is_reschedule_confirm' => Yii::t('app', 'Is Reschedule Confirm'),
            'old_start_time' => Yii::t('app', 'Old Start Time'),
            'old_end_time' => Yii::t('app', 'Old End Time'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'User'),
            'workzone_id' => Yii::t('app', 'Service Location')
        ];
    }

    public function checkIsBookingExists()
    {
        return SlotBooking::find()->where([
            // 'slot_id' => $this->slot_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'provider_id' => $this->provider_id,
            'payment_status' => SlotBooking::PAYMENT_SUCCESS
        ])
            ->andWhere([
            'in',
            'state_id',
            [
                SlotBooking::STATE_PENDING,
                SlotBooking::STATE_INPROGRESS
            ]
        ])
            ->exists();
    }

    public function getBookedServicesName()
    {
        $services = $this->getBookingServices()
            ->select('title')
            ->column();
        return implode(',', $services ?? '');
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, [
            'id' => 'created_by_id'
        ])->cache(5);
    }

    public function getProviderDetail()
    {
        return $this->hasOne(User::class, [
            'id' => 'provider_id'
        ]);
    }

    public function getBookedServicesTitle()
    {
        $services = $this->getBookingServices()
            ->select('title')
            ->column();
        return implode(',.', $services ?? '');
    }

    public function getDependantDetail()
    {
        return $this->hasOne(User::class, [
            'id' => 'dependant_id'
        ])->cache(5);
    }

    public function getSkillDetail()
    {
        return $this->hasOne(SubCategory::class, [
            'id' => 'service_id'
        ])->cache(5);
    }

    public function getBookingServices()
    {
        return $this->hasMany(BookingService::class, [
            'booking_id' => 'id'
        ])->cache(5);
    }

    public function getReportDetail()
    {
        return $this->hasOne(Report::class, [
            'booking_id' => 'id'
        ]);
    }

    public function getWorkZone()
    {
        return $this->hasOne(Location::class, [
            'id' => 'workzone_id'
        ]);
    }

    public function getServiceDetail()
    {
        return $this->hasOne(Category::class, [
            'id' => 'category_id'
        ])
            ->cache(5)
            ->via('skillDetail');
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
        $relations['provider_id'] = [
            'providerDetail',
            'User',
            'id'
        ];
        $relations['dependant_id'] = [
            'dependantDetail',
            'User',
            'id'
        ];
        $relations['workzone_id'] = [
            'workZone',
            'Location',
            'id'
        ];
        $relations['service_id'] = [
            'skillDetail',
            'SubCategory',
            'id'
        ];
        $relations['service_type'] = [
            'serviceDetail',
            'Category',
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

    public function getServiceProvided()
    {
        if (empty($this->serviceDetail)) {
            return 'Not defined';
        }
        $service_name = $this->serviceDetail->title;
        $sub_services = $this->getBookedServicesName();
        return $service_name . ' - ' . $sub_services;
    }

    public function saveServiceReport()
    {
        $bookingUser = ($this->type_id == self::TYPE_SELF) ? $this->createdBy : $this->dependantDetail;
        if (empty($bookingUser)) {
            return false;
        }
        $service_report = new Report();
        $service_report->title = $bookingUser->full_name;
        $service_report->age = $bookingUser->getAge();
        $service_report->user_id = $this->created_by_id;
        $service_report->service_id = $this->service_id;
        $service_report->address = $this->address;
        $service_report->dependant_id = $this->dependant_id;
        $service_report->service_provided = $this->getServiceProvided();
        $service_report->type_id = $this->type_id;
        $service_report->zipcode = $this->zipcode;
        $service_report->booking_id = $this->id;
        $service_report->state_id = Report::STATE_PENDING;
        if ($service_report->save()) {
            return true;
        }
        return false;
    }

    public function beforeSave($insert)
    {
        if (! parent::beforeSave($insert)) {
            return false;
        }
        // TODO : start here

        return true;
    }

    public function getPatientDetail()
    {
        if ($this->type_id == self::TYPE_SELF) {
            return ! empty($this->createdBy) ? $this->createdBy->asBookingJson() : (object) [];
        }
        return ! empty($this->dependantDetail) ? $this->dependantDetail->asBookingJson() : (object) [];
    }

    public function checkIsRating()
    {
        return Rating::find()->where([
            'model_id' => $this->id,
            'model_type' => self::class
        ])->exists();
    }

    public function asPaymentJson()
    {
        $json = [];
        $json['id'] = $this->id;
        $json['order_id'] = $this->order_id;
        $json['start_time'] = $this->start_time;
        $json['end_time'] = $this->end_time;
        $json['created_on'] = $this->created_on;
        $json['payment_status'] = $this->payment_status;
        $json['service_name'] = ! empty($this->serviceDetail) ? $this->serviceDetail->title : '';
        $json['skill_name'] = $this->getBookedServicesTitle();
        $json['user_amount'] = $this->user_amount;
        return $json;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $action = \Yii::$app->controller->action->id;
        if ($action == 'payment-history') {
            return $this->asPaymentJson();
        }
        $json['id'] = $this->id;
        $json['order_id'] = $this->order_id;
        $json['start_time'] = $this->start_time;
        $json['end_time'] = $this->end_time;
        $json['provider_id'] = $this->provider_id;
        $json['dependant_id'] = $this->dependant_id;
        $json['service_id'] = $this->service_id;
        $json['slot_id'] = $this->slot_id;
        $json['user_reschedule'] = $this->user_reschedule;
        $json['cancel_reason'] = $this->cancel_reason;
        $json['cancel_date'] = $this->cancel_date;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['payment_status'] = $this->payment_status;
        $json['provider_detail'] = ! empty($this->providerDetail) ? $this->providerDetail->asBookingJson() : (object) [];
        $json['patient_detail'] = $this->getPatientDetail();
        $json['skill_detail'] = ! empty($this->skillDetail) ? $this->skillDetail->asBookingJson() : (object) [];
        $json['service_name'] = ! empty($this->serviceDetail) ? $this->serviceDetail->title : '';
        $json['skill_name'] = $this->getBookedServicesTitle();
        $json['is_rating'] = $this->checkIsRating();
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
        return '/availability/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->start_time = \date('Y-m-d H:i:s');
            $model->end_time = \date('Y-m-d H:i:s');
            $model->provider_id = 1;
            $model->dependant_id = 1;
            $model->service_id = 1;
            $model->slot_id = 1;
            $model->description = $faker->text;
            $model->provider_reschedule = $faker->text(10);
            $model->user_reschedule = $faker->text(10);
            $model->is_reschedule_confirm = $faker->text(10);
            $model->old_start_time = \date('Y-m-d H:i:s');
            $model->old_end_time = \date('Y-m-d H:i:s');
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

            $model->start_time = isset($item['start_time']) ? $item['start_time'] : \date('Y-m-d H:i:s');

            $model->end_time = isset($item['end_time']) ? $item['end_time'] : \date('Y-m-d H:i:s');

            $model->provider_id = isset($item['provider_id']) ? $item['provider_id'] : 1;

            $model->dependant_id = isset($item['dependant_id']) ? $item['dependant_id'] : 1;

            $model->service_id = isset($item['service_id']) ? $item['service_id'] : 1;

            $model->slot_id = isset($item['slot_id']) ? $item['slot_id'] : 1;

            $model->description = isset($item['description']) ? $item['description'] : $faker->text;

            $model->provider_reschedule = isset($item['provider_reschedule']) ? $item['provider_reschedule'] : $faker->text(10);

            $model->user_reschedule = isset($item['user_reschedule']) ? $item['user_reschedule'] : $faker->text(10);

            $model->is_reschedule_confirm = isset($item['is_reschedule_confirm']) ? $item['is_reschedule_confirm'] : $faker->text(10);

            $model->old_start_time = isset($item['old_start_time']) ? $item['old_start_time'] : \date('Y-m-d H:i:s');

            $model->old_end_time = isset($item['old_end_time']) ? $item['old_end_time'] : \date('Y-m-d H:i:s');
            $model->state_id = self::STATE_REQUEST;

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

    public function uniqueOrderId()
    {
        return Yii::$app->security->generateRandomString(5) . $this->id;
    }

    public static function daily($dateAttribute = 'start_time', $state = null, $created_by_id = null)
    {
        $date = new \DateTime();
        $date->modify('-30 days');

        $count = [];
        $query = SlotBooking::find()->cache(60);
        for ($i = 1; $i <= 30; $i ++) {
            $date->modify('+1 days');
            $day = $date->format('Y-m-d');

            $query->where([
                "DATE($dateAttribute)" => $day
            ])->andWhere([
                'payment_status' => SlotBooking::PAYMENT_SUCCESS,
                'state_id' => SlotBooking::STATE_COMPLETED
            ]);

            $count[$day] = (int) $query->sum('admin_revenue');
        }
        return $count;
    }

    public static function weekly($dateAttribute = 'start_time', $state = null, $created_by_id = null)
    {
        $date = new \DateTime();
        $date->modify('-7 days ');
        $count = [];
        $query = SlotBooking::find()->cache(30);
        for ($i = 1; $i <= 7; $i ++) {
            $date->modify('1 days');
            $day = $date->format('Y-m-d');
            $query->where([
                "DATE($dateAttribute)" => $day
            ])->andWhere([
                'payment_status' => SlotBooking::PAYMENT_SUCCESS,
                'state_id' => SlotBooking::STATE_COMPLETED
            ]);

            $count[$day] = (int) $query->sum('admin_revenue');
        }
        return $count;
    }

    public static function monthly($dateAttribute = 'start_time', $state = null, $created_by_id = null)
    {
        $date = new \DateTime(date('Y-m'));

        $date->modify('-1 year');

        $count = [];
        $query = SlotBooking::find()->cache(10);
        for ($i = 1; $i <= 12; $i ++) {
            $date->modify('+1 months');
            $year = $date->format('Y');
            $month = $date->format('m');

            $query->where([
                "MONTH($dateAttribute)" => $month,
                "YEAR($dateAttribute)" => $year
            ])->andWhere([
                'payment_status' => SlotBooking::PAYMENT_SUCCESS,
                'state_id' => SlotBooking::STATE_COMPLETED
            ]);

            $count[$month] = (int) $query->sum('admin_revenue');
        }
        return $count;
    }

    public static function yearly($dateAttribute = 'start_time', $state = null, $created_by_id = null)
    {
        $date = new \DateTime(date('Y'));
        $date->modify('-12 year');

        $count = [];
        $query = SlotBooking::find()->cache(10);
        for ($i = 1; $i <= 12; $i ++) {
            $date->modify('+1 years');
            $year = $date->format('Y');

            $query->where([
                'like',
                $dateAttribute,
                $year
            ])->andWhere([
                'payment_status' => SlotBooking::PAYMENT_SUCCESS,
                'state_id' => SlotBooking::STATE_COMPLETED
            ]);
            $count[$year] = (int) $query->sum('admin_revenue');
        }
        return $count;
    }

    public static function dailyBooking($state = null, $role = null, $created_by_id = null, $dateAttribute = 'start_time')
    {
        $date = new \DateTime();
        $date->modify('-30 days');

        $count = [];
        $query = SlotBooking::find()->cache(60);
        for ($i = 1; $i <= 30; $i ++) {
            $date->modify('+1 days');
            $day = $date->format('Y-m-d');

            $query->where([
                "DATE($dateAttribute)" => $day
            ])->andWhere([
                'payment_status' => SlotBooking::PAYMENT_SUCCESS
            ]);
            if ($created_by_id !== null) {
                $query->andWhere([
                    'created_by_id' => $created_by_id
                ]);
            }
            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }
            if ($role !== null) {
                $role = is_array($role) ? $role : [
                    $role
                ];
                $query->andWhere([

                    'role_id' => $role,
                    'type_id' => User::TYPE_USER,
                    'step' => User::STEP_THREE
                ]);
                if ($role == User::ROLE_SERVICE_PROVIDER) {
                    $query->andWhere([
                        'is_approve' => User::IS_APPROVE
                    ]);
                }
            }

            $count[$day] = (int) $query->count();
        }
        return $count;
    }

    public static function weeklyBooking($state = null, $role = null, $created_by_id = null, $dateAttribute = 'start_time')
    {
        $date = new \DateTime();
        $date->modify('-7 days ');
        $count = [];
        $query = SlotBooking::find()->cache(30);
        for ($i = 1; $i <= 7; $i ++) {
            $date->modify('1 days');
            $day = $date->format('Y-m-d');
            $query->where([
                "DATE($dateAttribute)" => $day
            ])->andWhere([
                'payment_status' => SlotBooking::PAYMENT_SUCCESS
            ]);
            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }
            if ($role !== null) {
                $role = is_array($role) ? $role : [
                    $role
                ];
                $query->andWhere([

                    'role_id' => $role,
                    'type_id' => User::TYPE_USER,
                    'step' => User::STEP_THREE
                ]);
                if ($role == User::ROLE_SERVICE_PROVIDER) {
                    $query->andWhere([
                        'is_approve' => User::IS_APPROVE
                    ]);
                }
            }
            $count[$day] = (int) $query->count();
        }
        return $count;
    }

    public static function monthlyBooking($state = null, $role = null, $created_by_id = null, $dateAttribute = 'start_time')
    {
        $date = new \DateTime(date('Y-m'));

        $date->modify('-1 year');

        $count = [];
        $query = SlotBooking::find()->cache(10);
        for ($i = 1; $i <= 12; $i ++) {
            $date->modify('+1 months');
            $year = $date->format('Y');
            $month = $date->format('m');

            $query->where([
                "MONTH($dateAttribute)" => $month,
                "YEAR($dateAttribute)" => $year
            ])->andWhere([
                'payment_status' => SlotBooking::PAYMENT_SUCCESS
            ]);
            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }
            if ($role !== null) {
                $role = is_array($role) ? $role : [
                    $role
                ];
                $query->andWhere([

                    'role_id' => $role,
                    'type_id' => User::TYPE_USER,
                    'step' => User::STEP_THREE
                ]);
                if ($role == User::ROLE_SERVICE_PROVIDER) {
                    $query->andWhere([
                        'is_approve' => User::IS_APPROVE
                    ]);
                }
            }

            $count[$month] = (int) $query->count();
        }
        return $count;
    }

    public static function yearlyBooking($role = null, $state = null, $dateAttribute = 'start_time')
    {
        $date = new \DateTime(date('Y'));
        $date->modify('-12 year');

        $count = [];
        $query = SlotBooking::find()->cache(10);
        for ($i = 1; $i <= 12; $i ++) {
            $date->modify('+1 years');
            $year = $date->format('Y');

            $query->where([
                'like',
                $dateAttribute,
                $year
            ])->andWhere([
                'payment_status' => SlotBooking::PAYMENT_SUCCESS
            ]);

            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }
            if ($role !== null) {
                $role = is_array($role) ? $role : [
                    $role
                ];
                $query->andWhere([
                    'role_id' => $role,
                    'type_id' => User::TYPE_USER,
                    'step' => User::STEP_THREE
                ]);
                if ($role == User::ROLE_SERVICE_PROVIDER) {
                    $query->andWhere([
                        'is_approve' => User::IS_APPROVE
                    ]);
                }
            }
            $count[$year] = (int) $query->count();
        }
        return $count;
    }
}
