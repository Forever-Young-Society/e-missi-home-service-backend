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
use app\modules\service\models\Category;

/**
 * This is the model class for table "tbl_availability_provider_slot".
 *
 * @property integer $id
 * @property string $start_time
 * @property string $end_time
 * @property integer $availability_slot_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class ProviderSlot extends \app\components\TActiveRecord
{

    public $slot;

    public $provider_id;

    const ONLY_START_TIME = 1;

    const BOTH_START_AND_END = 2;

    public function __toString()
    {
        return (string) $this->start_time;
    }

    public static function getAvailabilitySlotOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getAvailabilitySlot()
    {
        $list = self::getAvailabilitySlotOptions();
        return isset($list[$this->availability_slot_id]) ? $list[$this->availability_slot_id] : 'Not Defined';
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            // self::STATE_INACTIVE => "New",
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
                $this->created_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
            }
        } else {}
        return parent::beforeValidate();
    }

    public function checkIsSlotExists()
    {
        return ProviderSlot::find()->where([
            'created_by_id' => $this->provider_id,
            'type_id' => $this->type_id
        ])
            ->andWhere([
            'between',
            'start_time',
            $this->start_time,
            $this->end_time
        ])
            ->exists();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%availability_provider_slot}}';
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
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'start_time',
                    'end_time',
                    'created_on',
                    'slot'
                ],
                'safe'
            ],
            [
                [
                    'availability_slot_id',
                    'state_id',
                    'type_id',
                    'created_by_id',
                    'provider_id'
                ],
                'integer'
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
            'availability_slot_id' => Yii::t('app', 'Availability Slot'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Service Provider')
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

    /**
     * Get total provider slots count
     *
     * @return $count
     */
    public static function getProviderSlotsCount()
    {
        $query = self::findActive();
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

    public function checkIsNursingSlotBooked()
    {
        return SlotBooking::find()->where([
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'provider_id' => $this->created_by_id,
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

    public function checkIsCaregiverSlotBooked()
    {
        $query = SlotBooking::find()->where([
            'provider_id' => $this->created_by_id,
            'payment_status' => SlotBooking::PAYMENT_SUCCESS
        ])->andWhere([
            'in',
            'state_id',
            [
                SlotBooking::STATE_PENDING,
                SlotBooking::STATE_INPROGRESS
            ]
        ]);
        $query_one = clone $query;
        $slotModel = $query->andWhere([
            'start_time' => $this->start_time,
            'end_time' => $this->end_time
        ])->exists();
        if ($slotModel) {
            return true;
        }
        $date = date('Y-m-d', strtotime($this->start_time));
        if ($this->end_time == $date . ' ' . Slot::CAREGIVER_THIRD_SLOT_END) {
            $nextSlot = $query_one->andWhere([
                'and',
                [
                    '>=',
                    'start_time',
                    $date . ' ' . Slot::CAREGIVER_THIRD_SLOT_START
                ],
                [
                    '<=',
                    'end_time',
                    $date . ' ' . Slot::CAREGIVER_THIRD_SLOT_END
                ]
            ])->exists();
        } else {
            $nextSlot = $query_one->andWhere([
                'start_time' => $date . ' ' . Slot::CAREGIVER_THIRD_SLOT_START,
                'end_time' => $date . ' ' . Slot::CAREGIVER_THIRD_SLOT_END
            ])->exists();
        }
        if ($nextSlot) {
            return true;
        }
        return false;
    }

    public function checkIsTcmSlotBooked()
    {
        $query = SlotBooking::find()->where([
            'provider_id' => $this->created_by_id,
            'payment_status' => SlotBooking::PAYMENT_SUCCESS
        ])->andWhere([
            'in',
            'state_id',
            [
                SlotBooking::STATE_PENDING,
                SlotBooking::STATE_INPROGRESS
            ]
        ]);
        $query_one = clone $query;
        $query_two = clone $query;
        $slotModel = $query->andWhere([
            'start_time' => $this->start_time,
            'end_time' => $this->end_time
        ])->exists();
        if ($slotModel) {
            return true;
        }
        $new_slot_end_time = date('Y-m-d H:i:s', strtotime($this->end_time . "+ 30 minute"));
        $nextSlot = $query_one->andWhere([
            'start_time' => $this->end_time,
            'end_time' => $new_slot_end_time
        ])->exists();
        if ($nextSlot) {
            return true;
        }
        $previous_slot_start_time = date('Y-m-d H:i:s', strtotime($this->start_time . "- 30 minute"));
        $previousSlot = $query_two->andWhere([
            'start_time' => $previous_slot_start_time,
            'end_time' => $this->start_time
        ])->exists();
        if ($previousSlot) {
            return true;
        }
        return false;
    }

    public function getSlotShowType()
    {
        return ($this->type_id == Category::TYPE_CAREGIVER_SERVICE) ? self::BOTH_START_AND_END : self::ONLY_START_TIME;
    }

    public function checkIsSlotBooked()
    {
        switch ($this->type_id) {
            case Category::TYPE_CAREGIVER_SERVICE:
                $slot_booked = $this->checkIsCaregiverSlotBooked();
                break;
            default:
                $slot_booked = $this->checkIsNursingSlotBooked();
        }

        return $slot_booked;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->availability_slot_id;
        // $json['main_id'] = $this->id;
        $json['start_time'] = $this->start_time;
        $json['type_id'] = $this->type_id;
        $json['end_time'] = $this->end_time;
        $json['is_booked'] = $this->checkIsSlotBooked();
        $json['slot_show_type'] = $this->getSlotShowType();
        if ($with_relations) {}
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
            $model->availability_slot_id = 1;
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

            $model->availability_slot_id = isset($item['availability_slot_id']) ? $item['availability_slot_id'] : 1;
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->save();
        }
    }

    public static function getUnAvailableDates($dates, $provider_id, $service_id)
    {
        $dates = [];
        $arr = [];
        if (! empty($dates)) {
            foreach ($dates as $date) {
                if (! empty($date)) {

                    $slot_count = ProviderSlot::findActive()->andWhere([
                        'created_by_id' => $provider_id,
                        'type_id' => $service_id,
                        'date(start_time)' => $date
                    ])
                        ->andWhere([
                        '>=',
                        'start_time',
                        date("Y-m-d H:i:s")
                    ])
                        ->count();

                    $booked_count = SlotBooking::find()->where([
                        'provider_id' => $provider_id,
                        'date(start_time)' => $date,
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
                        ->andWhere([
                        '>=',
                        'start_time',
                        date("Y-m-d H:i:s")
                    ])
                        ->count();
                    if ($slot_count <= $booked_count) {
                        $arr[] = $date;
                    }
                }
            }
        }
        return $arr;
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
