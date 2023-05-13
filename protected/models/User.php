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
namespace app\models;

use app\components\helpers\TEmailTemplateHelper;
use app\modules\api\models\AccessToken;
use app\modules\availability\models\BookingService;
use app\modules\availability\models\ProviderSlot;
use app\modules\availability\models\SlotBooking;
use app\modules\rating\models\Rating;
use app\modules\service\models\Category;
use app\modules\service\models\ProviderSkill;
use app\modules\service\models\Report;
use app\modules\workzone\models\Location;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\modules\logger\models\Log;

/**
 * This is the model class for table "tbl_user".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property string $date_of_birth
 * @property integer $gender
 * @property string $about_me
 * @property string $contact_no
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $city
 * @property string $country
 * @property string $zipcode
 * @property string $language
 * @property string $profile_file
 * @property integer $tos
 * @property integer $role_id
 * @property integer $state_id
 * @property integer $type_id
 * @property string $last_visit_time
 * @property string $last_action_time
 * @property string $last_password_change
 * @property integer $login_error_count
 * @property string $activation_key
 * @property string $timezone
 * @property string $created_on
 * @property integer $created_by_id
 * @property LoginHistory[] $loginHistories
 * @property Page[] $pages
 * @property Company[] $companies
 */
class User extends \app\components\TActiveRecord implements \yii\web\IdentityInterface
{

    public $search;

    public $verifyCode;

    public $new_password;

    public $old_password;

    public $qualification;

    public $service_language;

    public $skill;

    public $work_zone;

    public $file_id;

    public $from_date;

    public $to_date;

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_BANNED = 2;

    const STATE_DELETED = 3;

    const STATE_USER_DELETED = 4;

    const MALE = 0;

    const FEMALE = 1;

    const ADMIN = 1;

    const ROLE_ADMIN = 0;

    const ROLE_MANAGER = 1;

    const ROLE_USER = 2;

    const ROLE_SERVICE_PROVIDER = 3;

    const TYPE_ON = 0;

    const TYPE_OFF = 1;

    const NOTIFICATION_ON = 1;

    const NOTIFICATION_OFF = 0;

    const EMAIL_NOT_VERIFIED = 0;

    const EMAIL_VERIFIED = 1;

    const OTP_VERIFIED = 1;

    const OTP_NOT_VERIFIED = 0;

    const STEP_ONE = 1;

    const STEP_TWO = 2;

    const STEP_THREE = 3;

    const OTP_ATTEMPT_ONE = 1;

    const OTP_ATTEMPT_TWO = 2;

    const OTP_ATTEMPT_THREE = 3;

    const OTP_ATTEMPT_FOUR = 4;

    const APPROVAL_PENDING = 0;

    const DEFAULT_COUNTRY_CODE = 60;

    const IS_APPROVE = 1;

    const IS_REJECT = 2;

    const TYPE_USER = 0;

    const TYPE_DEPENDENT = 1;

    const DEFAULT_CURRENCY = 'RM';

    public $confirm_password;

    public $oldPassword;

    public function __toString()
    {
        return (string) $this->full_name;
    }

    public static function getActiveList()
    {
        return ArrayHelper::map(User::findActive()->all(), 'id', 'full_name');
    }

    public static function getClients()
    {
        return User::find()->andWhere([
            '<=',
            'role_id',
            self::ROLE_Service_Provider
        ])->all();
    }

    public static function getGenderOptions($id = null)
    {
        $list = array(
            self::MALE => "Male",
            self::FEMALE => "Female"
        );
        if ($id === null)
            return $list;
        return isset($list[$id]) ? $list[$id] : 'Not Defined';
    }

    public function getGender()
    {
        $list = self::getGenderOptions();
        return isset($list[$this->gender]) ? $list[$this->gender] : 'Not Defined';
    }

    public static function getRoleOptions($id = null)
    {
        $list = array(
            self::ROLE_ADMIN => "Admin",
            self::ROLE_MANAGER => "Manager",
            self::ROLE_USER => "User",
            self::ROLE_SERVICE_PROVIDER => "Service Provider"
        );
        if ($id === null)
            return $list;
        return isset($list[$id]) ? $list[$id] : 'Not Defined';
    }

    public function getRole()
    {
        $list = self::getRoleOptions();
        return isset($list[$this->role_id]) ? $list[$this->role_id] : 'Not Defined';
    }

