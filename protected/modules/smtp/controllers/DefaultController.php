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
namespace app\modules\smtp\controllers;

use app\components\TController;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;
use app\modules\smtp\models\SettingsForm;

/**
 * Default controller for the `smtp` module
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
                            'index',
                            'settings'
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
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSettings()
    {
        $model = new SettingsForm();

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {

                return $this->redirect([
                    'settings'
                ]);
            }
        }
        return $this->render('settings', [

            'model' => $model
        ]);
    }
}
