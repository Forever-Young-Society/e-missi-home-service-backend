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
namespace app\modules\logger\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\logger\models\Log as LogModel;

/**
 * Log represents the model behind the search form about `app\modules\logger\models\Log`.
 */
class Log extends LogModel
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
                    'error',
                    'description',
                    'link',
                    'referer_link',
                    'user_ip',
                    'created_on',
                    'user_id'
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
        $query = LogModel::find()->alias('l')->joinWith('user as u');

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
            'l.state_id' => $this->state_id,
            'l.type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            'l.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'l.error',
            $this->error
        ])
            ->andFilterWhere([
            'like',
            'l.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'l.link',
            $this->link
        ])
            ->andFilterWhere([
            'like',
            'l.referer_link',
            $this->referer_link
        ])
            ->andFilterWhere([
            'like',
            'l.user_ip',
            $this->user_ip
        ])
            ->andFilterWhere([
            'like',
            'u.full_name',
            $this->user_id
        ])
            ->andFilterWhere([
            'like',
            'l.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
