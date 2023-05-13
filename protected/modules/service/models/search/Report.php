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
namespace app\modules\service\models\search;

use app\modules\service\models\Report as ReportModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Report represents the model behind the search form about `app\modules\service\models\Report`.
 */
class Report extends ReportModel
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
                    'title',
                    'zipcode',
                    'city',
                    'state',
                    'address',
                    'service_provided',
                    'description',
                    'created_on'
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
        $query = ReportModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'age' => $this->age,
            'booking_id' => $this->booking_id,
            'service_id' => $this->service_id,
            'user_id' => $this->user_id,
            'dependant_id' => $this->dependant_id,
            'state_id' => $this->state_id,
            'type_id' => $this->type_id,
            'created_by_id' => $this->created_by_id
        ]);

        $query->andFilterWhere([
            'like',
            'id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'zipcode',
            $this->zipcode
        ])
            ->andFilterWhere([
            'like',
            'city',
            $this->city
        ])
            ->andFilterWhere([
            'like',
            'state',
            $this->state
        ])
            ->andFilterWhere([
            'like',
            'address',
            $this->address
        ])
            ->andFilterWhere([
            'like',
            'service_provided',
            $this->service_provided
        ])
            ->andFilterWhere([
            'like',
            'description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
