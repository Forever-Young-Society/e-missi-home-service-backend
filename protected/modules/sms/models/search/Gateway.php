<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\sms\models\search;

use app\modules\sms\models\Gateway as GatewayModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Gateway represents the model behind the search form about `app\modules\sms\models\Gateway`.
 */
class Gateway extends GatewayModel
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
                    'mode',
                    'state_id',
                    'type_id'
                ],
                'integer'
            ],
            [
                [
                    'created_by_id'
                ],
                'string'
            ],
            [
                [
                    'title',
                    'value',
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
        $query = GatewayModel::find()->alias('i')
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
            'i.mode' => $this->mode,
            'i.state_id' => $this->state_id,
            'i.type_id' => $this->type_id,
//             'i.created_on' => $this->created_on,
            'i.updated_on' => $this->updated_on,
//             'created_by_id' => $this->created_by_id
        ]);
        
        $query->andFilterWhere([
            'like',
            'i.title',
            $this->title
        ])->andFilterWhere([
            'like',
            'i.value',
            $this->value
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
