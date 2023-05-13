<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\modules\seo\models\search;

use app\modules\seo\models\Seo as SeoModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Seo represents the model behind the search form about `app\models\Seo`.
 */
class SeoSearch extends SeoModel
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
                    'id'
                ],
                'integer'
            ],
            [
                [
                    'route',
                    'title',
                    'keywords',
                    'description',
                    'data',
                    'created_on',
                    'state_id',
                    'updated_on'
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
        $query = SeoModel::find();

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
            'id' => $this->id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'state_id' => $this->state_id
        ]);

        $query->andFilterWhere([
            'like',
            'route',
            $this->route
        ])
            ->andFilterWhere([
            'like',
            'title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'keywords',
            $this->keywords
        ])
            ->andFilterWhere([
            'like',
            'description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'data',
            $this->data
        ]);

        return $dataProvider;
    }
}
