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

use app\models\User;
use Yii;

/**
 * This is the model class for table "tbl_seo_analytics".
 *
 * @property integer $id
 * @property string $account
 * @property string $domain_name
 * @property string $additional_information
 * @property integer $role_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
class Analytics extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->account;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    const TYPE_GOOGLE = 0;

    const TYPE_HOTJAR = 1;

    const TYPE_CLARITY = 2;

    const TYPE_HOG = 3;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Archived"
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
            self::STATE_INACTIVE => "primary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_GOOGLE => "Google Analytics",
            self::TYPE_HOTJAR => "Hotjar Analytics",
            self::TYPE_CLARITY => "Clarity Analytics",
            self::TYPE_HOG => "Hog Analytics"
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
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
            if (! isset($this->updated_on))
                $this->updated_on = date('Y-m-d H:i:s');
            if (! isset($this->created_by_id))
                $this->created_by_id = self::getCurrentUser();
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
        return '{{%seo_analytics}}';
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
                    'account',
                    'domain_name',
                    'created_on',
                    'updated_on',
                    'state_id',
                    'type_id',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'state_id',
                    'type_id',
                    'role_id',
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
                    'account',
                    'domain_name',
                    'additional_information'
                ],
                'string',
                'max' => 512
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
                    'account',
                    'domain_name',
                    'additional_information'
                ],
                'trim'
            ],
            // [
            // [
            // 'domain_name'
            // ],
            // 'app\components\validators\TNameValidator'
            // ],
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
            'account' => Yii::t('app', 'Account'),
            'domain_name' => Yii::t('app', 'Domain Name'),
            'additional_information' => Yii::t('app', 'Additional Information'),
            'role_id' => Yii::t('app', 'Role'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Analytics Type'),
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
        return $this->hasOne(User::class, [
            'id' => 'created_by_id'
        ])->cache(10);
    }

    public function getRole()
    {
        $list = User::getRoleOptions();
        return isset($list[$this->role_id]) ? $list[$this->role_id] : 'Not Defined';
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

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        return true;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['account'] = $this->account;
        $json['domain_name'] = $this->domain_name;
        $json['additional_information'] = $this->additional_information;
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
                $json['CreatedBy'] = $list;
            }
        }
        return $json;
    }

    public function getControllerID()
    {
        return '/seo/' . parent::getControllerID();
    }

    public static function showCode()
    {
        $models = self::findActive()->all();

        if (empty($models)) {
            return;
        }
        foreach ($models as $data) {

            if (Yii::$app->user->identity && Yii::$app->user->identity->role_id <= $data->role_id) {
                self::log('Skipping analytics due to role:');
                continue;
            }
            switch ($data->type_id) {

                case self::TYPE_GOOGLE:
                    {

                        \Yii::$app->getView()->registerJsFile('https://www.googletagmanager.com/gtag/js?id=' . $data->account);

                        $js = "                
                        window.dataLayer = window.dataLayer || [];
                        function gtag(){dataLayer.push(arguments);}
                        gtag('js', new Date());
                        
                        gtag('config', '{$data->account}');";

                        \Yii::$app->getView()->registerJs($js, \yii\web\View::POS_END, 'analytics-code' . $data->id);
                    }
                    break;
                case self::TYPE_HOTJAR:
                    {

                        $js = "
                       (function(h,o,t,j,a,r){
                        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                        h._hjSettings={hjid:{$data->account},hjsv:6};
                        a=o.getElementsByTagName('head')[0];
                        r=o.createElement('script');r.async=1;
                        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                        a.appendChild(r);
                    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');";

                        \Yii::$app->getView()->registerJs($js, \yii\web\View::POS_END, 'analytics-code' . $data->id);
                    }
                    break;
                case self::TYPE_CLARITY:
                    {

                        $js = "
                (function(c,l,a,r,i,t,y){
                    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                    t=l.createElement(r);t.async=1;t.src='https://www.clarity.ms/tag/'+i;
                    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
                })(window, document, 'clarity', 'script', '{$data->account}');
                ";

                        \Yii::$app->getView()->registerJs($js, \yii\web\View::POS_END, 'analytics-code' . $data->id);
                    }
                    break;
                case self::TYPE_HOG:
                    {

                        $js = '!function(t,e){var o,n,p,r;e.__SV||(window.posthog=e,e._i=[],e.init=function(i,s,a){function g(t,e){var o=e.split(".");2==o.length&&(t=t[o[0]],e=o[1]),t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}}(p=t.createElement("script")).type="text/javascript",p.async=!0,p.src=s.api_host+"/static/array.js",(r=t.getElementsByTagName("script")[0]).parentNode.insertBefore(p,r);var u=e;for(void 0!==a?u=e[a]=[]:a="posthog",u.people=u.people||[],u.toString=function(t){var e="posthog";return"posthog"!==a&&(e+="."+a),t||(e+=" (stub)"),e},u.people.toString=function(){return u.toString(1)+".people (stub)"},o="capture identify alias people.set people.set_once set_config register register_once unregister opt_out_capturing has_opted_out_capturing opt_in_capturing reset isFeatureEnabled onFeatureFlags".split(" "),n=0;n<o.length;n++)g(u,o[n]);e._i.push([i,s,a])},e.__SV=1)}(document,window.posthog||[]);
                    posthog.init("' . $data->account . '",{api_host:"https://hog.jiweb.in"})';

                        \Yii::$app->getView()->registerJs($js, \yii\web\View::POS_HEAD, 'analytics-code' . $data->id);
                    }
                    break;
            }
        }
    }
}
