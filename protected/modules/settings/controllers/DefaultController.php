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
namespace app\modules\settings\controllers;

use app\components\TController;
use app\components\filters\AccessControl;
use app\models\User;
use yii\filters\AccessRule;
use yii\helpers\Url;

/**
 * Default controller for the `settings` module
 */
class DefaultController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex($module_id = null)
    {
        $this->updateMenuItems();
        $items = [];

        $config = include (DB_CONFIG_PATH . 'web.php');

        if (! empty($config['modules'])) {
            foreach ($config['modules'] as $key => $module) {

                $moduleName = ucfirst($key);

                $defaultController = '\\app\\modules\\' . $key . '\\controllers\\DefaultController';

                \Yii::warning('Default Controller : ' . $defaultController);
                if (! class_exists($defaultController) || ! method_exists($defaultController, 'actionSettings')) {
                    continue;
                }
                \Yii::warning('Module Name : ' . $moduleName);
                $items[$moduleName] = [
                    'label' => $moduleName,
                    'url' => Url::toRoute([
                        '/' . $key . '/default/settings'
                    ])
                ];
            }
        }

        return $this->render('index', [
            'items' => $items
        ]);
    }
}
