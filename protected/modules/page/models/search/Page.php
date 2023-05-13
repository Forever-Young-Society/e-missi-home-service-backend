<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\page\models\search;

use app\modules\page\models\Page as PageModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Page represents the model behind the search form about `app\modules\page\models\Page`.
 */
class Page extends PageModel
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
                    'application_type'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'description',
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
        $query = PageModel::find()->alias('p')->joinWith('createdBy as c');

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
            'p.id' => $this->id,
            'p.state_id' => $this->state_id,
            'p.application_type' => $this->application_type,
            'p.type_id' => $this->type_id
        ]);

        $query->andFilterWhere([
            'like',
            'p.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'p.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'p.created_on',
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
