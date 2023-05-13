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
namespace app\modules\seo\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\seo\models\Log as LogModel;

/**
 * Log represents the model behind the search form about `app\modules\seo\models\Log`.
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
                    'type_id',
                    'user_id',
                    'view_count'
                ],
                'integer'
            ],
            [
                [
                    'referer_link',
                    'message',
                    'current_url',
                    'user_ip',
                    'user_agent',
                    'created_on',
                    'user_id',
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
        $query = LogModel::find()->alias('l')->joinWith('createdBy as c');

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
            'l.type_id' => $this->type_id,
            'l.user_id' => $this->user_id,
            'l.view_count' => $this->view_count
        ]);

        $query->andFilterWhere([
            'like',
            'l.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'l.referer_link',
            $this->referer_link
        ])
            ->andFilterWhere([
            'like',
            'l.message',
            $this->message
        ])
            ->andFilterWhere([
            'like',
            'l.current_url',
            $this->current_url
        ])
            ->andFilterWhere([
            'like',
            'l.user_ip',
            $this->user_ip
        ])
            ->andFilterWhere([
            'like',
            'l.user_agent',
            $this->user_agent
        ])
            ->andFilterWhere([
            'like',
            'l.created_on',
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
