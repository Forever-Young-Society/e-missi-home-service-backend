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
namespace app\modules\contact\models\search;

use app\modules\contact\models\Address as AddressModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Address represents the model behind the search form about `app\modules\contact\models\Address`.
 */
class Address extends AddressModel
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
                    'created_by_id',
                    'image_file'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'address',
                    'email',
                    'tel',
                    'mobile',
                    'latitude',
                    'longitude',
                    'country',
                    'type_id',
                    'created_on',
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
        $query = AddressModel::find();
        
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
            'state_id' => $this->state_id,
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
            'address',
            $this->address
        ])
            ->andFilterWhere([
            'like',
            'email',
            $this->email
        ])
            ->andFilterWhere([
            'like',
            'tel',
            $this->tel
        ])
            ->andFilterWhere([
            'like',
            'mobile',
            $this->mobile
        ])
            ->andFilterWhere([
            'like',
            'latitude',
            $this->latitude
        ])
            ->andFilterWhere([
            'like',
            'longitude',
            $this->longitude
        ])
            ->andFilterWhere([
            'like',
            'country',
            $this->country
        ])
            ->andFilterWhere([
            'like',
            'image_file',
            $this->image_file
        ])
            ->andFilterWhere([
            'like',
            'type_id',
            $this->type_id
        ])
            ->andFilterWhere([
            'like',
            'created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'updated_on',
            $this->updated_on
        ]);

        return $dataProvider;
    }
}
