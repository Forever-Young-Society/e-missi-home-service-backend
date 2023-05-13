<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\settings\commands;

use app\components\TConsoleController;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class ConfigController extends TConsoleController
{

    /**
     * enable/disbale erp
     *
     * @return number
     */
    public function actionSet($key, $value = 1)
    {
        
        \Yii::$app->settings->setValue($key, $value);
    }

    public function actionGet($key)
    {
        echo \Yii::$app->settings->getValue($key);
    }
}
