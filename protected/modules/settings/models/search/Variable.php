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
namespace app\modules\settings\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\settings\models\Variable as VariableModel;

/**
 * Variable represents the model behind the search form about `app\modules\settings\models\Variable`.
 */
class Variable extends VariableModel
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
                    'state_id'
                ],
                'integer'
            ],
            [
                [
                    'key',
                    'module',
                    'value',
                    'type_id',
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
        $query = VariableModel::find()->alias('v')->joinWith('createdBy as u');
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
            'v.state_id' => $this->state_id
        ]);

        $query->andFilterWhere([
            'like',
            'v.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'v.key',
            $this->key
        ])
            ->andFilterWhere([
            'like',
            'v.module',
            $this->module
        ])
            ->andFilterWhere([
            'like',
            'v.value',
            $this->value
        ])
            ->andFilterWhere([
            'like',
            'v.type_id',
            $this->type_id
        ])
            ->andFilterWhere([
            'like',
            'u.full_name',
            $this->created_by_id
        ]);

        return $dataProvider;
    }
}
