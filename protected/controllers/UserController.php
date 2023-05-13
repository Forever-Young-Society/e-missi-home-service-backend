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
namespace app\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\components\helpers\TEmailTemplateHelper;
use app\models\EmailQueue;
use app\models\File;
use app\models\LoginForm;
use app\models\User;
use app\models\UserCategory;
use app\models\UserLanguage;
use app\models\UserSubcategory;
use app\models\UserWorkzone;
use app\models\search\User as UserSearch;
use app\modules\availability\models\ProviderSlot;
use app\modules\notification\models\Notification;
use Yii;
use yii2fullcalendar\models\Event;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use app\modules\service\models\ProviderSkill;
use app\models\UserSkill;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends TController
{

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
                            'index',
                            'view',
                            'update',
                            'delete',
                            'clear',
                            'final-delete',
                            'provider-approval',
                            'approve-provider',
                            'reject-provider',
                            'provider',
                            'dependent-user',
                            'image',
                            'ajax',
                            'resetpassword',
                            'incomplete',
                            'changepassword',
                            'logout',
                            'email-resend',
                            'theme-param',
                            'image',
                            'update-provider',
                            'approve-document',
                            'reject-document',
                            'rejected',
                            'update-user',
                            'availability',
                            'delete-qualification-image',
                            'qualification-skills',
                            'set-daterange'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],

                    [
                        'actions' => [
                            'image'
                        ],
                        'allow' => (! defined('ENABLE_ERP')) ? true : false,
                        'roles' => [
                            '?',
                            '*'
                        ]
                    ],
                    [
                        'actions' => [
                            'login',
                            'recover',
                            'resetpassword',
                            'download',
                            'captcha',
                            'confirm-email'
                        ],
                        'allow' => true,
                        'roles' => [
                            '?',
                            '*'
                        ]
                    ]
                ]
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction'
                // 'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ],
            'image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => User::class,
                'attribute' => 'profile_file',
                'default' => true
            ]
        ];
    }

    /**
     * Clear runtime and assets
     *
     * @return \yii\web\Response
     */
    public function actionClear()
    {
        $runtime = Yii::getAlias('@runtime');
        $this->cleanRuntimeDir($runtime);

        $this->cleanAssetsDir();
        return $this->goBack();
    }

    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::ROLE_USER);
        $dataProvider->query->andwhere([
            'u.step' => User::STEP_THREE
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Set from date and to date in session for filtering
     *
     * @return \yii\web\Response
     */
    public function actionSetDaterange()
    {
        $model = new User();
        $model->scenario = 'date-range';
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $session = \Yii::$app->session;
            if (isset($model->from_date) && ! empty($model->from_date)) {
                $dateArry = explode('to', $model->from_date);
                $session->set('from_date', date('Y-m-d', strtotime($dateArry[0])));
                $session->set('to_date', date('Y-m-d', strtotime($dateArry[1])));
            } else {
                unset($session['from_date']);
                unset($session['to_date']);
            }
        }
        return $this->redirectBack();
    }

    public function actionProvider()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams, User::ROLE_SERVICE_PROVIDER);
        $dataProvider->query->andwhere([
            'u.is_approve' => User::IS_APPROVE
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionRejected()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams, User::ROLE_SERVICE_PROVIDER);
        $dataProvider->query->andwhere([
            'u.is_approve' => User::IS_REJECT
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionIncomplete()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams, null, User::STATE_ACTIVE);
        $dataProvider->query->andwhere([
            '!=',
            'u.step',
            User::STEP_THREE
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionDependentUser()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        $dataProvider->query->andwhere([
            'u.type_id' => User::TYPE_DEPENDENT
        ]);

        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionProviderApproval()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::ROLE_SERVICE_PROVIDER);
        $dataProvider->query->andWhere([
            'u.is_approve' => User::APPROVAL_PENDING,
            'u.step' => User::STEP_THREE
        ]);
        $this->updateMenuItems();
        return $this->render('approval_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionRejectedProvider()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, User::ROLE_SERVICE_PROVIDER);
        $dataProvider->query->andWhere([
            'u.is_approve' => User::IS_REJECT
        ]);
        $this->updateMenuItems();
        return $this->render('approval_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * To approve provider account manually
     *
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionApproveProvider($id)
    {
        $model = $this->findModel($id);
        if (! empty($model)) {
            if ($model->step != User::STEP_THREE) {
                \Yii::$app->session->setFlash('success', 'Please update provider profile first');
                return $this->redirect([
                    'update-provider',
                    'id' => $id
                ]);
            }
            if ($model->is_approve == User::IS_APPROVE) {
                \Yii::$app->session->setFlash('success', 'Service Provider Already Approved');
                return $this->redirectBack();
            }
            $model->is_approve = User::IS_APPROVE;
            $model->otp_verified = User::OTP_VERIFIED;
            $model->updateAttributes([
                'is_approve',
                'otp_verified'
            ]);
            Notification::create([
                'to_user_id' => $model->id,
                'created_by_id' => \Yii::$app->user->id,
                'title' => \Yii::t('app', 'Congratulations! Your account successfully verified'),
                'model' => $model,
                'type_id' => Notification::TYPE_USER_NOTIFICATION
            ]);
            \Yii::$app->session->setFlash('success', 'Service Provider Approved Successfully');
        } else {
            \Yii::$app->session->setFlash('info', 'Service Provider Not Found');
        }
        return $this->redirectBack();
    }

    /**
     * Return qualification skills list as per selected qualification
     *
     * @param integer $id
     * @return string|$response
     */
    public function actionQualificationSkills($id)
    {
        \Yii::$app->response->format = 'json';
        $response['status'] = 'NOK';
        $data = ArrayHelper::Map(ProviderSkill::findActive()->andWhere([
            'category_id' => $id,
            'type_id' => ProviderSkill::TYPE_SUB_SKILL_YES
        ])->each(), 'id', 'title');

        if (! empty($data)) {
            $response['status'] = 'OK';
            $response['data'] = $data;
        }
        return $response;
    }

    public function actionRejectProvider($id)
    {
        $model = $this->findModel($id);
        if (! empty($model)) {
            if ($model->is_approve == User::IS_REJECT) {
                \Yii::$app->session->setFlash('success', 'Service Provider Already Reject');
                return $this->redirectBack();
            }
            $model->updateAttributes([
                'is_approve' => User::IS_REJECT
            ]);

            \Yii::$app->session->setFlash('success', 'Service Provider Rejected Successfully');
            return $this->redirect([
                'user/driver-approval-index'
            ]);
        } else {
            \Yii::$app->session->setFlash('info', 'Service Provider Not Found');
            return $this->redirectBack();
        }
    }

    public function actionAvailability($id)
    {
        $list = [];
        $model = new ProviderSlot();
        $query = ProviderSlot::findActive()->andWhere([
            'created_by_id' => $id
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'start_time' => SORT_ASC
                ]
            ]
        ]);

        foreach ($dataProvider->getModels() as $eventModel) {
            $bgcolor = 'green';
            $brdrcolor = 'green';
            if ($eventModel->checkIsSlotBooked()) {
                $bgcolor = 'red';
                $brdrcolor = 'red';
            }

            $event = new Event();
            $start_day = $eventModel->start_time;
            $end_day = $eventModel->end_time;
            $event->end = date('Y-m-d\TH:i:s\Z', strtotime($end_day));
            $event->id = $eventModel->id;
            $event->backgroundColor = $bgcolor;
            $event->borderColor = $brdrcolor;
            $event->textColor = 'white';
            $event->resourceId = $eventModel->id;
            $event->start = date('Y-m-d\TH:i:s\Z', strtotime($start_day));
            $event->title = date('h:i a', strtotime($eventModel->start_time)) . '-' . date('h:i a', strtotime($eventModel->end_time));
            $event->editable = true;
            $list[] = $event;
        }

        return $this->render('calendar', [
            'events' => $list,
            'model' => $model
        ]);
    }

    public function actionApproveDocument($id, $file_id)
    {
        $model = $this->findModel($id);
        $file = File::findOne($file_id);
        if (! empty($model)) {
            if (! empty($file)) {
                $file->updateAttributes([
                    'is_approve' => File::DOCUMENT_APPROVED
                ]);
                // $model->sendDocApproveRejectMailToUser($file);
                \Yii::$app->session->setFlash('success', 'Document Approved Successfully');
                if (! $model->checkIsAnyDocumentPending()) {
                    $model->updateAttributes([
                        'is_approve' => User::IS_APPROVE
                    ]);
                    Notification::create([
                        'to_user_id' => $model->id,
                        'created_by_id' => \Yii::$app->user->id,
                        'title' => \Yii::t('app', 'Congratulations! Your documents successfully verified'),
                        'model' => $model,
                        'type_id' => Notification::TYPE_USER_NOTIFICATION
                    ]);
                }
            } else {
                \Yii::$app->session->setFlash('info', 'File Not Found');
            }
        } else {
            \Yii::$app->session->setFlash('info', 'Driver Not Found');
        }
        return $this->redirect($model->getUrl('view'));
    }

    public function actionRejectDocument($id, $file_id)
    {
        $model = $this->findModel($id);
        $file = File::findOne($file_id);

        if (! empty($model)) {
            if (! empty($file)) {
                $file->scenario = 'reject-document';
                $post = Yii::$app->request->post();
                if (Yii::$app->request->isAjax && $file->load($post)) {

                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return TActiveForm::validate($file);
                }
                if ($file->load($post)) {
                    $file->updateAttributes([
                        'is_approve' => File::DOCUMENT_REJECTED,
                        'reason'
                    ]);
                    $model->updateAttributes([
                        'is_approve' => User::IS_REJECT
                    ]);
                    \Yii::$app->session->setFlash('success', 'Document Rejected Successfully');
                    Notification::create([
                        'to_user_id' => $model->id,
                        'created_by_id' => \Yii::$app->user->id,
                        'title' => \Yii::t('app', 'Document rejected please upload the valid document'),
                        'model' => $model,
                        'type_id' => Notification::TYPE_USER_NOTIFICATION
                    ]);
                } else {
                    \Yii::$app->session->setFlash('info', 'Data not posted');
                }
            } else {
                \Yii::$app->session->setFlash('info', 'File not found');
            }
        } else {
            \Yii::$app->session->setFlash('info', 'Provider not found');
        }
        return $this->redirect($model->getUrl('view'));
    }

    /**
     * Displays a single User model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd()
    {
        $this->layout = 'main';
        $model = new User();
        $model->role_id = User::ROLE_USER;
        $model->state_id = User::STATE_ACTIVE;
        $model->scenario = 'add';
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && $model->load($post)) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {
            $image = UploadedFile::getInstance($model, 'profile_file');
            if (! empty($image)) {
                $image->saveAs(UPLOAD_PATH . $image->baseName . '.' . $image->extension);
                $model->profile_file = $image->baseName . '.' . $image->extension;

                Yii::$app->getSession()->setFlash('success', 'User Added Successfully.');
            }
            if ($model->validate()) {
                $model->generatePasswordResetToken();
                $model->setPassword($model->password);
                if ($model->save()) {
                    // $model->sendRegistrationMailtoAdmin();
                    // $model->sendRegistrationMailtoUser();
                    Yii::$app->getSession()->setFlash('success', ' User Added Successfully.');
                    return $this->redirect([
                        'view',
                        'id' => $model->id
                    ]);
                }
            }
        }
        $this->updateMenuItems($model);
        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionRecover()
    {
        $this->layout = 'guest-main';
        $model = new User();
        $model->scenario = 'token_request';
        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
            $user = User::findByEmail($model->email);
            if (! empty($user)) {
                if ($user->role_id == User::ROLE_ADMIN) {
                    $model->step = User::STEP_THREE;
                    $user->scenario = 'token_request';
                    $user->generatePasswordResetToken();
                    if (! $user->save()) {
                        throw new HttpException("Cant Generate Authentication Key");
                    }
                    $email = $user->email;
                    $sub = "Password Reset";
                    EmailQueue::add([
                        'from' => \Yii::$app->params['adminEmail'],
                        'to' => $email,
                        'subject' => $sub,
                        'type_id' => EmailQueue::TYPE_KEEP_AFTER_SEND,
                        'html' => TEmailTemplateHelper::renderFile('@app/mail/passwordResetToken.php', [
                            'user' => $user
                        ])
                    ], true);
                }
                return $this->render('thankyou');
            }
        }
        $this->updateMenuItems($model);
        return $this->render('requestPasswordResetToken', [
            'model' => $model
        ]);
    }

    public function actionResetpassword($token)
    {
        $this->layout = 'guest-main';
        $model = User::findByPasswordResetToken($token);
        if (! ($model)) {
            \Yii::$app->session->setFlash('error', 'This URL is expired.');
            return $this->redirect([
                'user/recover'
            ]);
        }
        $newModel = new User([
            'scenario' => 'resetpassword'
        ]);
        if (Yii::$app->request->isAjax && $newModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($newModel);
        }

        if ($newModel->load(Yii::$app->request->post()) && $newModel->validate()) {
            $model->setPassword($newModel->password);
            $model->removePasswordResetToken();
            $model->generateAuthKey();
            $model->last_password_change = date('Y-m-d H:i:s');

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'New password is saved successfully.');
            } else {
                \Yii::$app->session->setFlash('error', 'Error while saving new password.');
            }
            return $this->goHome();
        }
        $this->updateMenuItems($model);
        return $this->render('resetpassword', [
            'model' => $newModel
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $post = \yii::$app->request->post();
        $old_image = $model->profile_file;

        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }

        if ($model->load($post)) {
            $model->profile_file = $old_image;
            $model->saveUploadedFile($model, 'profile_file');
            if ($model->save())
                \Yii::$app->session->setFlash('success', 'Profile Updated Successfully');
            return $this->redirect($model->getUrl());
        }

        $model->password = '';
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionUpdateUser($id)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        $fileModel = new File();
        $model->scenario = 'update-user';
        $post = \yii::$app->request->post();
        $old_image = $model->profile_file;
        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }

        if ($model->load($post)) {
            $model->profile_file = $old_image;
            $model->saveUploadedFile($model, 'profile_file');
            if ($model->save())
                if (! empty($_FILES)) {
                    $files = UploadedFile::getInstances($fileModel, 'key');
                    foreach ($files as $file) {
                        File::add($model, $file, null, File::FILE_TYPE_MEDICAL_REPORT);
                    }
                }
            \Yii::$app->session->setFlash('success', 'Profile Updated Successfully');
            return $this->redirect($model->getUrl());
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model,
            'fileModel' => $fileModel
        ]);
    }

    /**
     * Delete qualification document file used in provider and user profile form
     *
     * @method GET
     * @param integer $id
     * @return $data
     */
    public function actionDeleteQualificationImage($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = [];
        $model = File::findOne($id);
        if (! empty($model)) {
            $model->delete();
            $data['message'] = Yii::t('app', 'File deleted successfully');
        } else {
            $data['message'] = Yii::t('app', 'File Not Found');
        }

        return $data;
    }

    /**
     * Updates an existing Provider User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateProvider($id)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        $model->scenario = 'update-provider';
        $fileModel = new File();
        $getQualifications = $model->getSelectedQualifications(true);
        if (empty($getQualifications[0])) {
            $getQualifications = [];
        }

        $getSkills = $model->getSelectedSkills(true);
        if (empty($getSkills[0])) {
            $getSkills = [];
        }

        $getLanguages = $model->getSelectedLanguages(true);
        if (empty($getLanguages[0])) {
            $getLanguages = [];
        }

        $getWorkzones = $model->getSelectedWorkzones(true);
        if (empty($getWorkzones[0])) {
            $getWorkzones = [];
        }

        $post = \yii::$app->request->post();

        $old_image = $model->profile_file;

        if (Yii::$app->request->isAjax && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }

        if ($model->load($post)) {
            $model->profile_file = $old_image;
            $model->saveUploadedFile($model, 'profile_file', $old_image);
            $model->step = User::STEP_THREE;
            $model->otp_verified = User::OTP_VERIFIED;
            if ($model->save()) {
                if (! empty($_FILES)) {
                    $files = UploadedFile::getInstances($fileModel, 'key');
                    foreach ($files as $file) {
                        File::add($model, $file, null, File::FILE_TYPE_QUALIFICATION_DOCUMENT);
                    }
                }
                // add provider qualification
                if (! is_array($model->qualification)) {
                    $model->qualification = [
                        $model->qualification
                    ];
                }
                if (is_array($model->qualification)) {
                    UserCategory::deleteAll([
                        'created_by_id' => $model->id
                    ]);
                    if (! $model->saveQualifications($model->qualification)) {
                        Yii::$app->getSession()->setFlash('error', "Unable to save qualification");
                        return $this->redirect([
                            'view',
                            'id' => $model->id
                        ]);
                    }
                }

                // add provider skills
                if (is_array($model->skill)) {
                    UserSkill::deleteAll([
                        'created_by_id' => $model->id
                    ]);
                    if (! $model->saveSkills($model->skill)) {
                        Yii::$app->getSession()->setFlash('error', "Unable to save skills");
                        return $this->redirect([
                            'view',
                            'id' => $model->id
                        ]);
                    }
                }

                // add languages
                if (is_array($model->service_language)) {
                    UserLanguage::deleteAll([
                        'created_by_id' => $model->id
                    ]);
                    if (! $model->saveLanguages($model->service_language)) {
                        Yii::$app->getSession()->setFlash('error', "Unable to save languages");
                        return $this->redirect([
                            'view',
                            'id' => $model->id
                        ]);
                    }
                }

                // add workzone
                if (! is_array($model->work_zone)) {
                    $model->work_zone = [
                        $model->work_zone
                    ];
                }
                if (is_array($model->work_zone)) {
                    UserWorkzone::deleteAll([
                        'created_by_id' => $id
                    ]);
                    if (! $model->saveWorkzones($model->work_zone)) {
                        Yii::$app->getSession()->setFlash('error', "Unable to save workzone");
                        return $this->redirect([
                            'view',
                            'id' => $model->id
                        ]);
                    }
                }

                \Yii::$app->session->setFlash('success', 'Profile updated successfully.');
                return $this->redirect($model->getUrl());
            }
        }

        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model,
            'qualifications' => $getQualifications,
            'skills' => $getSkills,
            'languages' => $getLanguages,
            'workzones' => $getWorkzones,
            'fileModel' => $fileModel
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $action = $model->getManageRedirectUrl();

        $this->updateMenuItems($model);

        if (\Yii::$app->user->id == $model->id) {
            \Yii::$app->session->setFlash('user-action-error', 'You are not allowed to perform this operation.');
            return $this->goBack();
        }
        // Disable hard-delete user
        // $model->delete();
        $model->state_id = User::STATE_DELETED;
        $model->save();
        \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Deleted Successfully.'));
        return $this->redirect([
            $action
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionFinalDelete($id)
    {
        $model = $this->findModel($id);

        $action = $model->getManageRedirectUrl();
        $this->updateMenuItems($model);
        if (\Yii::$app->request->isPost) {
            $model->delete();
            if (\Yii::$app->request->isAjax) {
                return true;
            }
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'User Deleted Successfully.'));
            return $this->redirect([
                $action
            ]);
        }
        return $this->render('final-delete', [
            'model' => $model
        ]);
    }

    public function actionLogin()
    {
        $this->layout = "guest-main";

        if (! \Yii::$app->user->isGuest) {
            if (User::isUser()) {
                return $this->goBack([
                    '/dashboard/index'
                ]);
            }
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // TODO: change redirect to return url
            if (! User::isAdmin()) {
                return $this->goBack([
                    '/dashboard/index'
                ]);
            } else {
                return $this->goHome();
            }
        }
        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionChangepassword($id)
    {
        $this->layout = 'main';
        $model = $this->findModel($id);
        if (! ($model->isAllowed()))
            throw new \yii\web\HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

        $newModel = new User([
            'scenario' => 'changepassword'
        ]);
        if (Yii::$app->request->isAjax && $newModel->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return TActiveForm::validate($newModel);
        }
        if ($newModel->load(Yii::$app->request->post()) && $newModel->validate()) {
            $model->setPassword($newModel->password);
            $model->last_password_change = date('Y-m-d H:i:s');
            $model->generateAuthKey();
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Password Changed');
                return $this->redirect([
                    'dashboard/index'
                ]);
            } else {
                \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
            }
        }
        $this->updateMenuItems($model);
        return $this->render('changepassword', [
            'model' => $newModel
        ]);
    }

    public function actionThemeParam()
    {
        $is_collapsed = Yii::$app->session->get('is_collapsed', 'sidebar-collapsed');
        $is_collapsed = empty($is_collapsed) ? 'sidebar-collapsed' : '';
        Yii::$app->session->set('is_collapsed', $is_collapsed);
    }

    /**
     * Resend verification email to user
     *
     * @return string
     */
    public function actionEmailResend()
    {
        $model = User::find()->where([
            'id' => Yii::$app->user->id
        ])->one();
        $model->sendVerificationMailtoUser(true);
        \Yii::$app->session->setFlash('success', 'Email send successfully');
        return $this->goBack([
            '/dashboard/index'
        ]);
    }

    public function actionConfirmEmail($id)
    {
        $user = User::find()->where([
            'activation_key' => $id
        ])->one();
        if (! empty($user)) {

            $user->email_verified = User::EMAIL_VERIFIED;
            $user->state_id = User::STATE_ACTIVE;
            if ($user->save()) {
                \Yii::$app->cache->flush();
                $user->refresh();
                if (Yii::$app->user->login($user, 3600 * 24 * 30)) {
                    \Yii::$app->getSession()->setFlash('success', 'Congratulations! your email is verified');
                    return $this->goBack([
                        '/dashboard/index'
                    ]);
                }
            }
        }
        \Yii::$app->getSession()->setFlash('expired', 'Token is Expired Please Resend Again');
        return $this->goBack([
            '/dashboard/index'
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {

            if (! ($model->isAllowed()))
                throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function updateMenuItems($model = null)
    {
        switch (\Yii::$app->controller->action->id) {
            case 'index':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add'
                        ],
                        'visible' => ! User::isAdmin()
                    ];
                }
                break;
            case 'add':
                {
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                break;

            default:
            case 'view':

                if ($model != null && ($model->role_id == User::ROLE_SERVICE_PROVIDER)) {
                    $this->menu['Manual Approve'] = [
                        'label' => '<span class="glyphicon glyphicon-check"> Manually Approve</span>',
                        'title' => Yii::t('app', 'Manual Approve'),
                        'url' => [
                            'approve-provider',
                            'id' => $model->id
                        ],
                        'visible' => $model->checkIsApproveButtonVisible()
                    ];
                }

                $this->menu['add'] = [
                    'label' => '<span class="glyphicon glyphicon-plus"></span>',
                    'title' => Yii::t('app', 'Add'),
                    'url' => [
                        'add'
                    ],
                    'visible' => ! User::isManager()
                ];

                if ($model != null && ($model->role_id != User::ROLE_ADMIN))
                    $this->menu['changepassword'] = [
                        'label' => '<span class="glyphicon glyphicon-paste"></span>',
                        'title' => Yii::t('app', 'changepassword'),
                        'url' => [
                            'changepassword',
                            'id' => $model->id
                        ],

                        'visible' => ($model->step == User::STEP_THREE)
                    ];
                if ($model != null && $model->role_id != User::ROLE_ADMIN) {
                    $url = ($model->role_id == User::ROLE_USER) ? 'update-user' : 'update-provider';
                    $this->menu['update'] = [
                        'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                        'title' => Yii::t('app', 'Update'),
                        'url' => [
                            $url,
                            'id' => $model->id
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                if ($model != null && $model->role_id == User::ROLE_ADMIN) {
                    $this->menu['update'] = [
                        'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                        'title' => Yii::t('app', 'Update'),
                        'url' => [
                            'update',
                            'id' => $model->id
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                if ($model != null && ($model->role_id != User::ROLE_ADMIN)) {

                    $this->menu['availability'] = [
                        'label' => '<span class="glyphicon glyphicon-credit-card"> Availability</span>',
                        'title' => Yii::t('app', 'Availability'),
                        'url' => $model->getUrl('availability'),
                        'visible' => $model->role_id == User::ROLE_SERVICE_PROVIDER
                    ];

                    $this->menu['provider'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            $model->getManageRedirectUrl()
                        ],
                        'visible' => User::isManager() && $model->role_id == User::ROLE_SERVICE_PROVIDER
                    ];

                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            $model->getManageRedirectUrl()
                        ],
                        'visible' => User::isManager() && $model->role_id == User::ROLE_USER
                    ];
                }

                if ($model != null && $model->role_id != User::ROLE_ADMIN)
                    $this->menu['final-delete'] = [
                        'label' => '<span class="glyphicon glyphicon-trash"></span>',
                        'title' => Yii::t('app', 'Final Delete'),
                        'url' => $model->getUrl('final-delete'),
                        'class' => 'btn btn-danger',
                        'visible' => User::isAdmin()
                    ];
        }
    }
}
