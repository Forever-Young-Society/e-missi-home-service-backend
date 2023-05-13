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
namespace app\modules\rating;

use app\components\TController;
use app\components\TModule;
use app\models\User;

/**
 * rating module definition class
 */
class Module extends TModule
{

    const NAME = 'rating';

    public $controllerNamespace = 'app\modules\rating\controllers';

    public $defaultRoute = 'rating';

    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Ratings'), '#', 'key ', Module::isAdmin(), [
            TController::addMenu(\Yii::t('app', 'Home'), '//rating', 'lock', Module::isAdmin())
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
     * 'rating/<id:\d+>/<title>' => 'rating/post/view',
     * // 'rating/post/<id:\d+>/<file>' => 'rating/post/image',
     * //'rating/category/<id:\d+>/<title>' => 'rating/category/type'
     *
     * ];
     * }
     */
}
