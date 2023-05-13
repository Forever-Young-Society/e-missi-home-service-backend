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
namespace app\modules\sitemap\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "tbl_sitemap_item".
 *
 * @property integer $id
 * @property string $location
 * @property string $module
 * @property integer $priority_id
 * @property integer $change_frequency_id
 * @property string $model_type
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id
 */
class Item extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->location;
    }

    public static function getPriorityOptions()
    {
        return [
            "1.0",
            "0.8",
            "0.5"
        ];
    }

    public function getPriority()
    {
        $list = self::getPriorityOptions();
        return isset($list[$this->priority_id]) ? $list[$this->priority_id] : 'Not Defined';
    }

    const CHANGEFREQ_ALWAYS = 0;

    const CHANGEFREQ_HOURLY = 1;

    const CHANGEFREQ_DAILY = 2;

    const CHANGEFREQ_WEEKLY = 3;

    const CHANGEFREQ_MONTHLY = 4;

    const CHANGEFREQ_YEARLY = 5;

    const CHANGEFREQ_NEVER = 6;

    public static function getChangeFrequencyOptions()
    {
        return [
            "always",
            "hourly",
            "daily",
            "weekly",
            "monthly",
            "yearly",
            "never"
        ];
    }

    public function getChangeFrequency()
    {
        $list = self::getChangeFrequencyOptions();
        return isset($list[$this->change_frequency_id]) ? $list[$this->change_frequency_id] : 'Not Defined';
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
            self::STATE_INACTIVE => "Deactivate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_DELETED => "Delete"
        ];
    }

    public static function getTypeOptions()
    {
        return [
            "URL",
            "IMAGE",
            "VIDEO"
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
                $this->created_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->updated_on)) {
                $this->updated_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
            }
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sitemap_item}}';
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
                    'location',
                    'priority_id',
                    'state_id',
                    'type_id',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'priority_id',
                    'change_frequency_id',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'integer'
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
                    'location'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'module'
                ],
                'string',
                'max' => 64
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
                    'location',
                    'model_type'
                ],
                'trim'
            ],
            [
                [
                    'location'
                ],
                'unique'
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
            'location' => Yii::t('app', 'Location (Absolute Url)'),
            'module' => Yii::t('app', 'Module'),
            'priority_id' => Yii::t('app', 'Priority'),
            'change_frequency_id' => Yii::t('app', 'Change Frequency'),
            'model_type' => Yii::t('app', 'Model Type'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
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

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['location'] = $this->location;
        $json['module'] = $this->module;
        $json['priority_id'] = $this->priority_id;
        $json['change_frequency_id'] = $this->change_frequency_id;
        $json['model_type'] = $this->model_type;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {}
        return $json;
    }

    public function getControllerID()
    {
        return '/sitemap/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->location = $faker->text(10);
            $model->module = $faker->text(10);
            $model->priority_id = 1;
            $model->change_frequency_id = 1;
            $model->model_type = $faker->text(10);
            $model->state_id = $states[rand(0, count($states))];
            $model->type_id = 0;
            $model->save();
        }
    }

    public static function addData($data)
    {
        if (self::find()->count() != 0) {
            return;
        }

        $faker = \Faker\Factory::create();
        foreach ($data as $item) {
            $model = new self();
            $model->loadDefaultValues();

            $model->location = isset($item['location']) ? $item['location'] : $faker->text(10);

            $model->module = isset($item['module']) ? $item['module'] : $faker->text(10);

            $model->priority_id = isset($item['priority_id']) ? $item['priority_id'] : 1;

            $model->change_frequency_id = isset($item['change_frequency_id']) ? $item['change_frequency_id'] : 1;

            $model->model_type = isset($item['model_type']) ? $item['model_type'] : $faker->text(10);
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->save();
        }
    }

    public function isAllowed()
    {
        if (User::isAdmin())
            return true;

        if ($this->hasAttribute('created_by_id') && $this->created_by_id == Yii::$app->user->id) {
            return true;
        }

        return User::isUser();
    }

    public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }

    protected function processFeed($insert, $changedAttributes)
    {
        // not needed
    }

    public static function addUrl($location, $priority_id = 0, $change_frequency_id = 0, $updated_on = null)
    {
        $model = new self();
        $model->loadDefaultValues();
        $model->location = $location;
        $model->priority_id = $priority_id;
        $model->change_frequency_id = $change_frequency_id;
        $model->state_id = self::STATE_ACTIVE;
        $model->type_id = 0;
        $model->module = 'sitemp';
        $model->updated_on = $updated_on;
        $model->save();
    }

    public function test()
    {
        $response = @get_headers($this->location);
        list ($http, $code, $status) = explode(' ', $response[0]);
        self::log($code . ' :Testing done : ' . $this->location);
        if (in_array($code, [
            '500',
            '403',
            '401'
        ])) {

            $this->state_id = self::STATE_INACTIVE;
            $this->save();
        } elseif ($code == '404') {
            // delete the link
            $this->state_id = self::STATE_DELETED;
            $this->save();
        }
    }
}
