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
namespace app\controllers;

use app\components\TController;
use app\models\User;
use app\modules\availability\models\SlotBooking;
use IPay88;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\PaymentResponse;
use yii\helpers\Json;
use app\base\TipayRequest;

/**
 * UserTermController implements the CRUD actions for UserTerm model.
 */
class PaymentController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'pay',
                            'response',
                            'success',
                            'failed',
                            'backend-response'
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

    public function beforeAction($action)
    {
        if ($action->id == 'response' || $action->id == 'backend-response') {
            $this->enableCsrfValidation = false;
        }
        if (! parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    public function actionPay($booking_id, $key)
    {
        $response_url = \Yii::$app->urlManager->createAbsoluteUrl('payment/response');
        $backend_response_url = \Yii::$app->urlManager->createAbsoluteUrl('payment/backend-response');
        $userModel = User::findOne([
            'activation_key' => $key
        ]);
        if (! empty($userModel)) {
            $bookingModel = SlotBooking::find()->where([
                'order_id' => $booking_id,
                'created_by_id' => $userModel->id
            ])->one();

            if (! empty($bookingModel)) {
                if ($bookingModel->payment_status != SlotBooking::PAYMENT_SUCCESS) {
                    $booking_service = $bookingModel->serviceDetail;
                    $description = ! empty($booking_service) ? $booking_service->title : 'Not Found';
                    $amount = number_format($bookingModel->user_amount, 2);
                    $amount = '1.00';
                    $request = new IPay88\Payment\Request(User::getMerchantkey());
                    $data = [
                        'merchantCode' => $request->setMerchantCode(User::getMerchantCode()),
                        'paymentId' => $request->setPaymentId(2),
                        'refNo' => $request->setRefNo($bookingModel->id),
                        'amount' => $request->setAmount($amount),
                        'currency' => $request->setCurrency('MYR'),
                        'prodDesc' => $request->setProdDesc($description),
                        'userName' => $request->setUserName($userModel->full_name),
                        'userEmail' => $request->setUserEmail($userModel->email),
                        'userContact' => $request->setUserContact($userModel->contact_no),
                        'remark' => $request->setRemark('Payment for service booking'),
                        'lang' => $request->setLang('UTF-8'),
                        'signature' => $request->getSignature(),
                        'responseUrl' => $request->setResponseUrl($response_url),
                        'backendUrl' => $request->setBackendUrl($backend_response_url)
                    ];

                    TipayRequest::make(User::getMerchantkey(), $data);
                }
            }
        }
    }

    public function actionBackendResponse()
    {
        return SlotBooking::SUCCESS_RESPONSE;
    }

    public function actionResponse()
    {
        $action = 'failed';
        $response = (new IPay88\Payment\Response())->init(User::getMerchantCode());
        $status = SlotBooking::PAYMENT_FAILED;
        if (! empty($response)) {
            $encodeJson = Json::encode($response);
            $model = new PaymentResponse();
            $model->description = $encodeJson;
            $model->state_id = PaymentResponse::STATE_ACTIVE;
            if (! $model->save()) {
                return false;
            }
            $status = $response['status'];
            $data = $response['data'];
            $bookingModel = SlotBooking::findOne($data['RefNo']);
            if (! empty($bookingModel)) {
                if ($bookingModel->payment_status != SlotBooking::PAYMENT_SUCCESS) {
                    $user_name = ! empty($bookingModel->createdBy) ? $bookingModel->createdBy->full_name : '';
                    if ($status == SlotBooking::PAYMENT_SUCCESS) {
                        $bookingModel->payment_status = SlotBooking::PAYMENT_SUCCESS;
                        $bookingModel->transaction_id = $data['TransId'];
                        $bookingModel->description = $encodeJson;
                        $bookingModel->updateAttributes([
                            'payment_status',
                            'transaction_id',
                            'description'
                        ]);
                        $bookingModel->saveEarning();
                        $bookingModel->sendBookingNotificationToUser();
                        $bookingModel->sendBookingNotificationToProvider($user_name);
                    } else {
                        //$bookingModel->payment_status = SlotBooking::PAYMENT_FAILED;
                        $bookingModel->payment_status = SlotBooking::PAYMENT_SUCCESS;
                        $bookingModel->saveEarning();
                        $bookingModel->sendBookingNotificationToUser();
                        $bookingModel->sendBookingNotificationToProvider($user_name);
                        $bookingModel->updateAttributes([
                            'payment_status'
                        ]);
                    }
                }
            }
        }

        if ($status == SlotBooking::PAYMENT_SUCCESS) {
            $action = 'success';
        }
        return $this->redirect($action);
    }

    public function actionSuccess()
    {
        return $this->render('/site/payment_success');
    }

    public function actionFailed()
    {
        return $this->render('/site/payment_fail');
    }
}
