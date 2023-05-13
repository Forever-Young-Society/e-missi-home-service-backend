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
use app\modules\contact\models\Chatscript as ChatscriptModel;

/**
 * Chatscript represents the model behind the search form about `app\modules\contact\models\Chatscript`.
 */
class Chatscript extends ChatscriptModel
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
                    'contact_link',
                    'show_bubble',
                    'popup_delay',
                    'state_id',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'domain',
                    'script_code',
                    'chat_server',
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
        $query = ChatscriptModel::find()->alias('c')->joinWith('createdBy as u');

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
            'c.contact_link' => $this->contact_link,
            'c.show_bubble' => $this->show_bubble,
            'c.popup_delay' => $this->popup_delay,
            'c.state_id' => $this->state_id,
            'c.type_id' => $this->type_id
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
            'c.domain',
            $this->domain
        ])
            ->andFilterWhere([
            'like',
            'c.script_code',
            $this->script_code
        ])
            ->andFilterWhere([
            'like',
            'c.chat_server',
            $this->chat_server
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
