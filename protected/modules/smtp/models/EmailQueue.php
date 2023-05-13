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

use app\components\helpers\TStringHelper;
use app\models\EmailQueue as EmailQueueBase;
use app\models\Feed;
use app\models\File;
use app\models\User;
use app\modules\comment\models\Comment;
use Yii;
use app\components\helpers\TRegExHelper;

/**
 * This is the model class for table "tbl_smtp_email_queue".
 *
 * @property integer $id
 * @property string $subject
 * @property string $from
 * @property string $to
 * @property string $cc
 * @property string $bcc
 * @property string $content
 * @property integer $type_id
 * @property integer $state_id
 * @property integer $attempts
 * @property string $sent_on
 * @property string $created_on
 * @property integer $model_id
 * @property string $model_type
 * @property integer $smtp_account_id
 * @property string $message_id
 * @property string $re_message_id
 */
class EmailQueue extends \app\components\TActiveRecord
{

    public $bcc_self = false;

    public $mail_sent = 0;

    public function __toString()
    {
        return (string) $this->subject;
    }

    const STATE_PENDING = 0;

    const STATE_SENT = 1;

    const STATE_DELETED = 2;

    const STATE_SEEN = 3;

    const TYPE_DELETE_AFTER_SEND = 0;

    const TYPE_KEEP_AFTER_SEND = 1;

