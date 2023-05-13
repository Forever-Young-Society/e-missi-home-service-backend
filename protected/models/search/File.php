<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use app\models\File as FileModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * File represents the model behind the search form about `app\models\File`.
 */
class File extends FileModel
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
                    'size',
                    'model_id'
                ],
                'integer'
            ],
            [
                [
                    'name',
                    'key',
                    'model_type',
                    'type_id',
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
	public function search($params) {
		$query = FileModel::find ()->alias('f')->joinWith('createdBy as u');

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
				'f.id' => $this->id,
				'f.size' => $this->size,
				'f.model_id' => $this->model_id,
		        'f.created_on' => $this->created_on,
        ]);

        $query->andFilterWhere([
            'like',
            'f.name',
            $this->name
        ])
            ->andFilterWhere([
            'like',
            'f.key',
            $this->key
        ])
            ->andFilterWhere([
            'like',
            'f.model_type',
            $this->model_type
        ])
            ->andFilterWhere([
            'like',
            'f.type_id',
            $this->type_id
        ])
            ->andFilterWhere([
            'like',
		    'u.full_name',
            $this->created_by_id
        ]);

        return $dataProvider;
    }
}
