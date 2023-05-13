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
namespace app\components\commands;

use app\components\TConsoleController;
use app\components\helpers\TArrayHelper;
use Yii;
use yii\console\ExitCode;

class ModuleController extends TConsoleController
{

    public $module_name = null;

    public function options($actionID)
    {
        return TArrayHelper::merge(parent::options($actionID), [
            'module_name'
        ]);
    }

    public function optionAliases()
    {
        return TArrayHelper::merge(parent::optionAliases(), [
            'm' => 'module_name'
        ]);
    }

    public static function moduleList()
    {
        $config = include (DB_CONFIG_PATH . 'web.php');
        $webmodules = array_keys($config['modules']);

        $configConsole = include (DB_CONFIG_PATH . 'console.php');
        $consoleModules = array_keys($configConsole['modules']);

        $allmodules = array_merge($webmodules, $consoleModules);

        return array_unique($allmodules);
    }

    public function actionMigrate()
    {
        self::log('Run migration on all modules');
        $modules = self::moduleList();
        if (empty($modules)) {
            self::log('No modules found');
            return ExitCode::NOUSER;
        }
        foreach ($modules as $module) {

            if ($this->module_name && $this->module_name != $module) {
                self::log($module . ' :skip:' . $this->module_name);
                continue;
            }
            $path = Yii::$app->basePath . '/modules/' . $module . '/migrations';

            self::log(' Checking path:' . $path);
            if (is_dir($path)) {
                try {
                    self::log('Run on withPaths:' . $path);

                    if (! $this->dryRun) {
                        Yii::$app->runAction("migrate", [
                            'migrationPath' => $path,
                            'interactive' => 0
                        ]);
                    }
                } catch (\Exception $ex) {
                    self::log($ex->getMessage());
                    self::log($ex->getTraceAsString());
                }
            }
        }
        self::log('Run migration on app');
        Yii::$app->runAction("migrate", [
            'interactive' => 0
        ]);

        Yii::$app->cache->flush();
        return ExitCode::OK;
    }
}
