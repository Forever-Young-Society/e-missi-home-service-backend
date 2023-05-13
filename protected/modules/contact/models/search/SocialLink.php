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

use app\modules\contact\models\SocialLink as SocialLinkModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SocialLink represents the model behind the search form about `app\modules\contact\models\SocialLink`.
 */
class SocialLink extends SocialLinkModel
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
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'ext_url',
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
        $query = SocialLinkModel::find()->alias('s')->joinWith('createdBy as c');

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
            's.state_id' => $this->state_id,
            's.type_id' => $this->type_id
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
            's.ext_url',
            $this->ext_url
        ])
            ->andFilterWhere([
            'like',
            's.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'c.full_name',
            $this->created_by_id
        ]);

        return $dataProvider;
    }
}
