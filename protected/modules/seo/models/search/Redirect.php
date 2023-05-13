<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\seo\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\seo\models\Redirect as RedirectModel;

/**
 * Redirect represents the model behind the search form about `app\modules\seo\models\Redirect`.
 */
class Redirect extends RedirectModel
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
                    'old_url',
                    'new_url',
                    'created_on',
                    'updated_on',
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
        $query = RedirectModel::find()->alias('i')
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
            'i.updated_on' => $this->updated_on,
        ]);
        
        $query->andFilterWhere([
            'like',
            'i.old_url',
            $this->old_url
        ])->andFilterWhere([
            'like',
            'i.new_url',
            $this->new_url
        ])->andFilterWhere([
            'like',
            'u.full_name',
            $this->created_by_id
        ])->andFilterWhere([
            'like',
            'i.created_on',
            $this->created_on
        ]);
        
        return $dataProvider;
    }
}
