<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\contact\commands;

use app\components\TConsoleController;
use app\modules\contact\models\Information;
use app\modules\contact\models\SettingsForm;
use app\modules\smtp\models\Unsubscribe;
use yii\console\ExitCode;

class InformationController extends TConsoleController
{

    /**
     * Marks information as Spam if mail is unsubscribed
     */
    public function actionMarkSpam()
    {
        $moduleSettings = new SettingsForm();

        if (! $moduleSettings->enable) {
            self::log("Contact not enabled");
            return ExitCode::OK;
        }

        $query = Information::find()->where([
            'in',
            'state_id',
            [
                Information::STATE_SUBMITTED
            ]
        ]);
        self::log('Contacts found: ' . $query->count());
        foreach ($query->each() as $info) {
            self::log('Processing : ' . $info->id);
            if (Unsubscribe::check($info->email) || ($info->user_agent && strstr($info->user_agent, 'bot'))) {
                self::log('in if : ' . $info->id);
                $info->state_id = Information::STATE_SPAM;
                $info->updateAttributes([
                    'state_id'
                ]);
            } else {
                self::log('in else : ' . $info->id);
                if ($info->state_id == Information::STATE_SUBMITTED) {
                    $info->sendToLeadManager();
                }
            }
        }
    }

/**
 * chatscript migration
 */
}

