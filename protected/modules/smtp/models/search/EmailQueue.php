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
namespace app\modules\smtp\models\search;

use app\modules\smtp\models\EmailQueue as EmailQueueModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EmailQueue represents the model behind the search form about `app\modules\smtp\models\EmailQueue`.
 */
class EmailQueue extends EmailQueueModel
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
                    'attempts',
                    'model_id',
                    'smtp_account_id'
                ],
                'integer'
            ],
            [
                [
                    'subject',
                    'from',
                    'to',
                    'cc',
                    'bcc',
                    'content',
                    'sent_on',
                    'created_on',
                    'model_type',
                    'message_id',
                    're_message_id'
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
        $query = EmailQueueModel::find();

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
            'type_id' => $this->type_id,
            'state_id' => $this->state_id,
            'attempts' => $this->attempts,
            'model_id' => $this->model_id,
            'smtp_account_id' => $this->smtp_account_id
        ]);

        $query->andFilterWhere([
            'like',
            'id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'subject',
            $this->subject
        ])
            ->andFilterWhere([
            'like',
            'from',
            $this->from
        ])
            ->andFilterWhere([
            'like',
            'to',
            $this->to
        ])
            ->andFilterWhere([
            'like',
            'cc',
            $this->cc
        ])
            ->andFilterWhere([
            'like',
            'bcc',
            $this->bcc
        ])
            ->andFilterWhere([
            'like',
            'content',
            $this->content
        ])
            ->andFilterWhere([
            'like',
            'sent_on',
            $this->sent_on
        ])
            ->andFilterWhere([
            'like',
            'created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'model_type',
            $this->model_type
        ])
            ->andFilterWhere([
            'like',
            'message_id',
            $this->message_id
        ])
            ->andFilterWhere([
            'like',
            're_message_id',
            $this->re_message_id
        ]);

        return $dataProvider;
    }
}
