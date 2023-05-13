<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\seo\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\seo\models\Analytics as AnalyticsModel;

/**
 * Analytics represents the model behind the search form about `app\modules\seo\models\Analytics`.
 */
class Analytics extends AnalyticsModel
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
                    'state_id',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'account',
                    'domain_name',
                    'additional_information',
                    'created_on',
                    'updated_on',
                    'created_by_id'
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
        $query = AnalyticsModel::find()->alias('i')
        ->joinWith('createdBy as u');
        
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
            'i.state_id' => $this->state_id,
            'i.type_id' => $this->type_id,
            'i.updated_on' => $this->updated_on
        ]);
        
        $query->andFilterWhere([
            'like',
            'i.account',
            $this->account
        ])
            ->andFilterWhere([
            'like',
            'i.domain_name',
            $this->domain_name
        ])
            ->andFilterWhere([
            'like',
            'i.created_on',
                $this->created_on
        ])
        ->andFilterWhere([
            'like',
            'i.additional_information',
            $this->additional_information
        ])
        ->andFilterWhere([
            'like',
            'u.full_name',
            $this->created_by_id
        ]);
        
        return $dataProvider;
    }
}
