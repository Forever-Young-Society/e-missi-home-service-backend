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
namespace app\modules\smtp;

use app\components\TController;
use app\components\TModule;
use app\models\User;

/**
 * smtp module definition class
 */
class Module extends TModule
{

    const NAME = 'smtp';

    public $controllerNamespace = 'app\modules\smtp\controllers';

    // public $defaultRoute = 'smtp';
    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Outgoing Emails'), '#', 'envelope ', Module::isAdmin(), [
            TController::addMenu(\Yii::t('app', 'Home'), '//smtp', 'home', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Accounts'), '//smtp/account', 'users', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Emails In Queue'), '//smtp/email-queue', 'envelope', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Unsubscribed'), '//smtp/unsubscribe', 'remove', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Settings'), '//smtp/default/settings', 'cog', Module::isAdmin())
        ]);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    // public static function getRules()
    // {
    // return [

    // 'email/queue/<id:\d+>/<title>' => 'smtp/email-queue/view',
    // 'email/icon/<id:\d+>/<title>' => 'smtp/email-queue/image',
    // 'email/<controller:[A-Za-z-]+>/<action:[A-Za-z-]+>/<id:\d+>/<title>' => 'smtp/<controller>/<action>',
    // 'email/<controller:[A-Za-z-]+>/<action:[A-Za-z-]+>/<id:\d+>' => 'smtp/<controller>/<action>'
    // ];
    // }
    public static function getCronJobs()
    {
        return [
            "* * * * * \t /email-queue/send",
            "* * * * * \t /email-queue/clear"
        ];
    }
}
