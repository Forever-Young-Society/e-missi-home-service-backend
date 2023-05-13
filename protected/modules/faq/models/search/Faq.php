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
namespace app\modules\faq\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\faq\models\Faq as FaqModel;

/**
 * Faq represents the model behind the search form about `app\modules\faq\models\Faq`.
 */
class Faq extends FaqModel
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
                    'question',
                    'answer',
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
        $query = FaqModel::find()->alias('faq')->joinWith('createdBy as user');

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
            'faq.state_id' => $this->state_id,
            'faq.type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            'faq.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'faq.question',
            $this->question
        ])
            ->andFilterWhere([
            'like',
            'faq.answer',
            $this->answer
        ])
            ->andFilterWhere([
            'like',
            'faq.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'user.full_name',
            $this->created_by_id
        ]);
        ;

        return $dataProvider;
    }
}
