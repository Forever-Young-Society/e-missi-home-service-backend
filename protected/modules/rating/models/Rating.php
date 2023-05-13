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

/**
 * This is the model class for table "tbl_rating".
 *
 * @property integer $id
 * @property integer $model_id
 * @property string $model_type
 * @property double $rating
 * @property string $title
 * @property string $comment
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
namespace app\modules\rating\models;

use Yii;
use app\models\Feed;
use app\models\User;
use yii\helpers\ArrayHelper;
use app\modules\availability\models\SlotBooking;

class Rating extends \app\components\TActiveRecord
{

    const SORT_LATEST = 1;

    const SORT_HIGHEST = 2;

    const SORT_LOWEST = 3;

    public function __toString()
    {
        return (string) $this->title;
    }

    public static function getModelOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getModel()
    {
        $list = self::getModelOptions();
        return isset($list[$this->model_id]) ? $list[$this->model_id] : 'Not Defined';
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
            self::STATE_INACTIVE => "default",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'label label-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_INACTIVE => "Deactivate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_DELETED => "Delete"
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
            if (empty($this->updated_on)) {
                $this->updated_on = date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = \Yii::$app->user->id;
            }
        } else {
            $this->updated_on = date('Y-m-d H:i:s');
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rating}}';
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
                    'model_id',
                    'model_type',
                    'rating',
                    'created_on',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'model_id',
                    'state_id',
                    'type_id',
                    'created_by_id',
                    'provider_id'
                ],
                'integer'
            ],
            [
                [
                    'rating'
                ],
                'number'
            ],
            [
                [
                    'comment'
                ],
                'string'
            ],
            [
                [
                    'created_on',
                    'updated_on'
                ],
                'safe'
            ],
            [
                [
                    'model_type',
                    'title'
                ],
                'string',
                'max' => 256
            ],
            [
                [
                    'created_by_id'
                ],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
            [
                [
                    'model_type',
                    'title'
                ],
                'trim'
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ],
            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
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
            'model_id' => Yii::t('app', 'Model'),
            'model_type' => Yii::t('app', 'Model Type'),
            'rating' => Yii::t('app', 'Rating'),
            'title' => Yii::t('app', 'Title'),
            'comment' => Yii::t('app', 'Comment'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ]);
    }

    public function getBooking()
    {
        return $this->hasOne(SlotBooking::class, [
            'id' => 'model_id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];

        $relations['feeds'] = [
            'feeds',
            'Feed',
            'model_id'
        ];
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

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        // TODO : start here
        return true;
    }

    public function beforeSave($insert)
    {
        if (! parent::beforeSave($insert)) {
            return false;
        }
        // TODO : start here

        return true;
    }

    public function asJson()
    {
        $json = [];
        $json['id'] = $this->id;
        $json['model_id'] = $this->model_id;
        $json['rating'] = $this->rating;
        $json['comment'] = $this->comment;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = User::getRelativeTime($this->created_on);
        $json['created_by_id'] = $this->created_by_id;
        $json['service_name'] = ! empty($this->booking) ? $this->booking->asJson()['service_name'] : '';
        $json['skill_name'] = ! empty($this->booking) ? $this->booking->asJson()['skill_name'] : '';
        $json['provider_id'] = $this->provider_id;
        $json['user_name'] = ! empty($this->createdBy) ? $this->createdBy->full_name : '';
        $json['user_gender'] = ! empty($this->createdBy) ? $this->createdBy->gender : '';
        $json['user_image'] = ! empty($this->createdBy) ? $this->createdBy->getImageUrl(90) : '';
        return $json;
    }

    public function getControllerID()
    {
        return '/rating/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();

            $model->model_id = 1;
            $model->model_type = $faker->text(10);
            $model->rating = $faker->text(10);
            $model->title = $faker->text(10);
            $model->comment = $faker->text;
            $model->state_id = $states[rand(0, count($states))];
            $model->type_id = 0;
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

            $model->model_id = isset($item['model_id']) ? $item['model_id'] : 1;

            $model->model_type = isset($item['model_type']) ? $item['model_type'] : $faker->text(10);

            $model->rating = isset($item['rating']) ? $item['rating'] : $faker->text(10);

            $model->title = isset($item['title']) ? $item['title'] : $faker->text(10);

            $model->comment = isset($item['comment']) ? $item['comment'] : $faker->text;
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            if (! $model->save()) {
                self::log($model->getErrorsString());
            }
        }
    }

    public function getStarCount($model, $rating)
    {
        $data = [];
        $data['rate_no'] = $rating[0];
        $data['count'] = self::findActive()->where([
            'model_id' => $model->id,
            'model_type' => get_class($model)
        ])
            ->andWhere([
            'IN',
            'rating',
            $rating
        ])
            ->count();
        return $data;
    }
}
