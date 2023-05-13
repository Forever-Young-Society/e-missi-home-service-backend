<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\commands;

use app\components\TConsoleController;
use Yii;
use function app\components\commands\EmailQueueController\actionClear as date;
use app\models\EmailQueue;
use app\models\File;
use app\modules\comment\models\Comment;
use yii\base\Exception;
use yii\console\ExitCode;

class EmailQueueController extends TConsoleController
{

    /**
     * Send pending emails
     *
     * @param number $m
     */
    public function actionSend($m = 12)
    {
        $enableEmails = Yii::$app->settings->getValue('enableEmails');
        
        if (! $enableEmails) {
            self::log("Emails not enabled");
            return ExitCode::OK;
        }
        
        $query = EmailQueue::find()->where([
            'state_id' => EmailQueue::STATE_PENDING
        ])
            ->orderBy('id asc')
            ->limit(100);

        self::log("Sending up emails : " . $query->count());
        foreach ($query->each() as $email) {
            self::log("Sending  email :" . $email->id . ' - ' . $email);
            try {
                $email->sendNow();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }

    /**
     * Clear all emails
     */
    public function actionTruncate()
    {
        $query = EmailQueue::find()->orderBy('id asc');

        EmailQueue::log("Cleaning up emails : " . $query->count());
        $query->limit(100);
        foreach ($query->each() as $email) {
            EmailQueue::log("Deleting  email :" . $email->id . ' - ' . $email);
            try {
                $email->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
        File::deleteRelatedAll([
            'model_type' => EmailQueue::class
        ]);
        Comment::deleteRelatedAll([
            'model_type' => EmailQueue::class
        ]);

        EmailQueue::truncate();
    }

    /**
     * Clear already sent emails
     *
     * @param number $m
     */
    public function actionClear($m = 12)
    {
        $query = EmailQueue::find()->where([
            'state_id' => EmailQueue::STATE_SENT
        ])
            ->andWhere([
            '<',
            'date_sent',
            date('Y-m-d H:i:s', strtotime("-$m months"))
        ])
            ->orderBy('id asc');

        EmailQueue::log("Cleaning up emails : " . $query->count());
        foreach ($query->each() as $email) {
            EmailQueue::log("Deleting  email :" . $email->id . ' - ' . $email);
            try {
                $email->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
        if ($m == 0) {
            EmailQueue::truncate();
        }
    }
}

