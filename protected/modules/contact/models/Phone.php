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
namespace app\modules\contact\models;

use app\models\User;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "tbl_contact_phone".
 *
 * @property integer $id
 * @property string $title
 * @property string $contact_no
 * @property string $type_chat
 * @property string $skype_chat
 * @property string $gtalk_chat
 * @property integer $type_id
 * @property integer $state_id
 * @property integer $whatsapp_enable
 * @property integer $telegram_enable
 * @property integer $toll_free_enable
 * @property string $country
 * @property string $created_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class Phone extends \app\components\TActiveRecord
{

    const CONTACT_TYPE_SALES = 0;

    const CONTACT_TYPE_HR = 1;

    public function __toString()
    {
        return (string) $this->title;
    }

    public static function getTypeOptions($id = null)
    {
        $list = array(
            self::CONTACT_TYPE_SALES => "Sales",
            self::CONTACT_TYPE_HR => "HR"
        );
        if ($id === null)
            return $list;
        return isset($list[$id]) ? $list[$id] : 'Not Defined';
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Archived"
        ];
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Archive"
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

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->created_on))
                $this->created_on = date('Y-m-d H:i:s');
            if (! isset($this->created_by_id))
                $this->created_by_id = self::getCurrentUser();
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contact_phone}}';
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
                    'title',
                    'contact_no',
                    'state_id',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'type_id',
                    'state_id',
                    'whatsapp_enable',
                    'telegram_enable',
                    'toll_free_enable',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'contact_no'
                ],
                'string',
                'min' => 7,
                'max' => 16
            ],
            [
                [
                    'title',
                    'contact_no',
                    'type_chat',
                    'skype_chat',
                    'gtalk_chat',
                    'country'
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
                'targetClass' => User::className(),
                'targetAttribute' => [
                    'created_by_id' => 'id'
                ]
            ],
            [
                [
                    'title',
                    'contact_no',
                    'type_chat',
                    'skype_chat',
                    'gtalk_chat',
                    'country'
                ],
                'trim'
            ],
            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
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
            'title' => Yii::t('app', 'Title'),
            'contact_no' => Yii::t('app', 'Contact No'),
            'type_chat' => Yii::t('app', 'Type Chat'),
            'skype_chat' => Yii::t('app', 'Skype Chat'),
            'gtalk_chat' => Yii::t('app', 'Gtalk Chat'),
            'type_id' => Yii::t('app', 'Type'),
            'state_id' => Yii::t('app', 'State'),
            'whatsapp_enable' => Yii::t('app', 'Whatsapp'),
            'telegram_enable' => Yii::t('app', 'Telegram'),
            'toll_free_enable' => Yii::t('app', 'Tollfree'),
            'state_id' => Yii::t('app', 'State'),
            'country' => Yii::t('app', 'Country'),
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
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ])->cache();
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

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        $json['contact_no'] = $this->contact_no;
        $json['type_chat'] = $this->type_chat;
        $json['skype_chat'] = $this->skype_chat;
        $json['gtalk_chat'] = $this->gtalk_chat;
        $json['type_id'] = $this->type_id;
        $json['state_id'] = $this->state_id;
        $json['country'] = $this->country;
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
        return '/contact/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->title = $faker->text(10);
            $model->contact_no = $faker->text(10);
            $model->type_chat = 0;
            $model->skype_chat = $faker->text(10);
            $model->gtalk_chat = $faker->text(10);
            $model->type_id = 0;
            $model->state_id = $states[rand(0, count($states))];
            $model->country = $faker->text(10);
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

            $model->title = isset($item['title']) ? $item['title'] : $faker->text(10);

            $model->contact_no = isset($item['contact_no']) ? $item['contact_no'] : $faker->text(10);

            $model->type_chat = isset($item['type_chat']) ? $item['type_chat'] : 0;

            $model->skype_chat = isset($item['skype_chat']) ? $item['skype_chat'] : $faker->text(10);

            $model->gtalk_chat = isset($item['gtalk_chat']) ? $item['gtalk_chat'] : $faker->text(10);

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->state_id = self::STATE_ACTIVE;

            $model->country = isset($item['country']) ? $item['country'] : $faker->text(10);
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

    public function getWhatsappNumber()
    {
        return preg_replace('/[^0-9]/', '', $this->contact_no);
        // filter_var($this->contact_no, FILTER_SANITIZE_NUMBER_INT);
    }

    public function getWhatsappLink()
    {
        $regex = preg_replace('/[^0-9]/', '', $this->contact_no);
        return Url::to('https://wa.me/' . $regex);
    }

    public function getTelegramLink()
    {
        $regex = preg_replace('/[^0-9]/', '', $this->contact_no);
        return Url::to('https://t.me/' . $regex);
    }

    public function getContactLink()
    {
        $regex = preg_replace('/[^0-9+]/', '', $this->contact_no);
        return Html::beginTag('span') . Html::a($this->contact_no, 'tel:' . $regex) . Html::endTag('span');
    }

    public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }

    public static function getAllContacts()
    {
        return Phone::findActive()->orderBy('id asc');
    }
}
