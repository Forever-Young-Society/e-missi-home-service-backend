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
namespace app\modules\smtp\models;

use app\models\User;
use app\modules\smtp\components\SmtpMailer;
use app\modules\smtp\helpers\DnsHelper;
use Yii;

/**
 * This is the model class for table "tbl_smtp_account".
 *
 * @property integer $id
 * @property string $title
 * @property string $email
 * @property string $password
 * @property string $server
 * @property integer $port
 * @property integer $encryption_type
 * @property integer $limit_per_email
 * @property integer $state_id
 * @property integer $type_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by_id
 * @property User $createdBy
 */
class Account extends \app\components\TActiveRecord
{

    use DnsHelper;

    private $_mailer;

    public function __toString()
    {
        return (string) $this->title;
    }

    const TYPE_NO_ENCRYPTION = 0;

    const TYPE_ENCRYPTION_TLS = 1;

    const TYPE_ENCRYPTION_SSL = 2;

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getEncryptionOptions()
    {
        return [
            self::TYPE_NO_ENCRYPTION => "None",
            self::TYPE_ENCRYPTION_TLS => "TLS",
            self::TYPE_ENCRYPTION_SSL => "SSL"
        ];
    }

    public function getEncryption()
    {
        $list = self::getEncryptionOptions();
        return isset($list[$this->encryption_type]) ? $list[$this->encryption_type] : 'Not Defined';
    }

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
            "SMTP",
            "GMAIL",
            "OUTLOOK"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->encryption_type == self::TYPE_ENCRYPTION_TLS)
            $this->port = '587';
        elseif ($this->encryption_type == self::TYPE_ENCRYPTION_SSL)
            $this->port = '465';
        else
            $this->port = '25';

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
        } else {
            $this->updated_on = date('Y-m-d H:i:s');
        }
        if (empty($this->server)) {
            $this->server = self::getMXServer($this->email);
        }
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%smtp_account}}';
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
                    'email',
                    'password',
                    'created_on',
                    'encryption_type'
                ],
                'required'
            ],
            [
                [
                    'port',
                    'encryption_type',
                    'limit_per_email',
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
                    'title',
                    'email',
                    'password',
                    'server'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'password'
                ],
                'string',
                'min' => 8
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
                    'email',
                    'password',
                    'server'
                ],
                'trim'
            ],
            [
                [
                    'email'
                ],
                'email'
            ],
            [
                [
                    'email',
                    'title'
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
            'title' => Yii::t('app', 'Title'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'server' => Yii::t('app', 'Server'),
            'port' => Yii::t('app', 'Port'),
            'encryption_type' => Yii::t('app', 'Encryption'),
            'limit_per_email' => Yii::t('app', 'Limit Per Email'),
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
        ])->cache();
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmails()
    {
        // return $this->hasMany(EmailQueue::class, [
        // 'smtp_account_id' => 'id'
        // ]);
        return $this->hasMany(EmailQueue::class, [
            'from' => 'email'
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

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        $json['email'] = $this->email;
        $json['password'] = $this->getDecryptedPassword($this->password);
        $json['server'] = $this->server;
        $json['port'] = $this->port;
        $json['encryption_type'] = $this->encryption_type;
        $json['limit_per_email'] = $this->limit_per_email;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {
            // createdBy
            $list = $this->createdBy;

            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['createdBy'] = $relationData;
            } else {
                $json['createdBy'] = $list;
            }
        }
        return $json;
    }

    public function getMailer()
    {
        if ($this->_mailer == null) {

            if (empty($this->server)) {
                $this->server = self::getMXServer($this->email);
            }

            if (\Yii::$app->mailer instanceof SmtpMailer) {
                if (\Yii::$app->mailer->account_id == $this->id) {
                    $this->_mailer = \Yii::$app->mailer;
                }
            }
            $this->_mailer = \Yii::createObject([
                'class' => SmtpMailer::class,
                'config' => $this
                // 'account_id' => $this->id
            ]);
        }
        return $this->_mailer;
    }

    public function getControllerID()
    {
        return '/smtp/' . parent::getControllerID();
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->title = $faker->text(10);
            $model->email = $faker->email;
            $model->server = $faker->text(10);
            $model->port = $faker->text(10);
            $model->encryption_type = $faker->text(10);
            $model->limit_per_email = $faker->text(10);
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

            $model->title = isset($item['title']) ? $item['title'] : $faker->text(10);

            $model->email = isset($item['email']) ? $item['email'] : $faker->email;

            $model->server = isset($item['server']) ? $item['server'] : $faker->text(10);

            $model->port = isset($item['port']) ? $item['port'] : $faker->text(10);

            $model->encryption_type = isset($item['encryption_type']) ? $item['encryption_type'] : $faker->text(10);

            $model->limit_per_email = isset($item['limit_per_email']) ? $item['limit_per_email'] : $faker->text(10);
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->save();
        }
    }

    public function test($to_email, $from_email)
    {
        $mail_sent = 0;

        $mailer = $this->getMailer();

        $mail = $mailer->compose();
        $mail->setHtmlBody("test")
            ->setTo($to_email)
            ->setFrom($from_email)
            ->setSubject("test");

        $mail_sent = $mail->send();

        if (! $mail_sent) {
            $this->addError('password', 'error');
        }
        return $mail_sent;
    }

    public static function findByEmail($email = null)
    {
        $account = null;

        if ($email != null) {
            $account = self::findActive()->andWhere([
                'email' => $email
            ])
                ->orderBy('updated_on asc')
                ->one();

            if ($account == null) {
                // try similar account matching domain
                if (strstr($email, '@')) {
                    $domain = explode('@', $email)[1];
                    $account = self::findActive()->andWhere([
                        'like',
                        'email',
                        $domain
                    ])->one();
                }
            }
        }
        if ($account == null) {
            $account = self::findActive()->orderBy('updated_on asc')->one();

            // if ($account == null)
            // throw new \Exception('Outgoing Server Not set');
        }
        return $account;
    }

    public static function getEmailAccount($id = null)
    {
        $account = self::find()->andFilterWhere([
            'id' => $id
        ])->one();
        return $account->email;
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
}
