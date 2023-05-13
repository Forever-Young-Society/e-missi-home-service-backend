<?php
namespace app\modules\seo;

use app\components\TModule;
use app\components\TController;

/**
 * manager module definition class
 */
class Module extends TModule
{

    /**
     *
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\seo\controllers';

    public $defaultRoute = 'manager';

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function subNav()
    {
        if (method_exists("\app\components\WebUser", 'getIsAdminMode') && \Yii::$app->user->isAdminMode)
            return self::adminSubNav();
        return TController::addMenu(\Yii::t('app', 'Seo'), '#', 'key ', Module::isManager(), [
            TController::addMenu(\Yii::t('app', 'Home'), '//seo/', 'home', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Meta'), '//seo/manager', 'list', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Analytics'), '//seo/analytics', 'area-chart', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Redirect'), '//seo/redirect', 'reply', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Logs'), '//seo/log', 'lock', Module::isManager())
        ]);
    }

    public static function adminSubNav()
    {
        return TController::addMenu(\Yii::t('app', 'Seo'), '#', 'key ', Module::isManager(), [
            TController::addMenu(\Yii::t('app', 'Seo'), '//seo/admin/manager', 'lock', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Analytics'), '//seo/admin/analytics', 'area-chart', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Redirect'), '//seo/redirect', 'reply', Module::isManager())
        ]);
    }

    public static function getRules()
    {
        return [
            'seo' => 'seo/default/index'
        ];
    }
}
