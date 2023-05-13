<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\contact\models;

use app\components\World;
use app\components\helpers\TStringHelper;
use app\models\EmailQueue;
use app\models\Feed;
use app\models\User;
use app\modules\smtp\models\Unsubscribe;
use borales\extensions\phoneInput\PhoneInputValidator;
use Yii;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\httpclient\Client;

/**
 * This is the model class for table "tbl_contact_information".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $email
 * @property string $subject
 * @property string $description
 * @property string $address
 * @property string $ip_address
 * @property string $country_code
 * @property string $referrer_url
 * @property string $user_agent
 * @property string $mobile
 * @property string $landline
 * @property string $skype_id
 * @property string $website
 * @property integer $budget_type_id
 * @property integer $state_id
 * @property string $type_id
 * @property string $created_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 */
class Information extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return $this->type . ' : ' . $this->full_name;
    }

    const STATE_DRAFT = 0;

    const STATE_SUBMITTED = 1;

    const STATE_PROCESSED = 2;

    const STATE_DELETED = 3;

    const STATE_SPAM = 4;

    const BUDGET_10K = 0;

    const BUDGET_15K = 1;

    const BUDGET_30K = 2;

    const BUDGET_50K = 3;

    const TYPE_CONTACT = 0;

    const TYPE_QUOTE = 1;

    const TYPE_MEETING = 2;

    const CONTACT_SESSION_KEY = 'contact_session_id';

    public static function getStateOptions()
    {
        return [
            self::STATE_DRAFT => "Draft",
            self::STATE_SUBMITTED => "Submitted",
            self::STATE_PROCESSED => "Processed",
            self::STATE_DELETED => "Deleted",
            self::STATE_SPAM => "Spam"
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
            self::STATE_DRAFT => "primary",
            self::STATE_SUBMITTED => "success",
            self::STATE_PROCESSED => "secondary",
            self::STATE_DELETED => "danger",
            self::STATE_SPAM => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_CONTACT => "Job",
            self::TYPE_QUOTE => "Quote",
            self::TYPE_MEETING => "Meeting"
        ];
    }

    public static function getTypeLabelOptions()
    {
        return [
            self::TYPE_QUOTE => "Project Discussion",
            self::TYPE_CONTACT => "Looking for Job",
            self::TYPE_MEETING => "Schedule a Meeting"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public static function getBudgetTypeOptions()
    {
        return [
            self::BUDGET_10K => "$10k-$15k",
            self::BUDGET_15K => "$15k-$30k",
            self::BUDGET_30K => "$30k-$50k",
            self::BUDGET_50K => ">$50k"
        ];
    }

    public function getBudgetType()
    {
        $list = self::getBudgetTypeOptions();
        return isset($list[$this->budget_type_id]) ? $list[$this->budget_type_id] : 'Not Defined';
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

    public function isAllowed()
    {
        if (User::isManager()) {
            return true;
        }
        return false;
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contact_information}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        return $scenarios;
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
                    'full_name',
                    'email',
                    'mobile',
                    'created_on'
                ],
                'required'
            ],

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
                    'created_on',
                    'ip_address',
                    'user_agent',
                    'referrer_url',
                    'subject'
                ],
                'safe'
            ],
            [
                [
                    'full_name',
                    'email',
                    'subject',
                    'address',
                    'landline',
                    'skype_id',
                    'website'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'mobile'
                ],
                'string',
                'min' => 7,
                'max' => 15
            ],
            [
                [
                    'mobile'
                ],
                PhoneInputValidator::class
            ],
            [
                [
                    'description',
                    'country_code'
                ],
                'string'
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
                    'full_name',
                    'subject',
                    'address',
                    'landline',
                    'skype_id',
                    'website',
                    'type_id'
                ],
                'trim'
            ],
            [
                [
                    'full_name'
                ],
                'app\components\validators\TNameValidator'
            ],
            [
                [
                    'email'
                ],
                'email'
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
            ],
            [
                [
                    'budget_type_id'
                ],
                'in',
                'range' => array_keys(self::getBudgetTypeOptions())
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
            'full_name' => Yii::t('app', 'Full Name'),
            'email' => Yii::t('app', 'Email'),
            'subject' => Yii::t('app', 'Session'),
            'description' => Yii::t('app', 'Description'),
            'address' => Yii::t('app', 'Address'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'country_code' => Yii::t('app', 'Country'),
            'referrer_url' => Yii::t('app', 'Referral Url'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'mobile' => Yii::t('app', 'Mobile'),
            'landline' => Yii::t('app', 'Landline'),
            'skype_id' => Yii::t('app', 'Skype'),
            'website' => Yii::t('app', 'Website'),
            'budget_type_id' => Yii::t('app', 'Budget'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
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

    public static function getHasManyRelations()
    {
        $relations = [];

        $relations['id'] = [
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

    public function getPrevious()
    {
        return self::find()->where([
            'email' => $this->email
        ])->andWhere([
            'not',
            [
                'id' => $this->id
            ]
        ]);
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

    public function afterSave($insert, $changedAttributes)
    {
        if (0 && $insert && $this->state_id != Information::STATE_SPAM) { // disabled by shiv sir on 20-oct-2022
                                                                          // Sends email confirmation mail to user
            $subject = 'Thank You for contacting us !!!';
            $msg = \yii::$app->view->renderFile('@app/modules/contact/mail/thank-you.php', [
                'model' => $this
            ]);
            EmailQueue::add([
                // 'from' => \Yii::$app->params['adminEmail'],
                'subject' => $subject,
                'to' => $this->email,
                'html' => $msg
            ], false);
        }

        $old_state = isset($changedAttributes['state_id']) ? $changedAttributes['state_id'] : $this->state_id;
        if ($insert || $old_state != $this->state_id) {
            switch ($this->state_id) {
                case self::STATE_DRAFT:
                    break;
                case self::STATE_SUBMITTED:
                    // emails disabled
                    if (0) {

                        $sub = 'New Contact: ' . $this;
                        $message = \yii::$app->view->renderFile('@app/modules/contact/mail/contact.php', [
                            'user' => $this
                        ]);

                        EmailQueue::sendEmailToAdmins([
                            // 'from' => $this->email,
                            'subject' => $sub,
                            'html' => $message
                        ], false);
                    }
                    break;
            }
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['full_name'] = $this->full_name;
        $json['email'] = $this->email;
        $json['subject'] = $this->subject;
        $json['description'] = $this->description;
        $json['address'] = $this->address;
        $json['ip_address'] = $this->ip_address;
        $json['country'] = $this->country;
        $json['referrer_url'] = $this->referrer_url;
        $json['user_agent'] = $this->user_agent;
        $json['mobile'] = $this->mobile;
        // $json['landline'] = $this->landline;
        // $json['skype_id'] = $this->skype_id;
        $json['website'] = $this->website;
        $json['state_id'] = $this->state;
        $json['type_id'] = $this->type;
        $json['created_on'] = $this->created_on;
        // $json['created_by_id'] = $this->created_by_id;
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

    public function checkSpamMail()
    {
        if (Unsubscribe::check($this->email)) {
            return 1;
        }
        if (strstr($this->user_agent, 'bot')) {
            return 1;
        }
        if (! $this->isPhoneValid()) {
            return 1;
        }

        $last = self::find()->orderBy('id DESC')->one();
        if ($last && $last->email == $this->email) {
            return 1;
        }

        return self::find()->where([
            'AND',
            [
                'state_id' => $this::STATE_SPAM
            ],
            [
                'OR',
                [
                    'email' => $this->email
                ],
                [
                    'mobile' => $this->mobile
                ]

                // No need to match description as it is not required
            ]
        ])->count();
    }

    public function getControllerID()
    {
        return '/contact/' . parent::getControllerID();
    }

    public function getCountry()
    {
        if ($this->country_code) {
            return World::findCountryByCode($this->country_code);
        }
        return 'INDIA';
    }

    public function isPhoneValid()
    {
        if (! TStringHelper::startsWith($this->mobile, '+')) {
            return false;
        }
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $swissNumberProto = $phoneUtil->parse($this->mobile, "CH");
            return true;
        } catch (\libphonenumber\NumberParseException $e) {
            var_dump($e);
        }
        return false;
    }

    public static function createNewRecord()
    {
        $model = null;
        $outJson = \Yii::$app->session->get(self::CONTACT_SESSION_KEY);
        if ($outJson) {
            $model = new Information();
            $model->loadDefaultValues();
            // VarDumper::dump($outJson);
            $model->setAttributes($outJson);
        }

        if ($model == null) {
            $model = new Information();
            $model->loadDefaultValues();
            $model->budget_type_id = Information::BUDGET_10K;
            $model->type_id = Information::TYPE_QUOTE;
            $model->state_id = Information::STATE_DRAFT;
            $model->referrer_url = Yii::$app->request->referrer ?: Yii::$app->request->absoluteUrl;
            $model->website = Url::current([], true);
            $model->ip_address = \Yii::$app->request->userIP;
            $model->user_agent = \Yii::$app->request->userAgent;
            $model->country_code = World::getCountryCodeByIp($model->ip_address);
            $model->description = Yii::$app->request->getQueryString();
        }

        $ref = Yii::$app->request->getQueryParam('keyword');
        if ($ref && ! is_array($ref)) {
            $model->description = 'Need  ' . $ref;
        }
        $ref = Yii::$app->request->getQueryParam('ref');
        if ($ref && ! is_array($ref)) {
            $model->description = 'Need more details on ' . $ref;
        }
        $id = Yii::$app->request->getQueryParam('id');
        if ($id) {
            $class = 'app\modules\portfolio\models\Portfolio';
            if (class_exists($class)) {
                $port = $class::findOne($id);
                if ($port) {
                    $model->description = 'Need more details on portfolio ' . $port . ":" . $port->id;
                }
            }
        }
        $product_id = Yii::$app->request->getQueryParam('product');
        if ($product_id) {
            $class = 'app\models\Product';
            if (class_exists($class)) {
                $port = $class::findOne($product_id);
                if ($port) {
                    $model->description = 'Need more details on product: ' . $port . ":" . $port->id;
                }
            }
        }
        $model->subject = ! empty($model->description) ? TStringHelper::truncate($model->description, 200) : '';

        self::updateNewRecord($model);

        return $model;
    }

    public static function updateNewRecord($model)
    {
        $outJson = $model->toArray([
            'full_name',
            'email',
            'mobile',
            'description',
            'budget_type_id',
            'type_id',
            'referrer_url',
            'ip_address',
            'user_agent',
            'country_code',
            'subject',
            'website'
        ]);

        \Yii::$app->session->set(self::CONTACT_SESSION_KEY, $outJson);
    }

    public function sendToLeadManager($company_id = 1)
    {
        self::log('Sending lead  ');

        $leadManagerUrl = 'https://lead.toxsl.com/lead/push';

        $json = [];

        $json['title'] = $this->type . ' ==> ' . $this->subject;
        $json['ref_url'] = (! empty($this->referrer_url)) ? $this->referrer_url : '#';
        $json['budget'] = $this->budgetType;
        $json['description'] = $this->asJson();
        $json['client_name'] = $this->full_name;
        $json['client_email'] = $this->email;
        $json['client_skype'] = $this->mobile;
        $json['client_contact'] = $this->mobile;
        $json['client_address'] = $this->country;
        // $json['type_id'] = 1;
        $json['state_id'] = 0;
        $json['company_id'] = strstr(Yii::$app->id, 'ozvid') ? 2 : 1;

        $client = new Client();
        $request = $client->createRequest()
            ->setOptions([
            'followLocation' => true
        ])
            ->setMethod('POST')
            ->setUrl($leadManagerUrl)
            ->setData([
            'Lead' => $json
        ]);
        $response = $request->send();
        self::log('request:POST: ' . $leadManagerUrl);
        self::log('response:' . VarDumper::dumpAsString($response));

        if ($response->isOk) {

            VarDumper::dump($response->getContent());
            $this->state_id = Information::STATE_PROCESSED;
            $this->updateAttributes([
                'state_id'
            ]);
            return 1;
        }
        return 0;
    }

    public static function sendChatToLeadManager()
    {
        self::log('Sending chat  ');

        $leadManagerUrl = 'https://lead.toxsl.com/lead/chat';

        $json = [];
        $json['ref_url'] = Yii::$app->request->referrer ?: Yii::$app->request->absoluteUrl;
        $json['website'] = Url::current([], true);
        $json['ip_address'] = \Yii::$app->request->userIP;
        $json['country_code'] = World::getCountryCodeByIp($json['ip_address']);
        $json['user_agent'] = \Yii::$app->request->userAgent;
        $json['company_id'] = strstr(Yii::$app->id, 'ozvid') ? 2 : 1;
        $json['description'] = $json;
        $client = new Client();
        $request = $client->createRequest()
            ->setOptions([
            'followLocation' => true
        ])
            ->setMethod('POST')
            ->setUrl($leadManagerUrl)
            ->setData([
            'Lead' => $json
        ]);
        $response = $request->send();
        self::log('request:POST: ' . $leadManagerUrl);
        self::log('response:' . VarDumper::dumpAsString($response));

        if ($response->isOk) {
            // VarDumper::dump($response->getContent());
            return 1;
        }
        VarDumper::dump($response);
        return 0;
    }
}
