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
namespace app\modules\availability;
use app\components\TController;
use app\components\TModule;
use app\models\User;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;

/**
 * availability module definition class
 */
class Module extends TModule
{
    const NAME = 'availability';

    public $controllerNamespace = 'app\modules\availability\controllers';
	
	//public $defaultRoute = 'availability';
	


    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Availabilities'), '#', 'key ', Module::isAdmin(), [
           // TController::addMenu(\Yii::t('app', 'Home'), '//availability', 'lock', Module::isAdmin()),
        ]);
    }
    
    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }
    
    
    public static function getRules()
    {
        return [
            'availability/<id:\d+>/<title>' => 'availability/post/view',
            'availability/<action>' => 'availability/<action>/index',
            'availability/<action>/view/<id:\d+>' => 'availability/<action>/view',    
        
        ];
    }
    
    
}
