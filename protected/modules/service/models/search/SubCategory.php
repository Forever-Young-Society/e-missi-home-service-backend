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

use app\modules\service\models\SubCategory as SubCategoryModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SubCategory represents the model behind the search form about `app\modules\service\models\SubCategory`.
 */
class SubCategory extends SubCategoryModel
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
                    'type_id',
                    'state_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'image_file',
                    'created_on',
                    'category_id',
                    'price',
                    'provider_price'
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
        $query = SubCategoryModel::find()->alias('s')->joinWith('categoryDetail as c');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ]
        ]);

        if (! ($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            's.type_id' => $this->type_id,
            's.state_id' => $this->state_id,
            's.created_by_id' => $this->created_by_id,
            'c.id' => $this->category_id
        ]);

        $query->andFilterWhere([
            'like',
            's.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            's.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            's.price',
            $this->price
        ])
            ->andFilterWhere([
            'like',
            's.provider_price',
            $this->provider_price
        ])
            ->
        // ->andFilterWhere([
        // 'like',
        // 'c.title',
        // $this->category_id
        // ])
        andFilterWhere([
            'like',
            's.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
