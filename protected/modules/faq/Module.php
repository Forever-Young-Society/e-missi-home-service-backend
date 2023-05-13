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
namespace app\modules\faq;

use app\components\TController;
use app\components\TModule;
use app\models\User;

/**
 * faq module definition class
 */
class Module extends TModule
{

    const NAME = 'faq';

    public $controllerNamespace = 'app\modules\faq\controllers';

    // public $defaultRoute = 'faq';
    public static function subNav()
    {

        return TController::addMenu(\Yii::t('app', "FAQ's"), '/faq/faq/index', 'question', Module::isAdmin(), [
        ],true);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function getRules()
    {
        return [

            'faq/<id:\d+>/<title>' => 'faq/post/view'
            // 'faq/post/<id:\d+>/<file>' => 'faq/post/image',
            // 'faq/category/<id:\d+>/<title>' => 'faq/category/type'
        ];
    }
}
