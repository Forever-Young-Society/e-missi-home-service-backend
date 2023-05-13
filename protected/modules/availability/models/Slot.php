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

use Yii;
use app\models\Feed;
use app\models\User;
use yii\helpers\ArrayHelper;
use app\modules\service\models\Category;

/**
 * This is the model class for table "tbl_availability_slot".
 *
 * @property integer $id
 * @property string $start_time
 * @property string $end_time
 * @property integer $slot_gap_time
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 * @property DoctorSlot[] $doctorSlots
 */
class Slot extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->start_time;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    const SLOT_START_TIME = '08:00:00';

    const SLOT_END_TIME = '22:00:00';

    const CAREGIVER_FIRST_SLOT_START = '08:00:00';

    const CAREGIVER_FIRST_SLOT_END = '13:00:00';

    const CAREGIVER_SECOND_SLOT_START = '13:00:00';

    const CAREGIVER_SECOND_SLOT_END = '18:00:00';

    const CAREGIVER_THIRD_SLOT_START = '08:00:00';

    const CAREGIVER_THIRD_SLOT_END = '22:00:00';

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

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%availability_slot}}';
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
                    'end_time'
                ],
                'required'
            ],
            [
                [
                    'start_time',
                    'end_time',
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'slot_gap_time',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
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
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'slot_gap_time' => Yii::t('app', 'Slot Gap Time'),
            'state_id' => Yii::t('app', 'State'),
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
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorSlots()
    {
        return $this->hasMany(ProviderSlot::className(), [
            'availability_slot_id' => 'id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];

        $relations['DoctorSlots'] = [
            'doctorSlots',
            'DoctorSlot',
            'id',
            'availability_slot_id'
        ];
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

    public static function addSlots()
    {
        $i = 0;
        while ($i <= 2) {
            if (! Slot::find()->where([
                'type_id' => $i
            ])->exists()) {

                $time = '60 minute';
                if ($i == Category::TYPE_PHYSIOTHERAPIST_SERVICE) {
                    $time = '90 minute';
                }
                $start = new \DateTime(Slot::SLOT_START_TIME);
                $end = new \DateTime(Slot::SLOT_END_TIME);

                while ($start->getTimestamp() < $end->getTimestamp()) {
                    $start_time = $start->format('H:i:s');
                    $end_time = date('H:i:s', strtotime($start_time . "+ " . $time));
                    if ($end_time <= Slot::SLOT_END_TIME) {
                        $slotModel = new Slot();
                        $slotModel->start_time = $start_time;
                        $slotModel->end_time = $end_time;
                        $slotModel->type_id = $i;
                        $slotModel->save();
                    }
                    $start->add(\DateInterval::createFromDateString($time));
                }
            }
            $i ++;
        }

        self::addCaregiverSlots();
    }

    public static function addCaregiverSlots()
    {
        if (! Slot::find()->where([
            'type_id' => Category::TYPE_CAREGIVER_SERVICE
        ])->exists()) {
            $i = 1;
            while ($i <= 3) {
                switch ($i) {
                    case Slot::STATE_ACTIVE:
                        $start = self::CAREGIVER_FIRST_SLOT_START;
                        $end = self::CAREGIVER_FIRST_SLOT_END;
                        break;
                    case Slot::STATE_DELETED:
                        $start = self::CAREGIVER_SECOND_SLOT_START;
                        $end = self::CAREGIVER_SECOND_SLOT_END;
                        break;
                    default:
                        $start = self::CAREGIVER_THIRD_SLOT_START;
                        $end = self::CAREGIVER_THIRD_SLOT_END;
                }
                $slotModel = new Slot();
                $slotModel->start_time = $start;
                $slotModel->end_time = $end;
                $slotModel->type_id = Category::TYPE_CAREGIVER_SERVICE;
                $slotModel->save();
                $i ++;
            }
        }
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        // TODO : start here
        ProviderSlot::deleteRelatedAll([
            'availability_slot_id' => $this->id
        ]);

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

    /**
     * This function is used to check whether the particular slot is selected by by docotor
     *
     * @return boolean
     */
    public function checkIsSlotSelected()
    {
        $get = \Yii::$app->request->get();

        return ProviderSlot::find()->my()
            ->andWhere([
            'availability_slot_id' => $this->id,
            'type_id' => $this->type_id
        ])
            ->andWhere([
            'between',
            'start_time',
            $get['start_time'],
            $get['end_time']
        ])
            ->exists();
    }

    public function asJson()
    {
        $json = [];
        $json['id'] = $this->id;
        $json['start_time'] = $this->start_time;
        $json['end_time'] = $this->end_time;
        // if (! empty($start_time) && ! empty($end_time)) {
        // $json['is_selected'] = $this->checkIsSlotSelected($start_time, $end_time);
        // } else {
        $json['is_selected'] = $this->checkIsSlotSelected();
        // }
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
            $model->start_time = \date('H:i:s');
            $model->end_time = \date('H:i:s');
            $model->slot_gap_time = $faker->text(10);
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

            $model->start_time = isset($item['start_time']) ? $item['start_time'] : \date('H:i:s');

            $model->end_time = isset($item['end_time']) ? $item['end_time'] : \date('H:i:s');

            $model->slot_gap_time = isset($item['slot_gap_time']) ? $item['slot_gap_time'] : $faker->text(10);
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
