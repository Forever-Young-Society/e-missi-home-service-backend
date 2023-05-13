<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\contact\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\contact\models\Information as InformationModel;

/**
 * Information represents the model behind the search form about `app\modules\contact\models\Information`.
 */
class Information extends InformationModel
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

                    'state_id',
                    'created_by_id',
                    'budget_type_id'
                ],
                'integer'
            ],
            [
                [
                    'id',
                    'full_name',
                    'email',
                    'subject',
                    'description',
                    'address',
                    'mobile',
                    'landline',
                    'skype_id',
                    'website',
                    'type_id',
                    'created_on',
                    'referrer_url',
                    'user_agent',
                    'ip_address',
                    'country_code'
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
        $query = InformationModel::find();

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
            'state_id' => $this->state_id,
            'created_by_id' => $this->created_by_id,
            'budget_type_id' => $this->budget_type_id
        ]);

        $query->andFilterWhere([
            'like',
            'full_name',
            $this->full_name
        ])
            ->andFilterWhere([
            'like',
            'email',
            $this->email
        ])
            ->andFilterWhere([
            'like',
            'subject',
            $this->subject
        ])
            ->andFilterWhere([
            'like',
            'description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'address',
            $this->address
        ])
            ->andFilterWhere([
            'like',
            'mobile',
            $this->mobile
        ])
            ->andFilterWhere([
            'like',
            'landline',
            $this->landline
        ])
            ->andFilterWhere([
            'like',
            'skype_id',
            $this->skype_id
        ])
            ->andFilterWhere([
            'like',
            'website',
            $this->website
        ])
            ->andFilterWhere([
                'like',
                'ip_address',
                $this->ip_address
            ])
            ->andFilterWhere([
            'like',
            'type_id',
            $this->type_id
        ])
            ->andFilterWhere([
            'like',
            'created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'referrer_url',
            $this->referrer_url
        ])
            ->andFilterWhere([
            'like',
            'user_agent',
            $this->user_agent
        ]);

        return $dataProvider;
    }
}
