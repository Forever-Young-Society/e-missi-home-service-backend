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
namespace app\modules\workzone;

use app\components\TController;
use app\components\TModule;

/**
 * workzone module definition class
 */
class Module extends TModule
{

    const NAME = 'workzone';

    public $controllerNamespace = 'app\modules\workzone\controllers';

    // public $defaultRoute = 'workzone';
    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Workzone Management'), '#', 'key ', Module::isAdmin(), [
            TController::addMenu(\Yii::t('app', 'Zone'), '//workzone/zone', 'list', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Location'), '//workzone/location', 'map', Module::isAdmin())
        ],true);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function getRules()
    {
        return [
            'workzone/<id:\d+>/<title>' => 'workzone/post/view',
            'workzone/<action>' => 'workzone/<action>/index',
            'workzone/<action>/view/<id:\d+>' => 'workzone/<action>/view'
        ];
    }
}
