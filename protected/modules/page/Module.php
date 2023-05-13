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
namespace app\modules\page;

use app\components\TController;
use app\components\TModule;
use app\models\User;

/**
 * page module definition class
 */
class Module extends TModule
{

	const NAME = 'page';

	public $controllerNamespace = 'app\modules\page\controllers';

	public $defaultRoute = 'page';

	public static function subNav()
	{
		return TController::addMenu(\Yii::t('app', 'Pages'), '#', 'file ', Module::isAdmin(), [
			TController::addMenu(\Yii::t('app', 'Home'), '//page', 'home', Module::isAdmin())
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
	 * 'page/<id:\d+>/<title>' => 'page/post/view',
	 * // 'page/post/<id:\d+>/<file>' => 'page/post/image',
	 * //'page/category/<id:\d+>/<title>' => 'page/category/type'
	 *
	 * ];
	 * }
	 */
}
