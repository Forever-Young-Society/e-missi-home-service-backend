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

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\contact\models\Phone as PhoneModel;

/**
 * Phone represents the model behind the search form about `app\modules\contact\models\Phone`.
 */
class Phone extends PhoneModel
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
                    'whatsapp_enable',
                    'telegram_enable',
                    'toll_free_enable'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'contact_no',
                    'type_chat',
                    'skype_chat',
                    'gtalk_chat',
                    'country',
                    'created_by_id',
                    'created_on'
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
        $query = PhoneModel::find()->alias('c')->joinWith('createdBy as u');

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
            'c.type_id' => $this->type_id,
            'c.state_id' => $this->state_id
        ]);

        $query->andFilterWhere([
            'like',
            'c.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'c.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'c.contact_no',
            $this->contact_no
        ])
            ->andFilterWhere([
            'like',
            'c.type_chat',
            $this->type_chat
        ])
            ->andFilterWhere([
            'like',
            'c.skype_chat',
            $this->skype_chat
        ])
            ->andFilterWhere([
            'like',
            'c.gtalk_chat',
            $this->gtalk_chat
        ])
            ->andFilterWhere([
            'like',
            'c.telegram_enable',
            $this->telegram_enable
        ])
            ->andFilterWhere([
            'like',
            'c.whatsapp_enable',
            $this->whatsapp_enable
        ])
            ->andFilterWhere([
            'like',
            'c.toll_free_enable',
            $this->toll_free_enable
        ])
            ->andFilterWhere([
            'like',
            'c.country',
            $this->country
        ])
            ->andFilterWhere([
            'like',
            'u.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'c.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
