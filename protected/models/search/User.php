<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models\search;

use app\models\User as UserModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * User represents the model behind the search form about `app\models\User`.
 */
class User extends UserModel
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
                    'gender',
                    'tos',
                    'role_id',
                    'state_id',
                    'type_id',
                    'login_error_count',
                    'otp_verified'
                ],
                'integer'
            ],
            [
                [
                    'full_name',
                    'email',
                    'password',
                    'date_of_birth',
                    'about_me',
                    'contact_no',
                    'address',
                    'latitude',
                    'longitude',
                    'city',
                    'country',
                    'zipcode',
                    'language',
                    'profile_file',
                    'last_visit_time',
                    'last_action_time',
                    'last_password_change',
                    'activation_key',
                    'timezone',
                    'created_on',
                    'updated_on',
                    'identity_number',
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
    public function search($params, $role = null, $both = null)
    {
        $query = UserModel::find()->alias('u')->joinWith('createdBy as d');
        if (! empty($both)) {
            $query->andWhere([
                '!=',
                'u.role_id',
                User::ROLE_ADMIN
            ])->andWhere([
                'u.type_id' => User::TYPE_USER
            ]);
        } else {
            if (! empty($role)) {
                $query->andWhere([
                    'u.role_id' => $role,
                    'u.type_id' => User::TYPE_USER
                ]);
            } else {
                $query->andWhere([
                    '!=',
                    'u.role_id',
                    User::ROLE_ADMIN
                ])->andWhere([
                    'u.type_id' => User::TYPE_DEPENDENT
                ]);
            }
        }

        $from_date = $this->getDateRangeValue('from_date');
        $to_date = $this->getDateRangeValue('to_date');
        if (! empty($from_date) && ! empty($to_date)) {
            $query->andWhere([
                'and',
                [
                    '>=',
                    "date(u.created_on)",
                    $from_date
                ],
                [
                    '<=',
                    "date(u.created_on)",
                    $to_date
                ]
            ]);
        }

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
            'u.id' => $this->id,
            'u.date_of_birth' => $this->date_of_birth,
            'u.gender' => $this->gender,
            'u.tos' => $this->tos,
            'u.role_id' => $this->role_id,
            'u.state_id' => $this->state_id,
            'u.type_id' => $this->type_id,
            'u.otp_verified' => $this->otp_verified
        ]);

        $query->andFilterWhere([
            'like',
            'u.full_name',
            $this->full_name
        ])
            ->andFilterWhere([
            'like',
            'u.email',
            $this->email
        ])
            ->andFilterWhere([
            'like',
            'd.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'u.identity_number',
            $this->identity_number
        ])
            ->andFilterWhere([
            'like',
            'u.about_me',
            $this->about_me
        ])
            ->andFilterWhere([
            'like',
            'u.contact_no',
            $this->contact_no
        ])
            ->andFilterWhere([
            'like',
            'u.address',
            $this->address
        ])
            ->andFilterWhere([
            'like',
            'u.city',
            $this->city
        ])
            ->andFilterWhere([
            'like',
            'u.country',
            $this->country
        ])
            ->andFilterWhere([
            'like',
            'u.zipcode',
            $this->zipcode
        ])
            ->andFilterWhere([
            'like',
            'u.language',
            $this->language
        ])
            ->andFilterWhere([
            'like',
            'u.created_on',
            $this->created_on
        ])
            ->andFilterWhere([
            'like',
            'u.timezone',
            $this->timezone
        ]);

        return $dataProvider;
    }
}
