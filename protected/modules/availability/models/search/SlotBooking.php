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
namespace app\modules\availability\models\search;

use app\modules\availability\models\SlotBooking as SlotBookingModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SlotBooking represents the model behind the search form about `app\modules\availability\models\SlotBooking`.
 */
class SlotBooking extends SlotBookingModel
{

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'dependant_id',
                    'provider_reschedule',
                    'user_reschedule',
                    'is_reschedule_confirm',
                    'state_id',
                    'type_id',
                    'payment_status',
                    'service_type'
                ],
                'integer'
            ],
            [
                [
                    'start_time',
                    'end_time',
                    'slot_id',
                    'description',
                    'old_start_time',
                    'old_end_time',
                    'created_on',
                    'created_by_id',
                    'provider_id',
                    'service_id',
                    'admin_revenue',
                    'provider_amount'
                ],
                'safe'
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function beforeValidate()
    {
        return true;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SlotBookingModel::find()->alias('s')
            ->joinWith([
            'createdBy as cb',
            'providerDetail as p',
            'skillDetail as sr',
            'serviceDetail as sd'
        ])
            ->andWhere([
            '!=',
            's.payment_status',
            SlotBookingModel::PAYMENT_PENDING
        ]);

        $from_date = $this->getDateRangeValue('from_date');
        $to_date = $this->getDateRangeValue('to_date');
        if (! empty($from_date) && ! empty($to_date)) {
            $query->andWhere([
                'and',
                [
                    '>=',
                    "date(s.start_time)",
                    $from_date
                ],
                [
                    '<=',
                    "date(s.start_time)",
                    $to_date
                ]
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->distinct(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        if (! ($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            's.dependant_id' => $this->dependant_id,
            's.provider_reschedule' => $this->provider_reschedule,
            's.user_reschedule' => $this->user_reschedule,
            's.is_reschedule_confirm' => $this->is_reschedule_confirm,
            's.state_id' => $this->state_id,
            's.type_id' => $this->type_id,
            's.payment_status' => $this->payment_status,
            's.admin_revenue' => $this->admin_revenue,
            'sd.id' => $this->service_type
        ]);

        $query->andFilterWhere([
            'like',
            's.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            's.start_time',
            $this->start_time
        ])
            ->andFilterWhere([
            'like',
            'sr.title',
            $this->service_id
        ])
            ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'p.full_name',
            $this->provider_id
        ])
            ->andFilterWhere([
            'like',
            's.end_time',
            $this->end_time
        ])
            ->andFilterWhere([
            'like',
            's.slot_id',
            $this->slot_id
        ])
            ->andFilterWhere([
            'like',
            's.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            's.old_start_time',
            $this->old_start_time
        ])
            ->andFilterWhere([
            'like',
            's.old_end_time',
            $this->old_end_time
        ])
            ->andFilterWhere([
            'like',
            's.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
