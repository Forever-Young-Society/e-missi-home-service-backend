<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\comment\models\search;

use app\modules\comment\models\Comment as CommentModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Comment represents the model behind the search form about `use app\modules\comment\models\Comment`.
 */
class Comment extends CommentModel
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
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'model_type',
                    'comment',
                    'created_on',
                   
                ],
                'safe'
            ],
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
        $query = CommentModel::find();
        
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
            'id' => $this->id,
            'model_id' => $this->model_id,
            'state_id' => $this->state_id,
            'type_id' => $this->type_id,
            'created_on' => $this->created_on,
            'created_by_id' => $this->created_by_id
        ]);
        
        $query->andFilterWhere([
            'like',
            'model_type',
            $this->model_type
        ])
            ->andFilterWhere([
            'like',
            'comment',
            $this->comment
        ])
            ->andFilterWhere([
            'like',
            'createdBy',
            $this->createdBy
        ]);
        
        return $dataProvider;
    }
}
