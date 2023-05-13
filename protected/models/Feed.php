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
namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_feed".
 *
 * @property integer $id
 * @property string $content
 * @property integer $state_id
 * @property integer $type_id
 * @property string $model_type
 * @property string $user_ip
 * @property string $user_agent
 * @property integer $model_id
 * @property string $created_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
class Feed extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->content;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Deleted"
        ];
    }

    public function getState()
    {
        $list = self::getStateOptions();
        return isset($list[$this->state_id]) ? $list[$this->state_id] : 'Not Defined';
    }

    public function getStateBadge()
    {
        $list = [
            self::STATE_INACTIVE => "secondary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_INACTIVE => "Inactive",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Deleted"
        ];
    }

    public static function getTypeOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->created_on)) {
                $this->created_on = date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
            }
        } else {}
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%feed}}';
    }

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'content'
                ],
                'string'
            ],
            [
                [
                    'state_id',
                    'type_id',
                    'model_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'model_type',
                    'model_id'
                ],
                'required'
            ],
            [
                [
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'model_type'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'user_ip',
                    'user_agent'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'created_by_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
            [
                [
                    'model_type'
                ],
                'trim'
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'content' => Yii::t('app', 'Content'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'model_type' => Yii::t('app', 'Model Type'),
            'user_ip' => Yii::t('app', 'User Ip'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'model_id' => Yii::t('app', 'Model'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, [
            'id' => 'created_by_id'
        ])->cache();
    }

    public function getModel()
    {
        $modelType = $this->model_type;
        if (class_exists($modelType)) {
            return $modelType::findOne($this->model_id);
        }
    }

    public static function getHasManyRelations()
    {
        $relations = [];
        return $relations;
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        return $relations;
    }

    protected function processFeed($insert, $changedAttributes)
    {}

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['content'] = $this->content;
        $json['model_type'] = $this->model_type;
        $json['model_id'] = $this->model_id;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // createdBy
            $list = $this->createdBy;

            if (is_array($list)) {
                $relationData = [];
                foreach ($list as $item) {
                    $relationData[] = $item->asJson();
                }
                $json['createdBy'] = $relationData;
            } else {
                $json['createdBy'] = $list;
            }
        }
        return $json;
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();

            $model->content = $faker->text;
            $model->state_id = $states[rand(0, count($states))];
            $model->type_id = 0;
            $model->model_type = $faker->text(10);
            $model->model_id = 1;
            $model->save();
        }
    }

    public static function addData($data)
    {
        $faker = \Faker\Factory::create();
        if (self::find()->count() != 0)
            return;
        foreach ($data as $item) {
            $model = new self();

            $model->content = isset($item['content']) ? $item['content'] : $faker->text;
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;

            $model->model_type = isset($item['model_type']) ? $item['model_type'] : $faker->text(10);

            $model->model_id = isset($item['model_id']) ? $item['model_id'] : 1;
            $model->save();
        }
    }

    public static function add($obj, $content)
    {
        // Dont add feed for console updates.
        if (\Yii::$app instanceof yii\console\Application) {
            return null;
        }
        $model = new self();
        $model->loadDefaultValues();
        $class = get_class($obj);
        $model->model_type = $class;
        $model->model_id = $obj->id;
        if (\Yii::$app instanceof yii\web\Application) {
            $model->user_ip = \Yii::$app->request->getUserIP();
            $model->user_agent = \Yii::$app->request->getUserAgent();
        }
        $model->content = $content;

        $model->state_id = Feed::STATE_ACTIVE;

        return $model->save();
    }

    public static function getRecentFeedsWhere($conditions = [], $page = null)
    {
        $query = Feed::find()->cache();

        if (! User::isAdmin()) {

            $query->where([
                'created_by_id' => \Yii::$app->user->id
            ]);
        }
        if (is_array($conditions)) {

            $query->andWhere($conditions);
        }
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => ($page) ? 5 : 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
    }
}
