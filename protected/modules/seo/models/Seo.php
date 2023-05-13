<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\modules\seo\models;

use Yii;
use yii\helpers\Inflector;
use app\models\User;

/**
 * This is the model class for table "tbl_seo".
 *
 * @property integer $id
 * @property string $route
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $data
 * @property string $state_id
 * @property string $created_on
 * @property string $updated_on
 *
 */
class Seo extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->title;
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

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
            if (! isset($this->updated_on))
                $this->updated_on = date('Y-m-d H:i:s');
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
        return '{{%seo}}';
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
                    'created_on',
                    'route'
                    // 'title',
                    // 'keywords',
                    // 'description',
                    // 'data'
                ],
                'required'
            ],
            [
                [
                    'state_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'updated_on',
                    'relative_url'
                ],
                'safe'
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
                    'route',
                    'title',
                    'keywords',
                    'description',
                    'data'
                ],
                'string',
                'max' => 255
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
            'route' => Yii::t('app', 'Route'),
            'relative_url' => Yii::t('app', 'Relative Url'),
            'title' => Yii::t('app', 'Title'),
            'keywords' => Yii::t('app', 'Keywords'),
            'description' => Yii::t('app', 'Description'),
            'data' => Yii::t('app', 'Data'),
            'state_id' => Yii::t('app', 'State'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On')
        ];
    }

    public static function getHasManyRelations()
    {
        $relations = [];
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
        return true;
    }

    public function getControllerID()
    {
        return '/seo/manager';
    }

    public function isAllowed()
    {
        if (User::isAdmin() || User::isManager()) {
            return true;
        }
        return false;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['route'] = $this->route;
        $json['title'] = $this->title;
        $json['keywords'] = $this->keywords;
        $json['description'] = $this->description;
        $json['data'] = $this->data;
        $json['state_id'] = $this->state_id;
        $json['created_on'] = $this->created_on;
        if ($with_relations) {}
        return $json;
    }

    public static function findByRoute($route, $url = null)
    {
        $model = null;
        try {

            if ($url != null) {
                $model = self::findActive()->andWhere([
                    'relative_url' => $url
                ])->one();
            }

            if ($model == null) {
                $model = self::findActive()->andWhere([
                    'like',
                    'route',
                    $route
                ])->one();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $model;
    }

    public static function getMeta($model, $controller, $action, $url = null)
    {
        if ($url != null) {
            $seo = self::findActive()->andWhere([
                'relative_url' => $url
            ])->one();
            if ($seo != null) {
                return $seo;
            }
        }

        if ($model != null && isset($model->id)) {
            $seo = self::findByRoute($controller . '/' . $action . '/' . $model->id);
        } else {
            $seo = self::findByRoute($controller . '/' . $action);
        }

        return $seo;
    }

    public static function addAnalyticsCode()
    {
        return Analytics::showCode();
    }
}