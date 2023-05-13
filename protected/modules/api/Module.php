<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api;

use app\modules\api\models\AccessToken;
use app\components\TModule;

/**
 * Api module definition class
 */
class Module extends TModule
{

    /**
     *
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\api\controllers';

    /**
     *
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->controllerMap = [
            'log' => [
                'class' => 'app\modules\logger\controllers\LogController'
            ]
        ];
    }

    public static function getRules()
    {
        return [
            [
                'class' => 'yii\rest\UrlRule',
                'pluralize' => false,
                'controller' => [
                    'api/user',
                    'api/post'
                ]
            ]
        ];
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function beforeDelete($user_id)
    {
        AccessToken::deleteRelatedAll([
            'created_by_id' => $user_id
        ]);
    }
}
