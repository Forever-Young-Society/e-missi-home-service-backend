<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\smtp\commands;

use app\components\TConsoleController;
use app\components\helpers\TStringHelper;
use app\models\EmailQueue as EmailQueueBase;
use app\models\File;
use app\modules\smtp\models\SettingsForm;
use yii\base\Exception;
use yii\console\ExitCode;

class EmailQueueController extends TConsoleController
{

    /**
     * Send pending emails
     *
     * @param number $limit
     */
    public function actionSend($limit = 100)
    {
        $model = new SettingsForm();

        if (! $model->enableEmails) {
            self::log("Emails not enabled");
            return ExitCode::OK;
        }
        $query = EmailQueueBase::getPendingEmails()->orderBy('id asc')->limit($limit);

        self::log("Sending up emails : " . $query->count());
        foreach ($query->each() as $email) {
            self::log("try Sending  email :" . $email->id . ' - ' . $email);
            try {
                $email->sendNow();
                unset($email);
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
        $query = EmailQueueBase::find()->orderBy('id asc');

        EmailQueueBase::log("Cleaning up emails : " . $query->count());
        $query->limit(1000);
        foreach ($query->each() as $email) {
            EmailQueueBase::log("Deleting  email :" . $email->id . ' - ' . $email);
            try {
                $email->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
        if ($query->count() == 0) {
            EmailQueueBase::truncate();
        }
    }

    /**
     * Clear already sent emails
     *
     * @param number $m
     */
    public function actionClear($m = 12)
    {
        $query = EmailQueueBase::find()->where([
            'state_id' => EmailQueueBase::STATE_SENT
        ]);

        if (! $this->force) {
            $query->andWhere([
                '<',
                'sent_on',
                \date('Y-m-d H:i:s', strtotime("-$m months"))
            ]);
        }
        $query->limit($this->limit)->orderBy('id asc');

        EmailQueueBase::log("Cleaning up emails : " . $query->count());
        foreach ($query->each() as $email) {
            EmailQueueBase::log("Deleting  email :" . $email->id . ' - ' . $email);
            try {
                $email->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }

    /**
     * Clear already sent emails
     *
     * @param number $m
     */
    public function actionClearJunk($m = 12)
    {
        $query = File::find();
        $query->andWhere([
            'like',
            'model_type',
            TStringHelper::basename(EmailQueueBase::class)
        ])->orderBy('id asc');

        EmailQueueBase::log("Cleaning up emails files : " . $query->count());
        foreach ($query->each() as $email) {
            EmailQueueBase::log("Deleting  email :" . $email->id . ' - ' . $email);
            try {
                $email->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }
}

