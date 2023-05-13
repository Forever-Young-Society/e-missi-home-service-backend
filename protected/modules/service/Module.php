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
namespace app\modules\service;

use app\components\TController;
use app\components\TModule;

/**
 * service module definition class
 */
class Module extends TModule
{

    const NAME = 'service';

    public $controllerNamespace = 'app\modules\service\controllers';

    // public $defaultRoute = 'service';
    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Service Management'), '#', 'tasks ', Module::isAdmin(), [
            TController::addMenu(\Yii::t('app', 'Dashboard'), '//service/', 'list', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Qualifications'), '//service/category', 'graduation-cap', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Provider Skills'), '//service/provider-skill', 'cogs', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Services'), '//service/sub-category', 'cogs', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Direct Booking Service'), '//service/sub-category/direct-booking', 'cogs', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Terms'), '//service/term', 'gavel', Module::isAdmin())
        ],true);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function getRules()
    {
        return [
            'service/<id:\d+>/<title>' => 'service/post/view',
            'service/<action>' => 'service/<action>/index',
            'service/<action>/view/<id:\d+>' => 'service/<action>/view'
        ];
    }
}
