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
namespace app\modules\scheduler;

use app\components\TController;
use app\components\TModule;

/**
 * scheduler module definition class
 */
class Module extends TModule
{

    const NAME = 'scheduler';

    public $controllerNamespace = 'app\modules\scheduler\controllers';

    public $defaultJobsFile = 'scheduler.default.txt';

    public $defaultJobs = [];

    // public $defaultRoute = 'scheduler';
    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Scheduler'), '#', 'calendar ', Module::isAdmin(), [
            TController::addMenu(\Yii::t('app', 'Home'), '//scheduler', 'home', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Cronjobs'), '//scheduler/cronjob', 'suitcase', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Logs'), '//scheduler/log', 'lock', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Settings'), '//scheduler/default/settings', 'cog', Module::isAdmin())
            
        ]);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function getRules()
    {
        return [
            'scheduler/<id:\d+>/<title>' => 'scheduler/cronjob/view',
            'scheduler/<action>' => 'scheduler/<action>/index',
            'scheduler/<action>/view/<id:\d+>' => 'scheduler/<action>/view'
        ];
    }
}
