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

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\availability\models\ProviderSlot as ProviderSlotModel;

/**
 * ProviderSlot represents the model behind the search form about `app\modules\availability\models\ProviderSlot`.
 */
class ProviderSlot extends ProviderSlotModel
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
                    'availability_slot_id',
                    'state_id',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'start_time',
                    'end_time',
                    'created_on',
                    'created_by_id'
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
        $query = ProviderSlotModel::find()->alias('p')->joinWith('createdBy as cb');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        
        $from_date = $this->getDateRangeValue('from_date');
        $to_date = $this->getDateRangeValue('to_date');
        if (! empty($from_date) && ! empty($to_date)) {
            $query->andWhere([
                'and',
                [
                    '>=',
                    "date(p.start_time)",
                    $from_date
                ],
                [
                    '<=',
                    "date(p.start_time)",
                    $to_date
                ]
            ]);
        }

        if (! ($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'p.availability_slot_id' => $this->availability_slot_id,
            'p.state_id' => $this->state_id,
            'p.type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            'p.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'p.start_time',
            $this->start_time
        ])
            ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'p.end_time',
            $this->end_time
        ])
            ->andFilterWhere([
            'like',
            'p.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