    public static function getFilterRoleOptions($id = null)
    {
        $list = array(
            self::ROLE_USER => "User",
            self::ROLE_SERVICE_PROVIDER => "Service Provider"
        );
        if ($id === null)
            return $list;
        return isset($list[$id]) ? $list[$id] : 'Not Defined';
    }

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "Inactive",
            self::STATE_ACTIVE => "Active",
            self::STATE_BANNED => "Banned",
            self::STATE_DELETED => "Deleted",
            self::STATE_USER_DELETED => "User Deleted"
        ];
    }

    public static function getLanguageOptions()
    {
        return self::listData(Language::findActive()->orderBy([
            'id' => SORT_DESC
        ])->all());
    }

    public static function getOtpOptions()
    {
        return [
            self::OTP_NOT_VERIFIED => Yii::t('app', 'Not Verified'),
            self::OTP_VERIFIED => Yii::t('app', 'Verified')
        ];
    }

    public function getOtpVerify()
    {
        $list = self::getOtpOptions();
        return isset($list[$this->otp_verified]) ? $list[$this->otp_verified] : Yii::t('app', 'Not Defined');
    }

    public function getOtpBadge()
    {
        $list = [
            self::OTP_NOT_VERIFIED => "danger",
            self::OTP_VERIFIED => "success"
        ];
        return isset($list[$this->otp_verified]) ? \yii\helpers\Html::tag('span', $this->getOtpVerify(), [
            'class' => 'badge badge-' . $list[$this->otp_verified]
        ]) : 'Not Defined';
    }

    public static function getISApproveOptions()
    {
        return [
            self::APPROVAL_PENDING => Yii::t('app', 'Approval Pending'),
            self::IS_APPROVE => Yii::t('app', 'Approved'),
            self::IS_REJECT => Yii::t('app', 'Rejected')
        ];
    }

    public function getApprove()
    {
        $list = self::getISApproveOptions();
        return isset($list[$this->is_approve]) ? $list[$this->is_approve] : Yii::t('app', 'Not Defined');
    }

    public function getApproveBadge()
    {
        $list = [
            self::IS_APPROVE => "success",
            self::APPROVAL_PENDING => "warning",
            self::IS_REJECT => "danger"
        ];
        return isset($list[$this->is_approve]) ? \yii\helpers\Html::tag('span', $this->getApprove(), [
            'class' => 'badge badge-' . $list[$this->is_approve]
        ]) : 'Not Defined';
    }

    public static function getQlaificationOptions()
    {
        return self::listData(Category::findActive()->orderBy([
            'id' => SORT_DESC
        ])->all());
    }

    public static function getSkillOptions()
    {
        return self::listData(ProviderSkill::findActive()->orderBy([
            'id' => SORT_DESC
        ])->all());
    }

    public static function getWorkzoneOptions()
    {
        return self::listData(Location::findActive()->orderBy([
            'id' => SORT_DESC
        ])->all());
    }

    public function getProviderWorkingZone()
    {
        $userWorkzone = $this->workzone;
        if (empty($userWorkzone)) {
            return 'Not Defined';
        }
        $workzone = $userWorkzone->workzoneDetail;
        return ! empty($workzone) ? $workzone->title : 'Not Defined';
    }

    public static function getUserAction()
    {
        return [
            self::STATE_INACTIVE => "In-activeate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_BANNED => "Ban",
            self::STATE_DELETED => "Delete",
            self::STATE_USER_DELETED => "User Delete"
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
            self::STATE_BANNED => "warning",
            self::STATE_DELETED => "danger",
            self::STATE_USER_DELETED => "danger"
        ];
        // return \yii\helpers\Html::tag('span', $this->getState(), ['class' => 'badge bg-' . $list[$this->state_id]]);
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_USER => "User",
            self::TYPE_DEPENDENT => "Dependent User"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public static function getProviderApprovalCount()
    {
        return User::findActive()->andWhere([
            'is_approve' => User::APPROVAL_PENDING,
            'role_id' => User::ROLE_SERVICE_PROVIDER,
            'step' => User::STEP_THREE
        ])->count();
    }

    public function getAuthSessions()
    {
        return $this->hasMany(AccessToken::className(), [
            'created_by_id' => 'id'
        ]);
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
        return '{{%user}}';
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
            'password' => Yii::t('app', 'Password'),
            'date_of_birth' => Yii::t('app', 'Date Of Birth'),
            'gender' => Yii::t('app', 'Gender'),
            'about_me' => Yii::t('app', 'About Me'),
            'contact_no' => Yii::t('app', 'Mobile Number'),
            'address' => Yii::t('app', 'Address'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'city' => Yii::t('app', 'City'),
            'country' => Yii::t('app', 'Country'),
            'zipcode' => Yii::t('app', 'Post Code'),
            'language' => Yii::t('app', 'Language'),
            'profile_file' => Yii::t('app', 'Profile File'),
            'tos' => Yii::t('app', 'Tos'),
            'role_id' => Yii::t('app', 'Role'),
            'is_notify' => Yii::t('app', 'Notify'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'last_visit_time' => Yii::t('app', 'Last Visit Time'),
            'last_action_time' => Yii::t('app', 'Last Action Time'),
            'last_password_change' => Yii::t('app', 'Last Password Change'),
            'login_error_count' => Yii::t('app', 'Login Error Count'),
            'activation_key' => Yii::t('app', 'Activation Key'),
            'timezone' => Yii::t('app', 'Timezone'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'created_by_id' => Yii::t('app', 'Created By'),
            'verifyCode' => Yii::t('app', 'Verification Code'),
            'identity_number' => Yii::t('app', 'Identification Number')
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLoginHistories()
    {
        return $this->hasMany(LoginHistory::class, [
            'user_id' => 'id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLanguages()
    {
        return $this->hasMany(UserLanguage::class, [
            'created_by_id' => 'id'
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkzones()
    {
        return $this->hasMany(UserWorkzone::class, [
            'created_by_id' => 'id'
        ]);
    }

    public function getProviderFiles()
    {
        return $this->hasMany(File::class, [
            'model_id' => 'id'
        ])->andOnCondition([
            'file_type' => File::FILE_TYPE_QUALIFICATION_DOCUMENT
        ]);
    }

    public function getUserFiles()
    {
        return $this->hasMany(File::class, [
            'model_id' => 'id'
        ])->andOnCondition([
            'file_type' => File::FILE_TYPE_MEDICAL_REPORT
        ]);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQualifications()
    {
        return $this->hasMany(UserCategory::class, [
            'created_by_id' => 'id'
        ]);
    }

    public function getWorkzone()
    {
        return $this->hasOne(UserWorkzone::class, [
            'created_by_id' => 'id'
        ])->cache(5);
    }

    public function getQualificationDetail()
    {
        return $this->hasOne(UserCategory::class, [
            'created_by_id' => 'id'
        ])->cache(5);
    }

    /**
     * Retunr date range values if set in session
     */
    public function getDateRange()
    {
        $from_date = $this->getDateRangeValue('from_date');
        $to_date = $this->getDateRangeValue('to_date');
        if (! empty($from_date) && ! empty($to_date)) {
            return date('d-M-y', strtotime($from_date)) . ' to ' . date('d-M-y', strtotime($to_date));
        }
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkills()
    {
        return $this->hasMany(UserSkill::class, [
            'created_by_id' => 'id'
        ]);
    }

    public function checkIsAnyDocumentPending()
    {
        return $this->getProviderFiles()
            ->andWhere([
            '!=',
            'is_approve',
            File::DOCUMENT_APPROVED
        ])
            ->exists();
    }

    public function getMedicalFile()
    {
        return $this->hasOne(File::class, [
            'model_id' => 'id'
        ])->andOnCondition([
            'file_type' => File::FILE_TYPE_MEDICAL_REPORT
        ]);
    }

    public function getProviderFile()
    {
        return $this->hasOne(File::class, [
            'model_id' => 'id'
        ])->andOnCondition([
            'file_type' => File::FILE_TYPE_QUALIFICATION_DOCUMENT
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'templates',
            'Template',
            'id'
        ];
        $relations['user_id'] = [
            'loginHistories',
            'LoginHistory',
            'id'
        ];

        return $relations;
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

    public function getDependentUsers()
    {
        return $this->hasMany(User::class, [
            'created_by_id' => 'id'
        ])->cache(5);
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

    public function sendRegistrationMailtoAdmin()
    {
        $sub = 'New User Registerd Successfully';
        $message = TEmailTemplateHelper::renderFile('@app/mail/newUser.php', [
            'user' => $this
        ]);
        $from = $this->email;
        EmailQueue::sendEmailToAdmins([
            'from' => $from,
            'subject' => $sub,
            'html' => $message,
            'type_id' => EmailQueue::TYPE_KEEP_AFTER_SEND
        ], true);
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        if ($this->id == \Yii::$app->user->id)
            return false;

        if (self::find()->count() <= 1)
            return false;

        LoginHistory::deleteRelatedAll([
            'user_id' => $this->id
        ]);

        AccessToken::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        Feed::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        File::deleteRelatedAll([
            'model_id' => $this->id,
            'file_type' => File::FILE_TYPE_MEDICAL_REPORT
        ]);

        UserLanguage::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        UserCategory::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        UserSkill::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        UserSubcategory::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        UserWorkzone::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        File::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        User::deleteRelatedAll([
            'created_by_id' => $this->id,
            'type_id' => self::TYPE_DEPENDENT
        ]);

        UserTerm::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        SlotBooking::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        SlotBooking::deleteRelatedAll([
            'provider_id' => $this->id
        ]);

        ProviderSlot::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        Report::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        Rating::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        BookingService::deleteRelatedAll([
            'created_by_id' => $this->id
        ]);

        \app\modules\comment\Module::beforeDelete($this->id);

        // Delete actual file
        $filePath = UPLOAD_PATH . $this->profile_file;

        if (is_file($filePath)) {
            unlink($filePath);
        }

        return true;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['update'] = [
            'full_name',
            'email',
            'password',
            'role_id',
            'state_id'
        ];

        $scenarios['add'] = [
            'full_name',
            'email',
            'password',
            'role_id',
            'state_id'
        ];

        $scenarios['signup'] = [
            'full_name',
            'email',
            // 'city',
            // 'country',
            'password',
            'confirm_password',
            'verifyCode',
            'captcha'
        ];
        $scenarios['add-admin'] = [
            'full_name',
            'email',
            'password',
            'confirm_password'
        ];
        $scenarios['changepassword'] = [
            'password',
            'confirm_password'
        ];

        $scenarios['resetpassword'] = [
            'password',
            'confirm_password'
        ];
        $scenarios['apichangepassword'] = [
            'password',
            'oldPassword'
        ];
        $scenarios['change-password'] = [
            'new_password'
        ];
        $scenarios['token_request'] = [
            'email',
            'activation_key',
            'role_id'
        ];
        $scenarios['date-range'] = [
            'from_date'
        ];

        $scenarios['update-provider'] = [
            'full_name',
            'email',
            'experience',
            'contact_no',
            'gender',
            'qualification',
            'identity_number',
            'skill',
            'service_language',
            'is_notify',
            'work_zone',
            'date_of_birth',
            'address',
            'profile_file'
        ];

        $scenarios['update-user'] = [
            'full_name',
            'email',
            'contact_no',
            'identity_number',
            'date_of_birth',
            'address',
            'profile_file',
            'about_me',
            'zipcode'
        ];
        return $scenarios;
    }

    public function findPasswords($attribute, $params)
    {
        $user = User::find()->where([
            'email' => Yii::$app->user->identity->email
        ])->one();
        if (! $user->validatePassword($this->oldPassword))
            $this->addError($attribute, 'Old password is incorrect');
    }

    /**
     *
     * @inheritdoc
     */
    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    // 'full_name',
                    // 'email',
                    'password',
                    // 'role_id',
                    'state_id',
                    'created_on'
                ],
                'required'
            ],

            [
                [
                    'full_name',
                    'email',
                    'about_me',
                    'contact_no',
                    'city',
                    'country',
                    'zipcode',
                    'language',
                    'profile_file',
                    'timezone',
                    'password',
                    'activation_key',
                    'address',
                    'latitude',
                    'longitude'
                ],
                'trim'
            ],

            [
                [
                    'contact_no'
                ],
                'unique'
            ],

            [
                [
                    'zipcode'
                ],
                'number'
            ],

            [
                [
                    'experience'
                ],
                'number',
                'min' => 0,
                'max' => 100
            ],

            [
                [
                    'password',
                    'confirm_password'
                ],
                'required',
                'on' => 'changepassword'
            ],

            [
                [
                    'new_password'
                ],
                'required',
                'on' => 'change-password'
            ],

            [
                [
                    'password',
                    'confirm_password'
                ],
                'required',
                'on' => 'resetpassword'
            ],

            [
                [
                    'full_name',
                    'email',
                    'password',
                    'confirm_password',
                    'verifyCode'
                ],
                'required',
                'on' => 'signup'
            ],

            [
                [
                    'full_name',
                    'email'
                ],
                'required',
                'on' => 'update'
            ],

            [
                [
                    'full_name',
                    'email',
                    'experience',
                    'contact_no',
                    'gender',
                    'qualification',
                    'identity_number',
                    'skill',
                    'service_language',
                    'work_zone',
                    'date_of_birth',
                    'address'
                ],
                'required',
                'on' => 'update-provider'
            ],

            [
                'identity_number',
                'match',
                'pattern' => '/^[0-9-()]*$/',
                'message' => 'Identity Number must be number'
            ],

            [
                [
                    'full_name',
                    'email',
                    'contact_no',
                    'identity_number',
                    'date_of_birth',
                    'address',
                    'about_me',
                    'zipcode'
                ],
                'required',
                'on' => 'update-user'
            ],
            [
                [
                    'full_name',
                    'email',
                    'password',
                    'confirm_password'
                ],
                'required',
                'on' => 'add-admin'
            ],
            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'password',
                'message' => "Passwords don't match",
                'on' => [
                    'signup',
                    'changepassword',
                    'resetpassword'
                ]
            ],
            [
                'password',
                'app\components\validators\TPasswordValidator'
            ],

            [
                [
                    'full_name'
                ],
                'app\components\validators\TNameValidator'
            ],

            [

                'email',
                'email'
            ],
            [
                [
                    'full_name'
                ],
                'filter',
                'filter' => function ($data) {
                    return ucwords(" " . $data);
                }
            ],
            [
                [
                    'search',
                    'date_of_birth',
                    'last_visit_time',
                    'last_action_time',
                    'last_password_change',
                    'created_on',
                    'country_code',
                    'otp',
                    'otp_verified',
                    'new_password',
                    'old_password',
                    'qualification',
                    'identity_number',
                    'skill',
                    'service_language',
                    'is_notify',
                    'work_zone',
                    'file_id',
                    'avg_rating',
                    'from_date',
                    'to_date'
                ],
                'safe'
            ],
            [
                [
                    'gender',
                    'tos',
                    'role_id',
                    'state_id',
                    'designation',
                    'type_id',
                    'login_error_count',
                    'created_by_id',
                    'step',
                    'otp_attempt',
                    'is_approve',
                    'work_type',
                    'experience',
                    'age'
                ],
                'integer'
            ],
            [
                [
                    'full_name',
                    'email',
                    'about_me',
                    'contact_no',
                    'city',
                    'country',
                    'zipcode',
                    'language',
                    'profile_file',
                    'timezone'
                ],
                'string',
                'max' => 255
            ],

            [
                [
                    'password',
                    'activation_key'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'address',
                    'latitude',
                    'longitude'
                ],
                'string',
                'max' => 512
            ],
            [
                'verifyCode',
                'captcha',

                'on' => 'signup'
            ],
            [
                [
                    'password',
                    'oldPassword'
                ],
                'required',
                'on' => 'apichangepassword'
            ],
            [
                'oldPassword',
                'findPasswords',
                'on' => [
                    'apichangepassword'
                ]
            ],
            [
                [
                    'email'
                ],
                'required',
                'on' => 'token_request'
            ]
        ];
    }

    public function getAge()
    {
        $birth_date = $this->date_of_birth;
        if (empty($birth_date)) {
            return self::STATE_INACTIVE;
        }
        $age = date("Y") - date("Y", strtotime($birth_date));
        return $age;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $action = \Yii::$app->controller->action->id;
        if ($action == 'matches-list') {
            return $this->asProviderJson();
        }
        $json['id'] = $this->id;
        $json['full_name'] = $this->full_name;
        $json['email'] = $this->email;
        $json['date_of_birth'] = $this->date_of_birth;
        $json['age'] = $this->getAge();
        $json['identity_number'] = $this->identity_number;
        $json['gender'] = $this->gender;
        $json['about_me'] = $this->about_me;
        $json['contact_no'] = $this->contact_no;
        $json['address'] = $this->address;
        $json['latitude'] = $this->latitude;
        $json['longitude'] = $this->longitude;
        $json['city'] = $this->city;
        $json['country'] = $this->country;
        $json['zipcode'] = $this->zipcode;
        $json['language'] = $this->language;
        $json['role_id'] = $this->role_id;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['step'] = $this->step;
        $json['last_action_time'] = $this->last_action_time;
        $json['timezone'] = $this->timezone;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['otp'] = $this->otp;
        $json['otp_verified'] = $this->otp_verified;
        $json['is_notify'] = $this->is_notify;
        $json['rating'] = $this->avg_rating;
        $json['otp_attempt'] = $this->otp_attempt;
        $json['medical_report'] = ! empty($this->medicalFile) ? $this->medicalFile->getImageUrl() : '';
        $json['profile_file'] = ! empty($this->profile_file) ? $this->getImageUrl() : '';
        $json['rating_count'] = $this->getRatingCount();
        return $json;
    }

    public function asProviderJson($with_relations = false, $files = true)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['full_name'] = $this->full_name;
        $json['email'] = $this->email;
        $json['date_of_birth'] = $this->date_of_birth;
        $json['identity_number'] = $this->identity_number;
        $json['gender'] = $this->gender;
        $json['about_me'] = $this->about_me;
        $json['contact_no'] = $this->contact_no;
        $json['address'] = $this->address;
        $json['latitude'] = $this->latitude;
        $json['longitude'] = $this->longitude;
        $json['city'] = $this->city;
        $json['country'] = $this->country;
        $json['zipcode'] = $this->zipcode;
        $json['language'] = $this->language;
        $json['experience'] = $this->experience;
        $json['role_id'] = $this->role_id;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['step'] = $this->step;
        $json['last_action_time'] = $this->last_action_time;
        $json['timezone'] = $this->timezone;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        $json['otp'] = $this->otp;
        $json['otp_verified'] = $this->otp_verified;
        $json['age'] = $this->getAge();
        $json['otp_attempt'] = $this->otp_attempt;
        $json['profile_file'] = ! empty($this->profile_file) ? $this->getImageUrl() : '';
        $json['qualification_file'] = ! empty($this->providerFile) ? $this->providerFile->getImageUrl() : '';
        $json['qualification_file_name'] = ! empty($this->providerFile) ? $this->providerFile->name : '';
        $json['languages'] = $this->getSelectedLanguages();
        $json['qualifications'] = $this->getSelectedQualifications();
        $json['skills'] = $this->getSelectedSkills();
        $json['work_zone'] = $this->getSelectedWorkzones();
        $json['work_zone_title'] = $this->getSelectedWorkzones(true, true);
        $json['rating'] = $this->avg_rating;
        $json['rating_count'] = $this->getRatingCount();
        if ($files) {
            $list = $this->providerFiles;
            if (is_array($list)) {
                $relationData = array_map(function ($item) {
                    return $item->asJson();
                }, $list);

                $json['files'] = $relationData;
            } else {
                $json['files'] = $list;
            }
        }
        if ($with_relations) {}
        return $json;
    }

    public function getRatingCount()
    {
        return Rating::find()->where([
            'provider_id' => $this->id
        ])->count();
    }

    public static function getRelativeTime($time)
    {
        return Yii::$app->formatter->asRelativeTime($time);
    }

    public function asBookingJson()
    {
        $json = [];
        $json['id'] = $this->id;
        $json['full_name'] = $this->full_name;
        $json['identity_number'] = $this->identity_number;
        $json['date_of_birth'] = $this->date_of_birth;
        $json['address'] = $this->address;
        $json['zipcode'] = $this->zipcode;
        $json['gender'] = $this->gender;
        $json['experience'] = $this->experience;
        $json['age'] = $this->getAge();
        $json['contact_no'] = $this->contact_no;
        $json['profile_file'] = ! empty($this->profile_file) ? $this->getImageUrl() : '';
        $json['qualification_title'] = $this->getSelectedQualifications(true, true);
        $json['rating'] = $this->avg_rating;
        return $json;
    }

    public function asServiceJson()
    {
        $json = [];
        $json['id'] = $this->id;
        $json['full_name'] = $this->full_name;
        $json['email'] = $this->email;
        $json['date_of_birth'] = $this->date_of_birth;
        $json['gender'] = $this->gender;
        $json['age'] = $this->getAge();
        $json['experience'] = $this->experience;
        $json['profile_file'] = ! empty($this->profile_file) ? $this->getImageUrl() : '';
        $json['language_title'] = $this->getSelectedLanguages(true, true);
        $json['qualification_title'] = $this->getSelectedQualifications(true, true);
        $json['skill_title'] = $this->getSelectedSkills(true, true, true);
        $json['work_zone_title'] = $this->getSelectedWorkzones(true, true);
        $json['rating'] = $this->avg_rating;
        return $json;
    }

    public function getSelectedLanguages($arr = false, $title = false)
    {
        $language = $this->getLanguages()
            ->select('language_id')
            ->column();
        if ($arr) {
            if ($title) {
                $language = $this->getlanguageTitle($language);
                return implode(',', $language ?? '');
            }
            return $language;
        }
        return implode(',', $language ?? '');
    }

    public function getlanguageTitle($language_ids)
    {
        return Language::findActive()->select('title')
            ->andWhere([
            'in',
            'id',
            $language_ids
        ])
            ->column();
    }

    public function getSelectedQualifications($arr = false, $title = false)
    {
        $qualifications = $this->getQualifications()
            ->select('category_id')
            ->column();
        if ($arr) {
            if ($title) {
                $qualifications = $this->getQualificationTitle($qualifications);
                return implode(',', $qualifications ?? '');
            }
            return $qualifications;
        }
        return implode(',', $qualifications ?? '');
    }

    public function getQualificationTitle($qualification_ids)
    {
        return Category::findActive()->select('title')
            ->andWhere([
            'in',
            'id',
            $qualification_ids
        ])
            ->column();
    }

    public function getSelectedWorkzones($arr = false, $title = false)
    {
        $workzone = $this->getWorkzones()
            ->select('workzone_id')
            ->column();
        if ($arr) {
            if ($title) {
                $workzone = $this->getWorkzoneTitle($workzone);
                return implode(',', $workzone ?? '');
            }
            return $workzone;
        }
        return implode(',', $workzone ?? '');
    }

    public function getWorkzoneTitle($workzone_ids)
    {
        return Location::findActive()->select('title')
            ->andWhere([
            'in',
            'id',
            $workzone_ids
        ])
            ->column();
    }

    public function getSelectedSkills($arr = false, $title = false, $detail = false)
    {
        $skills = $this->getSkills()
            ->select('skill_id')
            ->column();
        if ($arr) {
            if ($title) {
                $skills = $this->getSkillTitle($skills);
                if ($detail) {
                    return implode(',,', $skills ?? '');
                }
                return implode(',', $skills ?? '');
            }
            return $skills;
        }
        return implode(',', $skills ?? '');
    }

    public function getSkillTitle($skill_ids)
    {
        return ProviderSkill::findActive()->select('title')
            ->andWhere([
            'in',
            'id',
            $skill_ids
        ])
            ->column();
    }

    /**
     *
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
            'state_id' => self::STATE_ACTIVE
        ]);
    }

    public static function getFirebaseKey()
    {
        return \Yii::$app->settings->getValue('firebase_key', null, 'notification');
    }

    public static function getMerchantCode()
    {
        return \Yii::$app->settings->getValue('merchant_code', null, 'settings');
    }

    public static function getMerchantKey()
    {
        return \Yii::$app->settings->getValue('merchant_key', null, 'settings');
    }

    public function checkOtpExpireTime()
    {
        $time = 3;
        if ($this->otp_attempt > self::OTP_ATTEMPT_TWO) {
            $time = 30;
        }

        if (strtotime($this->last_action_time . "+" . $time . " minute") > strtotime(date("Y-m-d H:i:s"))) {
            return true;
        }
        return false;
    }

    public function deleteOldFile()
    {
        $array = explode(',', $this->file_id);
        foreach ($array as $id) {
            if (! empty($id)) {
                $file = File::findOne($id);
                if (! empty($file)) {
                    $file->delete();
                }
            }
        }
        return true;
    }

    public function deleteOldMedicalFile()
    {
        File::deleteAll([
            'model_id' => $this->id,
            'file_type' => File::FILE_TYPE_MEDICAL_REPORT
        ]);
        return true;
    }

    public function saveQualifications($qualifications)
    {
        foreach ($qualifications as $qualification) {
            $cat_model = Category::findOne($qualification);
            if (! empty($cat_model)) {
                $category = new UserCategory();
                $category->category_id = $qualification;
                $category->title = $cat_model->title;
                $category->type_id = $cat_model->type_id;
                $category->created_by_id = $this->id;
                if (! $category->save()) {
                    return false;
                }
            }
        }
        return true;
    }

    public function saveSkills($skills)
    {
        foreach ($skills as $skill) {
            $skill_model = ProviderSkill::findOne($skill);
            if (! empty($skill_model)) {
                $sub_category = new UserSkill();
                $sub_category->category_id = $skill_model->category_id;
                $sub_category->skill_id = $skill;
                $sub_category->parent_skill_id = $skill_model->parent_id;
                $sub_category->title = $skill_model->title;
                $sub_category->created_by_id = $this->id;
                if (! $sub_category->save()) {
                    return false;
                }
            }
        }
        return true;
    }

    public function saveLanguages($languages)
    {
        foreach ($languages as $language) {
            $language_model = Language::findOne($language);
            if (! empty($language_model)) {
                $user_lang = new UserLanguage();
                $user_lang->language_id = $language;
                $user_lang->code = $language_model->code;
                $user_lang->title = $language_model->title;
                $user_lang->created_by_id = $this->id;
                if (! $user_lang->save()) {
                    return false;
                }
            }
        }
        return true;
    }

    public function saveWorkzones($workzones)
    {
        foreach ($workzones as $workzone) {
            $workzone_model = Location::findOne($workzone);
            if (! empty($workzone_model)) {
                $user_workzone = new UserWorkzone();
                $user_workzone->workzone_id = $workzone_model->id;
                $user_workzone->title = $workzone_model->title;
                $user_workzone->created_by_id = $this->id;
                if (! $user_workzone->save()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     *
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne([
            'activation_key' => $token,
            'state_id' => self::STATE_ACTIVE
        ]);
    }

    /**
     * Return users count
     *
     * @return number
     */
    public static function getUsersCount($type, $step = true, $gender = '')
    {
        $query = User::findActive()->andWhere([
            'role_id' => User::ROLE_USER,
            'type_id' => $type
        ]);
        if ($step) {
            $query->andWhere([
                'step' => User::STEP_THREE
            ]);
        }
        if (is_numeric($gender)) {
            $query->andWhere([
                'gender' => $gender
            ]);
        }
        $object = new self();
        $from_date = $object->getDateRangeValue('from_date');
        $to_date = $object->getDateRangeValue('to_date');
        if (! empty($from_date) && ! empty($to_date)) {
            $query->andWhere([
                'and',
                [
                    '>=',
                    "date(created_on)",
                    $from_date
                ],
                [
                    '<=',
                    "date(created_on)",
                    $to_date
                ]
            ]);
        }

        return $query->count();
    }

    /**
     * Return incompleted users profiles count
     *
     * @return number
     */
    public static function getIncompleteUsersCount()
    {
        $query = User::find()->where([
            '!=',
            'step',
            User::STEP_THREE
        ])->andWhere([
            'and',
            [
                'type_id' => User::TYPE_USER
            ],
            [

                '!=',
                'role_id',
                User::ROLE_ADMIN
            ]
        ]);

        $object = new self();
        $from_date = $object->getDateRangeValue('from_date');
        $to_date = $object->getDateRangeValue('to_date');
        if (! empty($from_date) && ! empty($to_date)) {
            $query->andWhere([
                'and',
                [
                    '>=',
                    "date(created_on)",
                    $from_date
                ],
                [
                    '<=',
                    "date(created_on)",
                    $to_date
                ]
            ]);
        }

        return $query->count();
    }

    /**
     * Return users count
     *
     * @return number
     */
    public static function getProvidersCount($approval, $step = true, $gender = '')
    {
        $query = User::findActive()->andWhere([
            'role_id' => User::ROLE_SERVICE_PROVIDER,
            'is_approve' => $approval
        ]);
        if ($step) {
            $query->andWhere([
                'step' => User::STEP_THREE
            ]);
        }
        if (is_numeric($gender)) {
            $query->andWhere([
                'gender' => $gender
            ]);
        }
        $object = new self();
        $from_date = $object->getDateRangeValue('from_date');
        $to_date = $object->getDateRangeValue('to_date');
        if (! empty($from_date) && ! empty($to_date)) {
            $query->andWhere([
                'and',
                [
                    '>=',
                    "date(created_on)",
                    $from_date
                ],
                [
                    '<=',
                    "date(created_on)",
                    $to_date
                ]
            ]);
        }

        return $query->count();
    }

    /**
     * Return logs count
     *
     * @return number
     */
    public static function getLogCount()
    {
        $query = Log::find();
        $object = new self();
        $from_date = $object->getDateRangeValue('from_date');
        $to_date = $object->getDateRangeValue('to_date');
        if (! empty($from_date) && ! empty($to_date)) {
            $query->andWhere([
                'and',
                [
                    '>=',
                    "date(created_on)",
                    $from_date
                ],
                [
                    '<=',
                    "date(created_on)",
                    $to_date
                ]
            ]);
        }

        return $query->count();
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email,
            'state_id' => self::STATE_ACTIVE
        ]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public function getUsername()
    {
        return substr($this->email, 0, strpos($this->email, '@'));
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findByEmail($username);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token
     *            password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (! static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'activation_key' => $token,
            'state_id' => self::STATE_ACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token
     *            password reset token
     * @return boolean
     */
    public function getResetUrl()
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            'user/resetpassword',
            'token' => $this->activation_key
        ]);
    }

    public function getVerified()
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            'user/confirm-email',
            'id' => $this->activation_key
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     *
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     *
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->activation_key;
    }

    /**
     *
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $this->hashPassword($password);
    }

    public function hashPassword($password)
    {
        $password = utf8_encode(Yii::$app->security->generatePasswordHash(yii::$app->id . $password));
        return $password;
    }

    /**
     * Validates password
     *
     * @param string $password
     *            password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword(yii::$app->id . $password, utf8_decode($this->password));
    }

    /**
     * convert normal password to hash password before saving it to database
     */

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->activation_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->activation_key = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->activation_key = null;
    }

    public static function isServiceProvider()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_SERVICE_PROVIDER);
    }

    public static function isUser()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_USER || self::isManager());
    }

    public static function isManager()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_MANAGER || self::isAdmin());
    }

    public function isSelf()
    {
        if ($this->id == Yii::$app->user->identity->id)
            return true;

        return false;
    }

    public static function isAdmin()
    {
        $user = Yii::$app->user->identity;
        if ($user == null)
            return false;

        return ($user->isActive() && $user->role_id == User::ROLE_ADMIN);
    }

    public static function isGuest()
    {
        if (Yii::$app->user->isGuest) {
            return true;
        }
        return false;
    }

    public function isActive()
    {
        return ($this->state_id == User::STATE_ACTIVE);
    }

    public function getFeeds()
    {
        return $this->hasMany(Feed::className(), [
            'created_by_id' => 'id'
        ]);
    }

    /**
     * return true if manual apporval button is visible
     *
     * @return boolean
     */
    public function checkIsApproveButtonVisible()
    {
        return ($this->role_id == self::ROLE_SERVICE_PROVIDER && $this->is_approve != self::IS_APPROVE) ? true : false;
    }

    public function sendRegistrationMailtoUser()
    {
        $message = TEmailTemplateHelper::renderFile('sendPassword', [
            'user' => $this
        ]);
        $sub = "Welcome! You new account is ready " . \Yii::$app->name;

        EmailQueue::add([
            'to' => $this->email,
            'subject' => $sub,
            'html' => $message
        ], true);
    }

    public function sendVerificationMailtoUser()
    {
        $sub = "Welcome! Your new account is ready for " . \Yii::$app->params['company'];
        $message = TEmailTemplateHelper::renderFile('@app/mail/sendOtp.php', [
            'user' => $this
        ]);
        $from = \Yii::$app->params['adminEmail'];
        EmailQueue::add([
            'from' => $from,
            'to' => $this->email,
            'subject' => $sub,
            'html' => $message
        ], true);
    }

    public function sendVerificationResendOtpMailtoUser()
    {
        $sub = "Welcome! Your new One Time Password is given below";
        $message = TEmailTemplateHelper::renderFile('@app/mail/resendOtp.php', [
            'user' => $this
        ]);
        $from = \Yii::$app->params['adminEmail'];
        EmailQueue::add([
            'from' => $from,
            'to' => $this->email,
            'type_id' => EmailQueue::TYPE_KEEP_AFTER_SEND,
            'subject' => $sub,
            'html' => $message
        ], true);
    }

    public function getManageRedirectUrl()
    {
        $action = 'index';
        if ($this->role_id == User::ROLE_USER) {
            if ($this->type_id == User::TYPE_DEPENDENT) {
                return 'dependent-user';
            }
            $action = ($this->step == User::STEP_THREE) ? 'index' : 'incomplete';
        } else {
            if ($this->step == User::STEP_THREE) {

                if ($this->is_approve == User::APPROVAL_PENDING) {
                    $action = 'provider-approval';
                } else {
                    $action = ($this->is_approve == User::IS_REJECT) ? 'rejected' : 'provider';
                }
            } else {
                $action = 'incomplete';
            }
        }
        return $action;
    }

    public function getLoginUrl()
    {
        return Yii::$app->urlManager->createAbsoluteUrl([
            'user/login'
        ]);
    }

    public function getImageUrl($thumbnail = false)
    {
        $params = [
            '/' . $this->getControllerID() . '/image'
        ];
        $params['id'] = $this->id;

        if (isset($this->profile_file) && ! empty($this->profile_file)) {
            $params['file'] = $this->profile_file;
        }

        if ($thumbnail)
            $params['thumbnail'] = is_numeric($thumbnail) ? $thumbnail : 150;
        return Url::toRoute($params);
    }

    public function isAllowed()
    {
        if (User::isAdmin()) {
            return true;
        }

        return parent::isAllowed();
    }

    public static function findByContact($contact)
    {
        return static::findOne([
            'contact_no' => $contact
        ]);
    }

    public static function daily($state = null, $role = null, $created_by_id = null, $dateAttribute = 'created_on')
    {
        $date = new \DateTime();
        $date->modify('-30 days');

        $count = [];
        $query = User::find()->cache(60);
        for ($i = 1; $i <= 30; $i ++) {
            $date->modify('+1 days');
            $day = $date->format('Y-m-d');

            $query->where([
                "DATE($dateAttribute)" => $day
            ]);
            if ($created_by_id !== null) {
                $query->andWhere([
                    'created_by_id' => $created_by_id
                ]);
            }
            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }
            if ($role !== null) {

                $query->andWhere([
                    'role_id' => $role,
                    'type_id' => User::TYPE_USER,
                    'step' => User::STEP_THREE
                ]);
                if ($role == User::ROLE_SERVICE_PROVIDER) {

                    $query->andWhere([
                        'is_approve' => User::IS_APPROVE
                    ]);
                }
            }
            $count[$day] = (int) $query->count();
        }
        return $count;
    }

    public static function weekly($state = null, $role = null, $created_by_id = null, $dateAttribute = 'created_on')
    {
        $date = new \DateTime();
        $date->modify('-7 days ');
        $count = [];
        $query = User::find()->cache(30);
        for ($i = 1; $i <= 7; $i ++) {
            $date->modify('1 days');
            $day = $date->format('Y-m-d');
            $query->where([
                "DATE($dateAttribute)" => $day
            ]);
            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }
            if ($role !== null) {
                $query->andWhere([

                    'role_id' => $role,
                    'type_id' => User::TYPE_USER,
                    'step' => User::STEP_THREE
                ]);
                if ($role == User::ROLE_SERVICE_PROVIDER) {
                    $query->andWhere([
                        'is_approve' => User::IS_APPROVE
                    ]);
                }
            }
            $count[$day] = (int) $query->count();
        }
        return $count;
    }

    public static function monthly($state = null, $role = null, $created_by_id = null, $dateAttribute = 'created_on')
    {
        $date = new \DateTime(date('Y-m'));

        $date->modify('-1 year');

        $count = [];
        $query = User::find()->cache(10);
        for ($i = 1; $i <= 12; $i ++) {
            $date->modify('+1 months');
            $year = $date->format('Y');
            $month = $date->format('m');

            $query->where([
                "MONTH($dateAttribute)" => $month,
                "YEAR($dateAttribute)" => $year
            ]);
            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }
            if ($role !== null) {

                $query->andWhere([

                    'role_id' => $role,
                    'type_id' => User::TYPE_USER,
                    'step' => User::STEP_THREE
                ]);
                if ($role == User::ROLE_SERVICE_PROVIDER) {
                    $query->andWhere([
                        'is_approve' => User::IS_APPROVE
                    ]);
                }
            }

            $count[$month] = (int) $query->count();
        }
        return $count;
    }

    public static function yearly($role = null, $state = null, $dateAttribute = 'created_on')
    {
        $date = new \DateTime(date('Y'));
        $date->modify('-12 year');

        $count = [];
        $query = User::find()->cache(10);
        for ($i = 1; $i <= 12; $i ++) {
            $date->modify('+1 years');
            $year = $date->format('Y');

            $query->where([
                'like',
                $dateAttribute,
                $year
            ]);

            if ($state !== null) {
                $state = is_array($state) ? $state : [
                    $state
                ];
                $query->andWhere([
                    'in',
                    'state_id',
                    $state
                ]);
            }
            if ($role !== null) {

                $query->andWhere([
                    'role_id' => $role,
                    'type_id' => User::TYPE_USER,
                    'step' => User::STEP_THREE
                ]);
                if ($role == User::ROLE_SERVICE_PROVIDER) {
                    $query->andWhere([
                        'is_approve' => User::IS_APPROVE
                    ]);
                }
            }
            $count[$year] = (int) $query->count();
        }
        return $count;
    }

    public static function addData($data)
    {
        $faker = \Faker\Factory::create();
        if (self::find()->count() != 0)
            return;
        foreach ($data as $item) {
            $model = new self();

            $model->full_name = isset($item['full_name']) ? $item['full_name'] : $faker->name;

            $model->email = isset($item['email']) ? $item['email'] : $faker->email;

            $model->date_of_birth = isset($item['date_of_birth']) ? $item['date_of_birth'] : $faker->date($format = 'Y-m-d', $max = 'now');

            $model->gender = isset($item['gender']) ? $item['gender'] : 0;
            $model->password = isset($item['password']) ? $item['password'] : 'admin';

            $model->about_me = isset($item['about_me']) ? $item['about_me'] : $faker->text(10);

            $model->contact_no = isset($item['contact_no']) ? $item['contact_no'] : $faker->text(10);

            $model->address = isset($item['address']) ? $item['address'] : $faker->text(10);

            $model->latitude = isset($item['latitude']) ? $item['latitude'] : $faker->text(10);

            $model->longitude = isset($item['longitude']) ? $item['longitude'] : $faker->text(10);

            $model->city = isset($item['city']) ? $item['city'] : $faker->text(10);

            $model->country = isset($item['country']) ? $item['country'] : $faker->text(10);

            $model->zipcode = isset($item['zipcode']) ? $item['zipcode'] : 1;

            $model->language = isset($item['language']) ? $item['language'] : $faker->text(10);

            $model->profile_file = isset($item['profile_file']) ? $item['profile_file'] : $faker->text(10);

            $model->tos = isset($item['tos']) ? $item['tos'] : 1;

            $model->role_id = isset($item['role_id']) ? $item['role_id'] : 1;
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;

            $model->last_visit_time = isset($item['last_visit_time']) ? $item['last_visit_time'] : $faker->date($format = 'Y-m-d', $max = 'now');

            $model->last_action_time = isset($item['last_action_time']) ? $item['last_action_time'] : $faker->date($format = 'Y-m-d', $max = 'now');

            $model->last_password_change = isset($item['last_password_change']) ? $item['last_password_change'] : $faker->date($format = 'Y-m-d', $max = 'now');

            $model->login_error_count = isset($item['login_error_count']) ? $item['login_error_count'] : $faker->numberBetween();

            // $model->timezone = isset($item['timezone']) ? $item['timezone'] : $faker->text(10);

            $model->setPassword($model->password);

            $model->save();
        }
    }

    public static function findByUsercontact($username)
    {
        return static::find()->where([
            'contact_no' => $username
        ])->one();
    }
}
