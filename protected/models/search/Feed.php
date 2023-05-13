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
namespace app\models\search;

use app\models\Feed as FeedModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Feed represents the model behind the search form about `app\models\Feed`.
 */
class Feed extends FeedModel
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
                    'state_id',
                    'type_id',
                    'model_id'
                ],
                'integer'
            ],
            [
                [
                    'content',
                    'user_ip',
                    'user_agent',
                    'model_type',
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
        $query = FeedModel::find()->alias('f')->joinWith('createdBy as cb');

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
            'f.state_id' => $this->state_id,
            'f.type_id' => $this->type_id,
            'f.model_id' => $this->model_id
        ]);

        $query->andFilterWhere([
            'like',
            'f.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'f.content',
            $this->content
        ])
            ->andFilterWhere([
            'like',
            'f.user_ip',
            $this->user_ip
        ])
            ->andFilterWhere([
            'like',
            'f.user_agent',
            $this->user_agent
        ])
            ->andFilterWhere([
            'like',
            'f.model_type',
            $this->model_type
        ])
            ->andFilterWhere([
            'like',
            'f.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ]);

        return $dataProvider;
    }
}