    public static function getStateOptions()
    {
        return [
            self::STATE_PENDING => "Pending",
            self::STATE_SENT => "Sent",
            self::STATE_DELETED => "Discarded",
            self::STATE_SEEN => "Seen"
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
            self::STATE_PENDING => "primary",
            self::STATE_SENT => "success",
            self::STATE_DELETED => "danger",
            self::STATE_SEEN => "warning"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public function getModel()
    {
        if (! empty($this->model_type))
            return $this->model_type::findOne($this->model_id);
        return null;
    }

    public function getToEmails()
    {
        return $this->hasOne(self::class, [
            'to' => 'to'
        ]);
    }

    public static function getSmtpAccountOptions()
    {
        return self::listData(Account::findActive()->all());
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmtpAccount()
    {
        return $this->hasOne(Account::className(), [
            'id' => 'smtp_account_id'
        ])->cache();
    }

    public static function add($args = [], $trySendNow = true)
    {
        Yii::warning('emailqueue add ');

        if (defined('MIGRATION_IN_PROGRESS')) {
            return false;
        }

        if (empty($args) || ! is_array($args)) {
            Yii::error('invalid args failed');
            return false;
        }

        $mail = new EmailQueueBase();

        $mail->loadDefaultValues();
        $mail->state_id = self::STATE_PENDING;
        $mail->handleArgs($args);

        $mail->type_id = isset($args['type_id']) ? $args['type_id'] : self::TYPE_DELETE_AFTER_SEND;

        if (is_object($args['to'])) {
            if ($args['to']->hasAttribute('email')) {
                $mail->to = $args['to']->email;
            }
        } else {
            $mail->to = $args['to'];
        }

        if (isset($args['cc'])) {
            if (is_object($args['cc'])) {
                if ($args['cc']->hasAttribute('email')) {
                    $mail->cc = $args['cc']->email;
                }
            } else {
                $mail->cc = $args['cc'];
            }
        }
        if (isset($args['bcc'])) {
            if (is_object($args['bcc'])) {
                if ($args['bcc']->hasAttribute('email')) {
                    $mail->bcc = $args['bcc']->email;
                }
            } else {
                $mail->bcc = $args['bcc'];
            }
        }

        $to = self::cleanEmailAddress($mail->to);

        if (! $mail->unsubscribeCheck($to)) {
            Yii::error('unsubscribeCheck failed');
            return false;
        }
        if (isset($args['from'])) {
            $mail->from = self::cleanEmailAddress($args['from']);
        } else {
            $emailAccount = Account::findByEmail($to);

            if (isset($emailAccount)) {
                $mail->from = $emailAccount->email;
            }
        }
        if (empty($mail->from)) {
            $mail->from = Yii::$app->params['adminEmail'];
        }
        if (isset($args['model'])) {
            $mail->model_id = $args['model']->id;
            $mail->model_type = get_class($args['model']);
        }

        $mail->subject = (isset($args['subject'])) ? $args['subject'] : "EmailQueue";

        if (isset($args['html'])) {
            $mail->content = $args['html'];
        } else {
            $view = isset($args['view']) ? $args['view'] : '@app/mail/email';
            $args = isset($args['viewArgs']) ? $args['viewArgs'] : [];
            $mail->content = \Yii::$app->mailer->render($view, $args);
        }
        if (isset($args['message_id'])) {
            $mail->message_id = $args['message_id'];
        }

        if (! $mail->save()) {
            Yii::error('saved failed' . $mail->getErrorsString());
            return null;
        }

        if (isset($args['attachments'])) {
            $attachments = is_array($args['attachments']) ? $args['attachments'] : [
                $args['attachments']
            ];
            foreach ($attachments as $attachment) {
                if (is_file($attachment)) {
                    File::add($mail, file_get_contents($attachment), basename($attachment));
                }
                if ($attachment instanceof File) {
                    File::add($mail, file_get_contents($attachment->fullPath), $attachment->name);
                }
            }
        }

        if ($trySendNow) {

            Yii::debug('trySendNow');
            $mail->sendNow();
        }

        return $mail;
    }

    public function getFooter($full = true)
    {
        $unsubscribeUrl = $this->getAbsoluteUrl('unsubscribe');
        $imgUrl = $this->getAbsoluteUrl('image');

        $html = '<div class="text-center" align="center">';

        $html .= '<p style="font-size: 14px; padding: 0; color: #666">';
        $html .= "This email was sent to {$this->to}</p>";
        $html .= "<p><a href=\"$unsubscribeUrl\">Unsubscribe</a></p><br>";
        $html .= "<p> <img src=\"$imgUrl\" ></p> </div>";
        if (! $full) {
            self::log('footer is false so only image ' . $imgUrl);
            $html = "<img src='$imgUrl' >";
        }
        return $html;
    }

    public static function cleanEmailAddress($value)
    {
        $pattern = TRegExHelper::PATTERN_EMAIL;
        if (preg_match($pattern, $value, $matches)) {
            $out = ($matches[0]);
        } else {
            $out = $value;
        }
        self::log($value . ' : cleanEmailAddress==> ' . $out);
        return trim($out, '-');
    }

    public static function clearSent($days = 90)
    {
        $query = EmailQueueBase::find()->where([
            'in',
            'state_id',
            [
                self::STATE_SENT,
                self::STATE_DELETED
            ]
        ]);

        if (! empty($days)) {
            $query->andWhere([
                '<',
                'DATE(sent_on)',
                date('Y-m-d', strtotime('-' . $days . ' days'))
            ]);
        }

        self::deleteRelatedAll($query);
    }

    protected function unsubscribeCheck($to)
    {
        $unsubscribe = Unsubscribe::check($to);

        if ($unsubscribe) {
            $this->discard();
            return false;
        }
        return true;
    }

    public function getMailer()
    {
        if (strstr($this->from, '@')) {

            $emailAccount = Account::findByEmail($this->from);
        } else {
            $emailAccount = Account::findByEmail($this->to);
        }

        if (isset($emailAccount)) {

            return $emailAccount->getMailer();
        }
        return \Yii::$app->mailer;
    }

    public function sendNow()
    {
        $mail_sent = 0;
        $settings = new SettingsForm();

        if (! $settings->enableEmails) {
            self::log("Emails not enabled");
            return 0;
        }

        try {

            $mailer = $this->getMailer();

            $mail = $mailer->compose();

            if (isset($this->re_message_id)) {
                $mail->setHeader('In-Reply-To', $this->re_message_id);
            }

            $mail->setHtmlBody($this->content . $this->getFooter(false));

            if (empty($this->to)) {
                self::log("Email id invalid");
                return 0;
            }
            if (strstr($this->to, ',')) {
                $tolist = explode(',', $this->to);
            } else {
                $tolist = [
                    $this->to
                ];
            }

            foreach ($tolist as $to) {

                if (! $this->unsubscribeCheck($to)) {
                    Yii::error('unsubscribeCheck failed:' . $to);
                    return 0;
                }
            }

            $mail->setTo($tolist);

            $from = [
                $mailer->config->email => $this->from
            ];

            if ($this->bcc_self) {
                $mail->setBcc($from);
            }
            if ($this->cc) {
                $mail->setCc($this->cc);
            }
            $mail->setFrom($from);

            $mail->setSubject($this->subject);

            // $mail->setReplyTo($this->from);

            $this->addExtraHeaders($mail);

            if (isset($this->files)) {
                foreach ($this->getFiles()->each() as $file) {
                    $filename = $file->fullPath;
                    if (is_file($filename)) {
                        self::log('Attaching file:' . $filename);
                        $mail->attach($filename, [
                            'fileName' => TStringHelper::basename($filename)
                        ]);
                    }
                }
            }

            if (! defined('DISABLE_EMAILS')) {
                $mailer->send($mail);
            }

            if ($mailer->isSuccessful) {
                $this->message_id = $mailer->getMessageId();

                $this->sent_on = date('Y-m-d H:i:s');
                $this->state_id = self::STATE_SENT;
                $this->updateAttributes([
                    'state_id',
                    'sent_on',
                    'message_id',
                    'content'
                ]);

                $project = $this->getModel();
                if ($project && method_exists($project, 'confirmSent')) {
                    $project->confirmSent($this);
                }

                if (! $settings->keepAfterSend && $this->type_id == EmailQueueBase::TYPE_DELETE_AFTER_SEND) {
                    // Delete emails alreday sent
                    self::log('Deleting email already sent');
                    $this->delete();
                }
            }
        } catch (\Exception $e) {
            self::log($e->getMessage());
            self::log($e->getTraceAsString());
        }
        return $mail_sent;
    }

    public function addExtraHeaders($mail)
    {
        $unsubscribeUrl = $this->getAbsoluteUrl('unsubscribe');

        $mail->addHeader('List-Unsubscribe', "<mailto:$this->from?Subject=Unsubscribe:{$this->id}:{$this->to}>,<$unsubscribeUrl>");
    }

    public static function sendEmailToAdmins($data, $trySendNow = (YII_ENV == 'prod'))
    {
        $allAdmins = User::findActive()->andWhere([
            'role_id' => User::ROLE_ADMIN
        ]);
        Yii::debug('sendEmailToAdmins');
        foreach ($allAdmins->batch() as $admins) {
            foreach ($admins as $admin) {
                $data['to'] = $admin->email;
                EmailQueueBase::add($data, $trySendNow);
            }
        }
    }

    public function handleArgs($args = [])
    {
        if (isset($args['smtp_account_id'])) {
            $account = Account::findOne($args['smtp_account_id']);
        }
        if (isset($account)) {
            $this->smtp_account_id = $account->id;
        }
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (! isset($this->sent_on))
                $this->sent_on = date('Y-m-d h:i:s');
            if (! isset($this->state_id))
                $this->state_id = EmailQueue::STATE_PENDING;
            if (empty($this->created_on)) {
                $this->created_on = date('Y-m-d H:i:s');
            }
        }

        $this->subject = TStringHelper::truncate($this->subject, 240);

        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%smtp_email_queue}}';
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
                    'to',
                    'created_on'
                ],
                'required'
            ],
            [
                [
                    'content'
                ],
                'string',
                'max' => 65000
            ],
            [
                [
                    'type_id',
                    'state_id',
                    'attempts',
                    'model_id',
                    'smtp_account_id'
                ],
                'integer'
            ],
            [
                [
                    'sent_on',
                    'created_on'
                ],
                'safe'
            ],
            [
                [
                    'subject',
                    'message_id',
                    're_message_id'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'from',
                    'to',
                    'cc',
                    'bcc',
                    'model_type'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(EmailQueue::getStateOptions())
            ],
            [
                [
                    'subject',
                    'message_id',
                    're_message_id',
                    'from',
                    'to',
                    'cc',
                    'bcc',
                    'model_type'
                ],
                'trim'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject' => Yii::t('app', 'Subject'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'cc' => Yii::t('app', 'Cc'),
            'bcc' => Yii::t('app', 'Bcc'),
            'content' => Yii::t('app', 'Content'),
            'type_id' => Yii::t('app', 'Type'),
            'state_id' => Yii::t('app', 'State'),
            'attempts' => Yii::t('app', 'Attempts'),
            'sent_on' => Yii::t('app', 'Sent On'),
            'created_on' => Yii::t('app', 'Created On'),
            'model_id' => Yii::t('app', 'Model'),
            'model_type' => Yii::t('app', 'Model Type'),
            'smtp_account_id' => Yii::t('app', 'Smtp Account'),
            'message_id' => Yii::t('app', 'Message'),
            're_message_id' => Yii::t('app', 'Re Message')
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

    public function getControllerID()
    {
        return '/smtp/' . parent::getControllerID();
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['subject'] = $this->subject;
        $json['from'] = $this->from;
        $json['to'] = $this->to;
        $json['cc'] = $this->cc;
        $json['bcc'] = $this->bcc;
        $json['content'] = $this->content;
        $json['type_id'] = $this->type_id;
        $json['state_id'] = $this->state_id;
        $json['attempts'] = $this->attempts;
        $json['sent_on'] = $this->sent_on;
        $json['created_on'] = $this->created_on;
        $json['model_id'] = $this->model_id;
        $json['model_type'] = $this->model_type;
        $json['smtp_account_id'] = $this->smtp_account_id;
        $json['message_id'] = $this->message_id;
        $json['re_message_id'] = $this->re_message_id;
        if ($with_relations) {}
        return $json;
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new EmailQueueBase();
            $model->loadDefaultValues();
            $model->subject = $faker->text(10);
            $model->from = $faker->text(10);
            $model->to = $faker->text(10);
            $model->cc = $faker->text(10);
            $model->bcc = $faker->text(10);
            $model->content = $faker->text;
            $model->type_id = 0;
            $model->state_id = $states[rand(0, count($states))];
            $model->attempts = $faker->text(10);
            $model->sent_on = \date('Y-m-d H:i:s');
            $model->model_id = 1;
            $model->model_type = $faker->text(10);
            $model->smtp_account_id = 1;
            $model->message_id = 1;
            $model->re_message_id = 1;
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
            $model = new EmailQueueBase();
            $model->loadDefaultValues();

            $model->subject = isset($item['subject']) ? $item['subject'] : $faker->text(10);

            $model->from = isset($item['from']) ? $item['from'] : $faker->text(10);

            $model->to = isset($item['to']) ? $item['to'] : $faker->text(10);

            $model->cc = isset($item['cc']) ? $item['cc'] : $faker->text(10);

            $model->bcc = isset($item['bcc']) ? $item['bcc'] : $faker->text(10);

            $model->content = isset($item['content']) ? $item['content'] : $faker->text;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;

            $model->state_id = self::STATE_SENT;

            $model->attempts = isset($item['attempts']) ? $item['attempts'] : $faker->text(10);

            $model->sent_on = isset($item['sent_on']) ? $item['sent_on'] : \date('Y-m-d H:i:s');

            $model->model_id = isset($item['model_id']) ? $item['model_id'] : 1;

            $model->model_type = isset($item['model_type']) ? $item['model_type'] : $faker->text(10);

            $model->smtp_account_id = isset($item['smtp_account_id']) ? $item['smtp_account_id'] : 1;

            $model->message_id = isset($item['message_id']) ? $item['message_id'] : 1;

            $model->re_message_id = isset($item['re_message_id']) ? $item['re_message_id'] : 1;
            $model->save();
        }
    }

    public static function getPendingEmails()
    {
        return EmailQueueBase::find()->where([
            'state_id' => EmailQueue::STATE_PENDING
        ]);
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

    public function getFeeds()
    {
        return $this->hasMany(Feed::class, [
            'model_id' => 'id'
        ])->andWhere([
            'like',
            'model_type',
            TStringHelper::basename(get_called_class())
        ]);
    }

    public function getFiles()
    {
        return $this->hasMany(File::class, [
            'model_id' => 'id'
        ])->andWhere([
            'like',
            'model_type',
            TStringHelper::basename(get_called_class())
        ]);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, [
            'model_id' => 'id'
        ])->andWhere([
            'like',
            'model_type',
            TStringHelper::basename(get_called_class())
        ]);
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }

        return true;
    }

    public function discard()
    {
        $this->state_id = self::STATE_DELETED;
        self::log('Discarded');
        $this->updateAttributes([
            'state_id'
        ]);
    }

    /**
     * SMTP unsubscribe
     *
     * @return \app\modules\smtp\models\Unsubscribe
     */
    public function getUnsubscribeEmail()
    {
        $unsubscribe = new Unsubscribe();
        $unsubscribe->loadDefaultValues();
        $unsubscribe->email = $this->to;
        return $unsubscribe;
    }

    protected function processFeed($insert, $changedAttributes)
    {
        // feeds not required
    }
}