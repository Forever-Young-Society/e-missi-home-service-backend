<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\comment;

use app\components\TController;
use app\components\TModule;
use app\modules\comment\models\Comment;

/**
 * comment module definition class
 */
class Module extends TModule
{

    const NAME = 'comment';

    public $controllerNamespace = 'app\modules\comment\controllers';

    public $defaultRoute = 'comment';

    public $enableRichText = false;

    public $newCommentModel = null;

    public $type = 'db';

    // 'facebook';
    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Comments'), '//comment', 'comments-o', (Module::isAdmin()));
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function getRules()
    {
        return [
            'comment/<controller:[A-Za-z-]+>/<action:[A-Za-z-]+>' => 'comment/<controller>/<action>',
            'comment/<controller:[A-Za-z-]+>/<id:\d+>' => 'comment/<controller>/view',
            'comments' => 'comment/comment/get'
        ];
    }

    public static function beforeDelete($user_id)
    {
        Comment::deleteRelatedAll([
            'created_by_id' => $user_id
        ]);
    }
}
