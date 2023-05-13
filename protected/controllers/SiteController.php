<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\controllers;

use app\components\TController;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;
use Yii;
use IPay88;
use app\models\PaymentResponse;
use yii\helpers\Json;
use app\modules\availability\models\SlotBooking;
use app\modules\notification\models\Notification;
use app\base\TipayRequest;
use app\modules\contact\models\Information;
use app\components\World;
use app\components\TActiveForm;
use yii\web\HttpException;
use app\modules\smtp\models\EmailQueue;

class SiteController extends TController
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
                            'about',
                            'error',
                            'demo',
                            'privacy',
                            'terms',
                            'captcha',
                            'pay',
                            'response'
                        ],
                        'allow' => true,
                        'roles' => [
                            '*',
                            '?',
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'contact'
                        ],
                        'allow' => true,
                        'roles' => [
                            '*',
                            '?'
                        ]
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
            ]
        ];
    }

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        return $this->render('error', [
            'message' => $exception->getMessage(),
            'name' => 'Error'
        ]);
    }

    public function actionIndex()
    {
        $this->updateMenuItems();
        if (! \Yii::$app->user->isGuest) {
            $this->layout = 'main';
            return $this->redirect('dashboard/index');
        } else {
            $this->layout = 'guest-main';
            return $this->render('index');
        }
    }

    public function actionContact()
    {
        $model = new Information();
        $model->loadDefaultValues();
        $model->state_id = Information::STATE_DRAFT;
        $model->referrer_url = Yii::$app->request->absoluteUrl;
        $model->ip_address = \Yii::$app->request->userIP;
        $model->user_agent = \Yii::$app->request->userAgent;
        $model->country_code = World::getCountryCodeByIp($model->ip_address);
        $post = \yii::$app->request->post();

        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }

        if ($model->load($post) && $model->validate()) {
            if ($model->checkSpamMail() > 0) {
                throw new HttpException(403, Yii::t('app', 'You are not allowed to SPAM'));
            }
            $model->state_id = Information::STATE_SPAM; // Marked as spam until email not verified
            if ($model->save()) {
                Yii::$app->session->setFlash('contactFormSubmitted');
                // Sends email confirmation mail to user
                $subject = 'Thank You';
                $msg = \yii::$app->view->renderFile('@app/modules/contact/mail/thank-you.php', [
                    'model' => $model
                ]);
                $sent = EmailQueue::add([
                    'from' => \Yii::$app->params['adminEmail'],
                    'subject' => $subject,
                    'to' => $model->email,
                    'html' => $msg
                ]);

                if ($sent) {
                    $model->state_id = Information::STATE_SUBMITTED;
                    $model->updateAttributes([
                        'state_id'
                    ]);
                    $sub = 'New Contact: ' . $model->subject;
                    $message = \yii::$app->view->renderFile('@app/mail/contact.php', [
                        'user' => $model
                    ]);

                    EmailQueue::sendEmailToAdmins([
                        'from' => $model->email,
                        'subject' => $sub,
                        'html' => $message
                    ], true);
                }
                \Yii::$app->controller->redirect([
                    'contact/information/thankyou',
                    'id' => $model->id
                ]);
            }
        }

        return $this->render('contact', [
            'model' => $model
        ]);
    }

    public function actionAbout()
    {
        $this->layout = 'guest-main';
        return $this->render('about');
    }

    public function actionPrivacy()
    {
        $this->layout = 'guest-main';
        return $this->render('privacy');
    }

    public function actionTerms()
    {
        $this->layout = 'guest-main';
        return $this->render('terms');
    }

    public function beforeAction($action)
    {
        if ($action->id == 'response') {
            $this->enableCsrfValidation = false;
        }
        if (! parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    public function actionPay()
    {
        $response_url = \Yii::$app->urlManager->createAbsoluteUrl('site/response');
        $backend_response_url = \Yii::$app->urlManager->createAbsoluteUrl('site/backend-response');
        $request = new IPay88\Payment\Request(User::getMerchantkey());
        $data = [
            'merchantCode' => $request->setMerchantCode(User::getMerchantCode()),
            'paymentId' => $request->setPaymentId(2),
            'refNo' => $request->setRefNo('EXAMPLE0001'),
            'amount' => $request->setAmount('1.00'),
            'currency' => $request->setCurrency('MYR'),
            'prodDesc' => $request->setProdDesc('Testing'),
            'userName' => $request->setUserName('Your name'),
            'userEmail' => $request->setUserEmail('email@example.com'),
            'userContact' => $request->setUserContact('0123456789'),
            'remark' => $request->setRemark('Some remarks here..'),
            'lang' => $request->setLang('UTF-8'),
            'signature' => $request->getSignature(),
            'responseUrl' => $request->setResponseUrl($response_url),
            'backendUrl' => $request->setBackendUrl($backend_response_url)
        ];

        TipayRequest::make(User::getMerchantkey(), $data);
    }

    public function actionResponse()
    {
        $response = (new IPay88\Payment\Response())->init(User::getMerchantCode());
        if (! empty($response)) {
            $encodeJson = Json::encode($response);
            $model = new PaymentResponse();
            $model->description = $encodeJson;
            $model->state_id = PaymentResponse::STATE_ACTIVE;
            if (! $model->save()) {
                return false;
            }
            $status = $response['status'];
            $view = 'payment_fail';
            if ($status == SlotBooking::PAYMENT_SUCCESS) {
                $view = 'payment_success';
            }
        }
        return $this->render($view);
    }

    protected function updateMenuItems($model = null)
    {
        // create static model if model is null
        switch ($this->action->id) {
            case 'add':
                {
                    $this->menu[] = array(
                        'label' => Yii::t('app', 'Manage'),
                        'url' => array(
                            'index'
                        ),
                        'visible' => User::isAdmin()
                    );
                }
                break;
            default:
            case 'view':
                {
                    $this->menu[] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span> Manage',
                        'title' => 'Manage',
                        'url' => array(
                            'index'
                        ),
                        'visible' => User::isAdmin()
                    );

                    if ($model != null)
                        $this->menu[] = array(
                            'label' => Yii::t('app', 'Update'),
                            'url' => array(
                                'update',
                                'id' => $model->id
                            ),
                            'visible' => ! User::isAdmin()
                        );
                }
                break;
        }
    }
}
