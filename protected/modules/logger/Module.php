<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\logger;

use app\components\TController;
use app\components\TModule;

/**
 * log module definition class
 */
class Module extends TModule
{

    const NAME = 'logger';

    public $controllerNamespace = 'app\modules\logger\controllers';

    public $enableEmails = true;

    // public $defaultRoute = 'log';
    public $contactToSupportEmail = null;

    public $sendLogEmailsTo = null;

    public $errorCodes = [
        500
    ];

    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Logger'), '#', 'key ', Module::isAdmin(), [
            TController::addMenu(\Yii::t('app', 'Home'), '//logger', 'home', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Errors'), '//logger/log', 'times', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'System Info'), '//logger/default/technology', 'server', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Settings'), '//logger/default/settings', 'cog', Module::isAdmin())
        ]);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function getRules()
    {
        return [ /*
                   * 'log/<id:\d+>/<title>' => 'logger/log/view',
                   * 'log' => 'logger/log/index',
                   * 'log/delete/<id:\d+>/<title>' => 'logger/log/delete',
                   * 'log/custom-error' => 'logger/log/custom-error'
                   */
        ];
    }
}
