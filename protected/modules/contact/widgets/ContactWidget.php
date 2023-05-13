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
namespace app\modules\contact\widgets;

use app\components\TActiveForm;
use app\components\TBaseWidget;
use app\models\EmailQueue;
use app\modules\contact\models\Information;
use Yii;
use yii\web\HttpException;
use app\components\World;

class ContactWidget extends TBaseWidget
{

    public $form_type = Information::TYPE_CONTACT;

    public function run()
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
                    ], false);
                }
                \Yii::$app->controller->redirect([
                    'contact/information/thankyou',
                    'id' => $model->id
                ]);
            }
        }
        if ($this->form_type == Information::TYPE_CONTACT) {
            return $this->render('form', [
                'model' => $model
            ]);
        }
        return $this->render('quote_form', [
            'model' => $model
        ]);
    }
}
?>
