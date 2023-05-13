<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\sms\models\search;

use app\modules\sms\models\History as HistoryModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * History represents the model behind the search form about `app\modules\sms\models\History`.
 */
class History extends HistoryModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'model_id',
                    'gateway_id',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'from',
                    'to',
                    'model_type',
                    'text',
                    'sms_detail',
                    'created_on',
                    'updated_on'
                ],
                'safe'
            ]
        ];
    }

    /**
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
        $query = HistoryModel::find()->alias('i')
        ->joinWith('createdBy as u');;
        
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
            'i.id' => $this->id,
            'i.model_id' => $this->model_id,
            'i.gateway_id' => $this->gateway_id,
            'i.state_id' => $this->state_id,
            'i.type_id' => $this->type_id,
            'i.updated_on' => $this->updated_on
        ]);
        
        $query->andFilterWhere([
            'like',
            'i.from',
            $this->from
        ])
            ->andFilterWhere([
            'like',
            'i.to',
            $this->to
        ])
            ->andFilterWhere([
            'like',
            'i.model_type',
            $this->model_type
        ])
            ->andFilterWhere([
            'like',
            'i.text',
            $this->text
        ])
            ->andFilterWhere([
            'like',
            'i.sms_detail',
            $this->sms_detail
        ])
        ->andFilterWhere([
            'like',
            'i.created_on',
            $this->created_on
        ])
        ->andFilterWhere([
            'like',
            'u.full_name',
            $this->created_by_id
        ]);
        
        return $dataProvider;
    }
}
