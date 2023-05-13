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
namespace app\modules\settings;

use app\components\TController;
use app\components\TModule;
use app\models\User;

/**
 * settings module definition class
 */
class Module extends TModule
{

    const NAME = 'settings';

    public $controllerNamespace = 'app\modules\settings\controllers';

    // public $defaultRoute = 'settings';
    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Settings'), '#', 'cog', Module::isAdmin(), [
            TController::addMenu(\Yii::t('app', 'Home'), '//settings/default', 'home', Module::isAdmin()),
            TController::addMenu(\Yii::t('app', 'Settings'), '//settings/variable/', 'list', Module::isAdmin())
        ]);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    /*
     * public static function getRules()
     * {
     * return [
     *
     * 'settings/<id:\d+>/<title>' => 'settings/post/view',
     * // 'settings/post/<id:\d+>/<file>' => 'settings/post/image',
     * //'settings/category/<id:\d+>/<title>' => 'settings/category/type'
     *
     * ];
     * }
     */
}
