<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\sms;

use app\components\TController;
use app\components\TModule;

/**
 * sms module definition class
 */
class Module extends TModule
{

    const NAME = 'sms';

    public $controllerNamespace = 'app\modules\sms\controllers';

    public $defaultRoute = 'gateway';

    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'SMS'), '#', 'envelope ', Module::isAdmin(), [
            TController::addMenu(\Yii::t('app', 'Gateway'), '//sms/gateway', 'paper-plane', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'History'), '//sms/history', 'history', Module::isAdmin())
        ]);
    }

    public function init()
    {
        parent::init();
        \Yii::$app->set('sms', [
            'class' => 'app\modules\sms\components\Sms'
        ]);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }
}
