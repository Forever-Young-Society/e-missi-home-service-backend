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
namespace app\modules\api\controllers;

use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\File;
use app\models\LoginForm;
use app\models\User;
use app\models\UserCategory;
use app\models\UserLanguage;
use app\models\UserSkill;
use app\models\UserWorkzone;
use app\modules\api\components\ApiBaseController;
use app\modules\api\models\AccessToken;
use app\modules\contact\models\Information;
use app\modules\faq\models\Faq;
use app\modules\notification\models\Notification;
use app\modules\page\models\Page;
use app\modules\rating\models\Rating;
use app\modules\smtp\models\EmailQueue;
use app\modules\workzone\models\Location;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\UploadedFile;

/**
 * UserController implements the API actions for User model.
 */
class UserController extends ApiBaseController
{

    public $modelClass = "app\models\User";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class
                ],
                'rules' => [
                    [
                        'actions' => [
                            'check',
                            'logout',
                            'change-password',
                            'detail',
                            'get-page',
                            'add-dependent',
                            'update-profile-user',
                            'notification-switch',
                            'notification-list',
                            'notification-clear',
                            'faq-list',
                            'add-rating',
                            'dependent-list',
                            'dependent-detail',
                            'remove-dependent',
                            'update-dependent',
                            'delete-account'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ],
                    [
                        'actions' => [
                            'check',
                            'logout',
                            'change-password',
                            'detail',
                            'get-page',
                            'add-dependent',
                            'notification-switch',
                            'notification-list',
                            'notification-clear',
                            'add-rating',
                            'provider-detail',
                            'update-profile-provider',
                            'location-list',
                            'delete-account',
                            'faq-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isServiceProvider();
                        }
                    ],
                    [
                        'actions' => [
                            'signup',
                            'login',
                            'resend-otp',
                            'verify-otp',
                            'forgot-password',
                            'work-zone-list',
                            'complete-provider-signup',
                            'complete-signup',
                            'change-password',
                            'get-page',
                            'location-list',
                            'faq-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isGuest();
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     *
     * @OA\Post(path="/user/signup",
     *   summary="",
     *   tags={"User"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(property="User[contact_no]", type="string", example="12345678",description="contact of user"),
     *              @OA\Property(property="User[country_code]", type="string", example="91",description="country_code of user"),
     *              @OA\Property(property="User[role_id]", type="integer", example="2",description="For User role is 2, provider 3"),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionSignup()
    {
        $data = [];
        $this->setStatus(400);
        $model = new User();
        $model->loadDefaultValues();
        $model->state_id = User::STATE_ACTIVE;
        $model->email_verified = User::EMAIL_VERIFIED;
        $model->otp_verified = User::OTP_NOT_VERIFIED;
        $model->step = User::STEP_ONE;
        $model->otp_attempt = User::OTP_ATTEMPT_ONE;
        $model->country_code = User::DEFAULT_COUNTRY_CODE;
        $model->otp = rand(111111, 999999);
        // $model->otp = '1234';
        if ($model->load(Yii::$app->request->post())) {
            $contact_identify = User::findByContact($model->contact_no);
            if (empty($contact_identify)) {
                $model->setPassword(time());
                $model->generateAuthKey();
                $model->last_action_time = date('Y-m-d H:i:s');
                if ($model->save()) {
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', "Verification code send successfully");
                    $data['detail'] = $model->asJson();
                } else {
                    $data['message'] = $model->getErrors();
                }
            } else {
                if ($contact_identify->role_id != $model->role_id) {
                    $data['message'] = \Yii::t('app', "This mobile number has already sign up, please proceed to sign in.");
                    return $data;
                }
                if ($contact_identify->step != User::STEP_THREE) {
                    $contact_identify->otp = rand(111111, 999999);
                    // $contact_identify->otp = '1234';
                    $contact_identify->last_action_time = date('Y-m-d H:i:s');
                    $contact_identify->updateAttributes([
                        'otp',
                        'last_action_time'
                    ]);
                    $this->setStatus(200);
                    $data['message'] = \Yii::t('app', "Profile setup is pending");
                    $data['detail'] = $contact_identify->asJson();
                    return $data;
                }
                $data['message'] = \yii::t('app', "This mobile number has already sign up, please proceed to sign in.");
            }
        } else {
            $data['message'] = \Yii::t('app', "Data not posted.");
        }

        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/delete-account",
     *   summary="Delete user account",
     *   tags={"User"},
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Delete user account",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionDeleteAccount()
    {
        $this->setStatus(400);
        $data = [];
        $model = \Yii::$app->user->identity;
        if (! empty($model)) {
            $model->updateAttributes([
                'state_id' => User::STATE_USER_DELETED
            ]);
            $this->setStatus(200);
            $data['message'] = \Yii::t('app', 'Account Deleted Successfully');
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/forgot-password",
     *   summary="Forgot Password",
     *   tags={"User"},
     *   *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={
     *              "User[contact_no]"
     *              },
     *              @OA\Property(
     *              property="User[contact_no]",
     *              type="contact_no", format="string",
     *              example="987654321",
     *              description="Enter Email"
     *              ),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionForgotPassword()
    {
        $data = [];
        $this->setStatus(400);
        $model = new User();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $contact = $model->contact_no;
            if ($contact != null) {
                $user = User::findByContact($contact);

                if ($user) {
                    if ($user->step != User::STEP_THREE) {
                        $data['message'] = \Yii::t('app', "Please complete your profile setup first");
                        return $data;
                    }
                    $user->generatePasswordResetToken();
                    if (! $user->save()) {
                        throw new HttpException("Cant Generate Authentication Key");
                    }
                    $this->setStatus(200);
                    $user->otp = rand(111111, 999999);
                    // $user->otp = '1234';
                    $user->otp_attempt = User::OTP_ATTEMPT_ONE;
                    $user->last_action_time = date('Y-m-d H:i:s');
                    $user->updateAttributes([
                        'otp',
                        'last_action_time',
                        'otp_attempt'
                    ]);
                    $data['otp'] = $user->otp;
                    $data['detail'] = $user->asJson();
                    $data['message'] = \Yii::t('app', "Verification code send successfully");
                } else {
                    $data['message'] = \Yii::t('app', "Contact number is not registered.");
                }
            } else {
                $data['message'] = \Yii::t('app', "Email cannot be blank");
            }
        } else {
            $data['message'] = \Yii::t('app', "Data Not Posted");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/complete-signup",
     *   summary="",
     *   tags={"User"},
     *   @OA\Parameter(
     *     name="contact_no",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[email]","User[password]"},
     *              @OA\Property(property="User[full_name]", type="string", example="David",description="first name"),
     *              @OA\Property(property="User[email]", type="string", format="email", example="test@mail.com",description="Email of the user"),
     *              @OA\Property(property="User[password]", type="string", format="password", example="admin123",description="Password"),
     *              @OA\Property(property="User[identity_number]", type="string", example="12345678",description="Identification number of user"),
     *              @OA\Property(property="User[date_of_birth]", type="string", example="1998-11-21",description="User date of birth"),
     *              @OA\Property(property="User[zipcode]", type="integer", example="160071",description="User date of birth"),
     *              @OA\Property(property="User[about_me]", type="string", example="",description="User medical condition"),
     *              @OA\Property(property="User[address]", type="string", example="India",description="Address of user"),
     *              @OA\Property(property="User[latitude]", type="string", example="79.364222354",description="Address latitude"),
     *              @OA\Property(property="User[longitude]", type="string", example="30.3642078",description="Address longitude"),
     *              @OA\Property(property="LoginForm[device_token]", type="string", example="263623dsafsdf",description="device token"),
     *              @OA\Property(property="LoginForm[device_name]", type="string", example="android",description="android / ios"),
     *              @OA\Property(property="LoginForm[device_type]", type="string",example="1",description="device type"),
     *              @OA\Property(property="File[key]", type="file",example="",description="select file")
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionCompleteSignup($contact_no)
    {
        $data = [];
        $this->setStatus(400);
        $loginForm = new LoginForm();
        $fileModel = new File();
        $post = \Yii::$app->request->post();
        $model = User::findByContact($contact_no);
        if (empty($model)) {
            $data['message'] = \Yii::t('app', "User not found.");
            return $data;
        }
        $model->loadDefaultValues();
        $model->state_id = User::STATE_ACTIVE;
        if ($model->load($post) && $loginForm->load($post)) {
            // $email_identify = User::findByEmail($model->email);
            // if (empty($email_identify)) {
            $model->setPassword($model->password);
            $model->generateAuthKey();
            $model->step = User::STEP_THREE;
            if ($model->save()) {
                if ($_FILES) {
                    // medical report documents
                    $files = UploadedFile::getInstances($fileModel, 'key');
                    foreach ($files as $file) {
                        if (! empty($file)) {
                            File::add($model, $file, null, File::FILE_TYPE_MEDICAL_REPORT);
                        }
                    }
                }
                \Yii::$app->user->login($model);
                AccessToken::add($loginForm, $model->getAuthKey());
                $this->setStatus(200);
                $model->sendRegistrationMailtoAdmin();
                $data['access-token'] = $model->getAuthKey();
                $data['detail'] = $model->asJson();
                $data['message'] = \yii::t('app', "Profile setup successfully.");
            } else {
                $data['message'] = $model->getErrors();
            }
            // } else {
            // $data['message'] = \yii::t('app', "Email has already been taken.");
            // }
        } else {
            $data['message'] = \Yii::t('app', "Data not posted.");
        }

        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/complete-provider-signup",
     *   summary="",
     *   tags={"User"},
     *   @OA\Parameter(
     *     name="contact_no",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[email]","User[password]"},
     *              @OA\Property(property="User[full_name]", type="string", example="David",description="first name"),
     *              @OA\Property(property="User[email]", type="string", format="email", example="test@mail.com",description="Email of the user"),
     *              @OA\Property(property="User[password]", type="string", format="password", example="admin123",description="Password"),
     *              @OA\Property(property="User[identity_number]", type="string", example="12345678",description="Identification number of user"),
     *              @OA\Property(property="User[date_of_birth]", type="string", example="1998-11-21",description="User date of birth"),
     *              @OA\Property(property="User[zipcode]", type="integer", example="160071",description="User date of birth"),
     *              @OA\Property(property="User[about_me]", type="string", example="",description="User medical condition"),
     *              @OA\Property(property="User[experience]", type="integer", example="",description="Service Provider Experience"),
     *              @OA\Property(property="User[gender]", type="integer", example="",description="Service Provider Gender"),
     *              @OA\Property(property="User[address]", type="string", example="India",description="Address of user"),
     *              @OA\Property(property="User[qualification]", type="string", example="India",description="User qualifications"),
     *              @OA\Property(property="User[service_language]", type="string", example="1,2,3",description="Languages of user"),
     *              @OA\Property(property="User[work_zone]", type="string", example="1,2,3",description="Languages of user"),
     *              @OA\Property(property="User[skill]", type="string", example="India",description="Skills of user"),
     *              @OA\Property(property="LoginForm[device_token]", type="string", example="263623dsafsdf",description="device token"),
     *              @OA\Property(property="LoginForm[device_name]", type="string", example="android",description="android / ios"),
     *              @OA\Property(property="LoginForm[device_type]", type="string",example="1",description="device type"),
     *              @OA\Property(property="User[profile_file]", type="file", format="file", example="",description="Select image"),
     *              @OA\Property(description="Add multiple file",property="File[key][]",type="array", @OA\Items(type="file",format="binary")),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionCompleteProviderSignup($contact_no)
    {
        $data = [];
        $this->setStatus(400);
        $loginForm = new LoginForm();
        $fileModel = new File();
        $post = \Yii::$app->request->post();
        $model = User::findByContact($contact_no);
        if (empty($model)) {
            $data['message'] = \Yii::t('app', "User not found.");
            return $data;
        }
        $model->loadDefaultValues();
        $model->state_id = User::STATE_ACTIVE;
        $transaction = \Yii::$app->db->beginTransaction();
        if ($model->load($post) && $loginForm->load($post)) {
            try {
                // $email_identify = User::findByEmail($model->email);
                // if (empty($email_identify)) {
                $model->setPassword($model->password);
                $model->generateAuthKey();
                $model->step = User::STEP_THREE;
                $model->saveUploadedFile($model, 'profile_file');
                if ($model->save()) {

                    // add provider qualification
                    $qualifications = explode(',', $model->qualification);
                    if (is_array($qualifications)) {
                        UserCategory::deleteAll([
                            'created_by_id' => $model->id
                        ]);
                        if (! $model->saveQualifications($qualifications)) {
                            $data['message'] = \yii::t('app', "Unable to save qualification");
                            $transaction->rollBack();
                            return $data;
                        }
                    }

                    // add provider skills
                    $skills = explode(',', $model->skill);
                    if (is_array($skills)) {
                        UserSkill::deleteAll([
                            'created_by_id' => $model->id
                        ]);
                        if (! $model->saveSkills($skills)) {
                            $data['message'] = \yii::t('app', "Unable to save skills");
                            $transaction->rollBack();
                            return $data;
                        }
                    }

                    // add languages
                    $languages = explode(',', $model->service_language);
                    if (is_array($languages)) {
                        UserLanguage::deleteAll([
                            'created_by_id' => $model->id
                        ]);
                        if (! $model->saveLanguages($languages)) {
                            $data['message'] = \yii::t('app', "Unable to save languages");
                            $transaction->rollBack();
                            return $data;
                        }
                    }

                    // add workzone
                    $workzones = explode(',', $model->work_zone);
                    if (is_array($workzones)) {
                        UserWorkzone::deleteAll([
                            'created_by_id' => $model->id
                        ]);
                        if (! $model->saveWorkzones($workzones)) {
                            $data['message'] = \yii::t('app', "Unable to save workzone");
                            $transaction->rollBack();
                            return $data;
                        }
                    }

                    if ($_FILES) {
                        // Qualification documents
                        $files = UploadedFile::getInstances($fileModel, 'key');
                        foreach ($files as $file) {
                            if (! empty($file)) {
                                File::add($model, $file, null, File::FILE_TYPE_QUALIFICATION_DOCUMENT);
                            }
                        }
                    }
                    \Yii::$app->user->login($model);
                    AccessToken::add($loginForm, $model->getAuthKey());
                    // $model->sendVerificationMailtoUser();
                    Notification::create([
                        'model' => $model,
                        'to_user_id' => User::ADMIN,
                        'title' => 'Service Provider complete profile setup',
                        'created_by_id' => $model->id
                    ]);
                    $model->sendRegistrationMailtoAdmin();
                    $this->setStatus(200);
                    $transaction->commit();
                    $data['access-token'] = $model->getAuthKey();
                    $data['detail'] = $model->asProviderJson();
                } else {
                    $data['message'] = $model->getErrors();
                }
                // } else {
                // $data['message'] = \yii::t('app', "Email has already been taken.");
                // }
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                $data['error'] = \Yii::t('app', $e->getMessage());
            }
        } else {
            $data['message'] = \Yii::t('app', "Data not posted.");
        }

        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/add-dependent",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[email]","User[password]"},
     *              @OA\Property(property="User[full_name]", type="string", example="David",description="first name"),
     *              @OA\Property(property="User[email]", type="string", format="email", example="test@mail.com",description="Email of the user"),
     *              @OA\Property(property="User[identity_number]", type="string", example="12345678",description="Identification number of user"),
     *              @OA\Property(property="User[date_of_birth]", type="string", example="1998-11-21",description="User date of birth"),
     *              @OA\Property(property="User[zipcode]", type="integer", example="160071",description="User date of birth"),
     *              @OA\Property(property="User[about_me]", type="string", example="",description="User medical condition"),
     *              @OA\Property(property="User[address]", type="string", example="India",description="Address of user"),
     *              @OA\Property(property="User[latitude]", type="string", example="79.364222354",description="Address latitude"),
     *              @OA\Property(property="User[longitude]", type="string", example="30.3642078",description="Address longitude"),
     *              @OA\Property(property="User[age]", type="integer", example="22",description="Age"),
     *              @OA\Property(property="File[key]", type="file",example="",description="select file")
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionAddDependent()
    {
        $data = [];
        $this->setStatus(400);
        $fileModel = new File();
        $post = \Yii::$app->request->post();
        $user = \yii::$app->user->identity;
        $model = new User();
        $model->loadDefaultValues();
        $model->state_id = User::STATE_ACTIVE;
        $model->created_by_id = $user->id;
        $model->type_id = User::TYPE_DEPENDENT;
        $model->role_id = User::ROLE_USER;
        if ($model->load($post)) {
            $contact_identify = User::findByContact($model->contact_no);
            if (! empty($contact_identify)) {
                $data['message'] = \yii::t('app', "Mobile number has already been taken.");
            }
            $model->setPassword(time());
            $model->generateAuthKey();
            $model->step = User::STEP_ONE;
            $model->saveUploadedFile($model, 'profile_file');
            if ($model->save()) {
                if ($_FILES) {
                    // medical report documents
                    $files = UploadedFile::getInstances($fileModel, 'key');
                    foreach ($files as $file) {
                        if (! empty($file)) {
                            File::add($model, $file, null, File::FILE_TYPE_MEDICAL_REPORT);
                        }
                    }
                }
                // $model->sendVerificationMailtoUser();
                $this->setStatus(200);
                $data['detail'] = $model->asJson();
                $data['message'] = \yii::t('app', "Dependent added successfully");
            } else {
                $data['message'] = $model->getErrors();
            }
        } else {
            $data['message'] = \Yii::t('app', "Data not posted.");
        }

        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/update-dependent",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(property="User[full_name]", type="string", example="David",description="first name"),
     *              @OA\Property(property="User[email]", type="string", format="email", example="test@mail.com",description="Email of the user"),
     *              @OA\Property(property="User[identity_number]", type="string", example="12345678",description="Identification number of user"),
     *              @OA\Property(property="User[date_of_birth]", type="string", example="1998-11-21",description="User date of birth"),
     *              @OA\Property(property="User[zipcode]", type="integer", example="160071",description="User date of birth"),
     *              @OA\Property(property="User[about_me]", type="string", example="",description="User medical condition"),
     *              @OA\Property(property="User[address]", type="string", example="India",description="Address of user"),
     *              @OA\Property(property="User[latitude]", type="string", example="79.364222354",description="Address latitude"),
     *              @OA\Property(property="User[longitude]", type="string", example="30.3642078",description="Address longitude"),
     *              @OA\Property(property="File[key]", type="file",example="",description="select file"),
     *              @OA\Property(property="User[age]", type="integer", example="22",description="Age"),
     *              @OA\Property(property="User[profile_file]", type="file", format="file", example="",description="Select image"),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionUpdateDependent($id)
    {
        $data = [];
        $this->setStatus(400);
        $post = \Yii::$app->request->post();
        $transaction = \Yii::$app->db->beginTransaction();
        $fileModel = new File();
        $user = User::findActive()->andWhere([
            'id' => $id
        ])
            ->my()
            ->one();
        if (empty($user)) {
            $data['message'] = \Yii::t('app', "Dependent user not found.");
            return $data;
        }
        $old_image = $user->profile_file;
        if ($user->load($post)) {
            try {
                $user->saveUploadedFile($user, 'profile_file', $old_image);
                if ($user->save()) {
                    if ($_FILES) {

                        // medical report documents
                        $files = UploadedFile::getInstances($fileModel, 'key');
                        foreach ($files as $file) {
                            if (! empty($file)) {
                                $user->deleteOldMedicalFile();
                                File::add($user, $file, null, File::FILE_TYPE_MEDICAL_REPORT);
                            }
                        }
                    }
                    $this->setStatus(200);
                    $transaction->commit();
                    $data['detail'] = $user->asJson();
                    $data['message'] = \Yii::t('app', "Dependent updated successfully.");
                } else {
                    $transaction->rollBack();
                    $data['error'] = $user->getErrors();
                }
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                $data['error'] = \yii::t('app', $e->getMessage());
                return $data;
            }
        } else {
            $data['message'] = \yii::t('app', "Data not posted.");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/dependent-detail",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Dependent User detail",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionDependentDetail($id)
    {
        $data = [];
        $model = User::findOne($id);
        if (! empty($model)) {
            $data['detail'] = $model->asJson();
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'Dependent User Not Found');
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/remove-dependent",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Remove Dependent User",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionRemoveDependent($id)
    {
        $data = [];
        $model = User::find()->where([
            'id' => $id,
            'type_id' => User::TYPE_DEPENDENT
        ])
            ->my()
            ->one();
        if (! empty($model)) {
            $model->delete();
            $data['message'] = \Yii::t('app', 'Dependent User Removed Successfully');
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'Dependent User Not Found');
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/dependent-list",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description=" Show all dependent users list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionDependentList($page = 0)
    {
        $query = User::findActive()->andWhere([
            'type_id' => User::TYPE_DEPENDENT
        ])->my();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Get(path="/user/check",
     *   summary="check",
     *   tags={"check"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionCheck()
    {
        $data = [];

        if (! \Yii::$app->user->isGuest) {
            $data['detail'] = \Yii::$app->user->identity->asJson();
        } else {
            $data['message'] = \yii::t('app', "User not authenticated. No device token found");
        }

        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/login",
     *   summary="",
     *   tags={"User"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"LoginForm[username]","LoginForm[password]","LoginForm[device_token]","LoginForm[device_type]","LoginForm[device_name]"},
     *       @OA\Property(property="LoginForm[username]", type="string", example="12345678",description="contact/mail of user"),
     *       @OA\Property(property="LoginForm[password]", type="string", example="12345678",description="password"),
     *       @OA\Property(property="LoginForm[role_id]", type="integer", example="1",description="role"),
     *       @OA\Property(property="LoginForm[device_token]", type="string", example="12345678",description="Device Token"),
     *       @OA\Property(property="LoginForm[device_type]", type="string", example="12345678",description="Device Type"),
     *       @OA\Property(property="LoginForm[device_name]", type="string", example="12345678",description="Device Name"),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionLogin()
    {
        $data = [];
        $this->setStatus(400);
        $model = new LoginForm();
        $post = Yii::$app->request->post();
        if ($model->load($post)) {

            $user = User::findByContact($model->username);
            if ($user) {
                if ($user->role_id == User::ROLE_ADMIN) {
                    $data['message'] = Yii::t('app', 'You are not authorised to login');
                    return $data;
                }
                if ($user->role_id != $model->role_id) {
                    if ($model->role_id == User::ROLE_USER) {
                        $data['error'] = \yii::t('app', 'You are not allowed to login in user section with service provider credentials.');
                    } else {
                        $data['error'] = \yii::t('app', 'You are not allowed to login in service provider section with user credentials.');
                    }
                    return $data;
                }

                if ($user->state_id == User::STATE_ACTIVE) {
                    if ($user->otp_verified == User::OTP_NOT_VERIFIED) {
                        if (! $user->validatePassword($model->password)) {
                            $data['message'] = \Yii::t('app', "Incorrect password");
                            return $data;
                        }
                        $this->setStatus(200);
                        $data['message'] = \Yii::t('app', "This mobile number has already sign up, please proceed to sign in");
                        $data['detail'] = $user->asJson();
                        return $data;
                    }
                }
                if ($model->applogin()) {
                    $user->generateAuthKey();
                    $user->updateAttributes([
                        'activation_key'
                    ]);
                    $this->setStatus(200);
                    $data['access-token'] = $user->getAuthKey();
                    AccessToken::add($model, $user->getAuthKey());
                    $data['message'] = Yii::t('app', "Login successfully");
                    $data['detail'] = $user->asJson();
                } else {
                    $data['message'] = $model->getErrors();
                    return $data;
                }
            } else {
                $data['message'] = Yii::t('app', "User not registered");
            }
        } else {
            $data['message'] = Yii::t('app', "No data posted");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/verify-otp",
     *   summary="",
     *   tags={"User"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[contact_no]"},
     *              @OA\Property(property="User[contact_no]", type="string", example="12345678",description="contact of user"),
     *              @OA\Property(property="User[country_code]", type="string", example="91",description="country code of user"),
     *              @OA\Property(property="User[otp_verified]", type="string", example="1234",description="otp"),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionVerifyOtp()
    {
        $this->setStatus(400);
        $data = [];
        $model = new User();
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $user = User::findByContact($model->contact_no);
            if (empty($user)) {
                $data['message'] = \Yii::t('app', 'User not found');
                return $data;
            }

            if ($model->otp_verified == User::OTP_NOT_VERIFIED) {
                $data['message'] = \Yii::t('app', 'Incorrect OTP');
                return $data;
            }

            // if ($user->checkOtpExpireTime()) {
            $user->state_id = User::STATE_ACTIVE;
            $user->otp_verified = User::OTP_VERIFIED;
            if ($user->step == User::STEP_THREE) {
                $user->step = User::STEP_THREE;
            } else {
                $user->step = User::STEP_TWO;
            }
            $user->otp = '';
            $user->updateAttributes([
                'state_id',
                'otp_verified',
                'otp',
                'step'
            ]);
            $this->setStatus(200);
            $data['detail'] = $user->asJSon();
            $data['message'] = \yii::t('app', "Your account verified successfully!");
            // } else {
            // $data['message'] = \Yii::t('app', "OTP Expired");
            // }
        } else {
            $data['message'] = \Yii::t('app', 'No data posted');
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/resend-otp",
     *   summary="",
     *   tags={"User"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[contact_no]","User[email]"},
     *              @OA\Property(property="User[contact_no]", type="string", example="12345678",description="contact of user"),
     *              @OA\Property(property="User[country_code]", type="string", example="12345678",description="country code of user"),
     *
     *
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionResendOtp()
    {
        $data = [];
        $model = new User();
        $this->setStatus(400);
        if ($model->load(Yii::$app->request->post())) {
            $user = User::findByContact($model->contact_no);
            if (! empty($user)) {
                // if ($user->otp_attempt != User::OTP_ATTEMPT_FOUR) {
                // if ($user->checkOtpExpireTime()) {
                // $data['message'] = \Yii::t('app', "OTP already sent");
                // return $data;
                // }
                // }
                $user->otp = rand(111111, 999999);
                // $user->otp = '1234';
                $user->last_action_time = date('Y-m-d H:i:s');
                $user->otp_attempt = $user->otp_attempt + User::STATE_ACTIVE;
                $user->updateAttributes([
                    'otp',
                    'last_action_time',
                    'otp_attempt'
                ]);
                // $user->sendOtpSmstoUser();
                $this->setStatus(200);
                $data['detail'] = $user->asJson();
                $data['message'] = \Yii::t('app', "Verification code sent successfully");
            } else {
                $data['error'] = \Yii::t('app', "No User found");
            }
        } else {
            $data['error'] = \Yii::t('app', "No data posted");
        }

        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/logout",
     *   summary="Logout",
     *   tags={"user"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionLogout()
    {
        $data = [];
        $user = \Yii::$app->user->identity;
        if (\Yii::$app->user->logout()) {
            $user->generateAuthKey();
            $user->updateAttributes([
                'activation_key'
            ]);
            AccessToken::deleteOldAppData($user->id);
            $this->setStatus(200);
            $data['message'] = \Yii::t('app', "You Have Logged Out!");
        }

        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/get-page",
     *   summary="Get Page Content",
     *   tags={"User"},
     *   @OA\Parameter(
     *     name="type_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="application_type",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionGetPage($type_id, $application_type = Page::APPLICATION_TYPE_USER)
    {
        $data = [];
        if (is_numeric($type_id)) {
            $model = Page::findActive()->andWhere([
                'type_id' => $type_id,
                'application_type' => $application_type
            ])->one();
            if (! empty($model)) {
                $data['detail'] = $model->asJson();
            } else {
                $data['message'] = \Yii::t('app', "No data found");
            }
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', "No such page found");
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/location-list",
     *   summary="Get Workzone List",
     *   tags={"User"},
     *   @OA\Response(
     *     response=200,
     *     description="Returns location list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionLocationList($page = 0)
    {
        $query = Location::findActive();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Post(path="/user/contact-us",
     *   summary="Contact Us",
     *   tags={"User"},
     *   *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={
     *              "Information[email]",
     *              "Information[description]",
     *              "Information[full_name]",
     *              },
     *              @OA\Property(
     *              property="Information[email]",
     *              type="email", format="text",
     *              example="Sam@gmail.in",
     *              description="Enter Email"
     *              ),
     *              @OA\Property(
     *              property="Information[description]",
     *              type="string", format="text",
     *              example="I want to contact",
     *              description="Enter Description"
     *              ),
     *
     *              @OA\Property(
     *              property="Information[full_name]",
     *              type="string", format="text",
     *              example="Sam",
     *              description="Enter Full Name"
     *              ),
     *
     *              @OA\Property(
     *              property="Information[mobile]",
     *              type="string", format="number",
     *              example="Sam",
     *              description="Enter contact number"
     *              ),
     *
     *
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Contact Successfull Message",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionContactUs()
    {
        $data = [];
        $this->setStatus(400);
        $model = new Information();
        if ($model->load(Yii::$app->request->post())) {
            $from = $model->email;
            $message = \yii::$app->view->renderFile('@app/mail/contact.php', [
                'user' => $model
            ]);
            $sub = 'New Contact Mail: ';
            EmailQueue::sendEmailToAdmins([
                'from' => $from,
                'subject' => $sub,
                'html' => $message,
                'type_id' => EmailQueue::TYPE_KEEP_AFTER_SEND
            ], false);

            if ($model->save()) {
                $data['message'] = \Yii::t('app', "Warm Greetings!! Thank you for contacting us. We have received your request. Our representative will contact you soon.");
                $this->setStatus(200);
            } else {
                $data['error'] = $model->getErrors();
            }
        } else {
            $data['error'] = \Yii::t('app', "No Data Posted");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/change-password",
     *   summary="",
     *   tags={"User"},
     *   @OA\Parameter(
     *     name="contact_no",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[new_password]"},
     *              @OA\Property(property="User[new_password]", type="string", format="password", example="admin123",description="Password"),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Change password",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionChangePassword($contact_no)
    {
        $data = [];
        $this->setStatus(400);
        $model = User::findByContact($contact_no);
        $newModel = new User([
            'scenario' => 'change-password'
        ]);
        if ($newModel->load(Yii::$app->request->post())) {
            if ($model->validatePassword($newModel->new_password)) {
                $data['message'] = \Yii::t('app', "This password is already set.");
                return $data;
            }
            if ($newModel->validate()) {
                $model->setPassword($newModel->new_password);
                $model->generateAuthKey();
                $model->updateAttributes([
                    'password'
                ]);
                $this->setStatus(200);
                $data['message'] = \Yii::t('app', "Password changed successfully.");
            } else {
                $data['error'] = \Yii::t('app', $newModel->getErrorsString());
            }
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/update-profile-user",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(property="User[full_name]", type="string", example="David",description="first name"),
     *              @OA\Property(property="User[email]", type="string", format="email", example="test@mail.com",description="Email of the user"),
     *              @OA\Property(property="User[identity_number]", type="string", example="12345678",description="Identification number of user"),
     *              @OA\Property(property="User[date_of_birth]", type="string", example="1998-11-21",description="User date of birth"),
     *              @OA\Property(property="User[zipcode]", type="integer", example="160071",description="User date of birth"),
     *              @OA\Property(property="User[about_me]", type="string", example="",description="User medical condition"),
     *              @OA\Property(property="User[address]", type="string", example="India",description="Address of user"),
     *              @OA\Property(property="User[latitude]", type="string", example="79.364222354",description="Address latitude"),
     *              @OA\Property(property="User[longitude]", type="string", example="30.3642078",description="Address longitude"),
     *              @OA\Property(property="File[key]", type="file",example="",description="select file"),
     *              @OA\Property(property="User[profile_file]", type="file", format="file", example="",description="Select image"),
     *              @OA\Property(property="User[new_password]", type="string", format="password", example="admin123",description="Password"),
     *              @OA\Property(property="User[old_password]", type="string", format="password", example="admin123",description="Password"),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionUpdateProfileUser()
    {
        $data = [];
        $this->setStatus(400);
        $post = \Yii::$app->request->post();
        $transaction = \Yii::$app->db->beginTransaction();
        $user = \Yii::$app->user->identity;
        $fileModel = new File();
        $old_image = $user->profile_file;
        if ($user->load($post)) {
            try {
                $user->saveUploadedFile($user, 'profile_file', $old_image);

                if (! empty($user->old_password) && ! empty($user->new_password)) {
                    if ($user->validatePassword($user->old_password)) {
                        $user->setPassword($user->new_password);
                    } else {
                        $data['message'] = \Yii::t('app', 'Old password is incorrect');
                        return $data;
                    }
                }

                if ($user->save()) {
                    if ($_FILES) {
                        // medical report documents
                        $files = UploadedFile::getInstances($fileModel, 'key');
                        foreach ($files as $file) {
                            if (! empty($file)) {
                                $user->deleteOldMedicalFile();
                                File::add($user, $file, null, File::FILE_TYPE_MEDICAL_REPORT);
                            }
                        }
                    }
                    $this->setStatus(200);
                    $transaction->commit();
                    $data['detail'] = $user->asJson();
                    $data['message'] = \Yii::t('app', "Profile has been updated successfully.");
                } else {
                    $transaction->rollBack();
                    $data['error'] = \yii::t('app', $user->getErrors());
                }
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                $data['error'] = \yii::t('app', $e->getMessage());
                return $data;
            }
        } else {
            $data['message'] = \yii::t('app', "Data not posted.");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/update-profile-provider",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"User[email]","User[password]"},
     *              @OA\Property(property="User[full_name]", type="string", example="David",description="first name"),
     *              @OA\Property(property="User[email]", type="string", format="email", example="test@mail.com",description="Email of the user"),
     *              @OA\Property(property="User[password]", type="string", format="password", example="admin123",description="Password"),
     *              @OA\Property(property="User[identity_number]", type="string", example="12345678",description="Identification number of user"),
     *              @OA\Property(property="User[date_of_birth]", type="string", example="1998-11-21",description="User date of birth"),
     *              @OA\Property(property="User[zipcode]", type="integer", example="160071",description="User date of birth"),
     *              @OA\Property(property="User[about_me]", type="string", example="",description="User medical condition"),
     *              @OA\Property(property="User[experience]", type="integer", example="",description="Service Provider Experience"),
     *              @OA\Property(property="User[gender]", type="integer", example="",description="Service Provider Gender"),
     *              @OA\Property(property="User[address]", type="string", example="India",description="Address of user"),
     *              @OA\Property(property="User[qualification]", type="string", example="India",description="User qualifications"),
     *              @OA\Property(property="User[service_language]", type="string", example="India",description="Languages of user"),
     *              @OA\Property(property="User[skill]", type="string", example="1,2,3",description="Skills of user"),
     *              @OA\Property(property="User[work_zone]", type="string", example="1,2,3",description="Languages of user"),
     *              @OA\Property(property="LoginForm[device_token]", type="string", example="263623dsafsdf",description="device token"),
     *              @OA\Property(property="LoginForm[device_name]", type="string", example="android",description="android / ios"),
     *              @OA\Property(property="LoginForm[device_type]", type="string",example="1",description="device type"),
     *              @OA\Property(property="User[profile_file]", type="file", format="file", example="",description="Select image"),
     *              @OA\Property(description="Add multiple file",property="File[key][]",type="array", @OA\Items(type="file",format="binary")),
     *              @OA\Property(property="User[new_password]", type="string", format="password", example="admin123",description="Password"),
     *              @OA\Property(property="User[old_password]", type="string", format="password", example="admin@123",description="Password"),
     *              @OA\Property(property="User[file_id]", type="string", example="1,2,3",description="Skills of user"),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="update provider profile",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionUpdateProfileProvider()
    {
        $data = [];
        $this->setStatus(400);
        $post = \Yii::$app->request->post();
        $transaction = \Yii::$app->db->beginTransaction();
        $user = \Yii::$app->user->identity;
        $fileModel = new File();
        $old_image = $user->profile_file;
        if ($user->load($post)) {
            try {
                $user->saveUploadedFile($user, 'profile_file', $old_image);

                if (! empty($user->old_password) && ! empty($user->new_password)) {
                    if ($user->validatePassword($user->old_password)) {
                        $user->setPassword($user->new_password);
                    } else {
                        $data['message'] = \Yii::t('app', 'Old password is incorrect');
                        return $data;
                    }
                }

                if ($user->save()) {

                    // add provider qualification
                    $qualifications = explode(',', $user->qualification);
                    if (is_array($qualifications)) {
                        if (! empty($qualifications)) {
                            UserCategory::deleteAll([
                                'created_by_id' => $user->id
                            ]);
                            if (! $user->saveQualifications($qualifications)) {
                                $data['message'] = \yii::t('app', "Unable to save qualification");
                                $transaction->rollBack();
                                return $data;
                            }
                        }
                    }
                    // add provider skills
                    $skills = explode(',', $user->skill);
                    if (is_array($skills)) {
                        if (! empty($skills)) {
                            UserSkill::deleteAll([
                                'created_by_id' => $user->id
                            ]);
                            if (! $user->saveSkills($skills)) {
                                $data['message'] = \yii::t('app', "Unable to save skills");
                                $transaction->rollBack();
                                return $data;
                            }
                        }
                    }

                    // add languages
                    $languages = explode(',', $user->service_language);
                    if (is_array($languages)) {
                        if (! empty($languages)) {
                            UserLanguage::deleteAll([
                                'created_by_id' => $user->id
                            ]);
                            if (! $user->saveLanguages($languages)) {
                                $data['message'] = \yii::t('app', "Unable to save languages");
                                $transaction->rollBack();
                                return $data;
                            }
                        }
                    }

                    // add languages
                    $workzones = explode(',', $user->work_zone);
                    if (is_array($workzones)) {
                        if (! empty($workzones)) {
                            UserWorkzone::deleteAll([
                                'created_by_id' => $user->id
                            ]);
                            if (! $user->saveWorkzones($workzones)) {
                                $data['message'] = \yii::t('app', "Unable to save workzone");
                                $transaction->rollBack();
                                return $data;
                            }
                        }
                    }

                    if ($_FILES) {
                        $user->deleteOldFile();
                        // Qualification documents
                        $files = UploadedFile::getInstances($fileModel, 'key');
                        foreach ($files as $file) {
                            if (! empty($file)) {
                                File::add($user, $file, null, File::FILE_TYPE_QUALIFICATION_DOCUMENT);
                            }
                        }

                        $new_files = UploadedFile::getInstances($fileModel, 'name');
                        foreach ($new_files as $new_file) {
                            if (! empty($new_file)) {
                                File::add($user, $new_file, null, File::FILE_TYPE_QUALIFICATION_DOCUMENT);
                            }
                        }
                    }
                    $this->setStatus(200);
                    $transaction->commit();
                    $data['detail'] = $user->asProviderJson();
                    $data['message'] = \Yii::t('app', "Profile has been updated successfully.");
                } else {
                    $transaction->rollBack();
                    $data['error'] = $user->getErrors();
                }
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                $data['error'] = $e->getMessage();
                return $data;
            }
        } else {
            $data['message'] = \yii::t('app', "Data not posted.");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/user/detail",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="User detail",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionDetail()
    {
        $data = [];
        $model = \Yii::$app->user->identity;
        if (! empty($model)) {
            $data['detail'] = $model->asJson();
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'User Not Found');
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/notification-switch",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Switch the Notification On and Off",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionNotificationSwitch()
    {
        $data = [];
        $this->setStatus(200);
        $model = \Yii::$app->user->identity;
        if ($model->is_notify == User::NOTIFICATION_OFF) {
            $model->is_notify = User::NOTIFICATION_ON;
            $data['message'] = \Yii::t('app', 'Notification Turned ON');
        } else {
            $model->is_notify = User::NOTIFICATION_OFF;
            $data['message'] = \Yii::t('app', 'Notification Turned OFF');
        }
        $model->updateAttributes([
            'is_notify'
        ]);
        $data['detail'] = $model->asJson();
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/notification-list",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description=" Show all the notification of Login user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionNotificationList($page = null)
    {
        $query = Notification::find()->where([
            'is_read' => Notification::IS_NOT_READ
        ])->my('to_user_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Get(path="/user/notification-clear",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description=" Show all the notification of Login user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionNotificationClear()
    {
        $data = [];
        Notification::deleteAll([
            'to_user_id' => \Yii::$app->user->id
        ]);
        $data['message'] = "Notification clear successfully";
        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/faq-list",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="type",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description=" Show all the FAQ list for User",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *     ),
     *   ),
     * )
     */
    public function actionFaqList($type = Faq::TYPE_USER, $page = 0)
    {
        $query = Faq::findActive()->andWhere([
            'type_id' => $type
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Post(path="/user/add-rating",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"Rating[model_id]","Rating[state_id]","Rating[rating]","Rating[title]"},
     *              @OA\Property(property="Rating[model_id]", type="integer", format="text", example="1",description="Id of service"),
     *              @OA\Property(property="Rating[state_id]", type="integer", format="text", example="0",description="State for active 1 or inactive 0"),
     *              @OA\Property(property="Rating[rating]", type="integer", format="text", example="0",description="Rating for service"),
     *              @OA\Property(property="Rating[title]", type="string", format="text", example="Title",description="Title")
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Make rating for the property",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionAddRating()
    {
        $data = [];
        $model = new Rating();
        $model->state_id = Rating::STATE_ACTIVE;
        $model->loadDefaultValues();
        if ($model->load(\Yii::$app->request->post())) {
            $model->model_id = $model->model_id;
            $model->model_type = User::className();
            if ($model->save()) {
                $this->setStatus(200);
                $data['message'] = \yii::t('app', 'Rating added successfully');
            } else {
                $this->setStatus(400);
                $data['message'] = $model->getErrors();
            }
        } else {
            $this->setStatus(400);
            $data['message'] = \yii::t('app', "Data not posted.");
        }

        return $data;
    }

    /**
     *
     * @OA\Get(path="/user/provider-detail",
     *   summary="",
     *   tags={"User"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="provider detail",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionProviderDetail()
    {
        $data = [];
        $model = \Yii::$app->user->identity;
        if (! empty($model)) {
            $data['detail'] = $model->asProviderJson(true);
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'User Not Found');
        }
        return $data;
    }
}
