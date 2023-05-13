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
namespace app\modules\logger\controllers;

use app\components\TController;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;
use app\modules\logger\models\SettingsForm;
use app\modules\logger\models\Log;

/**
 * Default controller for the `log` module
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
                            'settings',
                            'technology',
                            'toggle-env'
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

        $post = \Yii::$app->request->post();
        if ($model->load($post)) {
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

    /**
     * Renders the System Info view
     *
     * @return string
     */
    public function actionTechnology()
    {
        $info['Generic'] = [
            'App Name' => \Yii::$app->name,
            'App ID' => PROJECT_ID,
            // 'App Version' => VERSION,
            'Environment' => YII_ENV,
            'Company Name' => \Yii::$app->params['company']
            // 'Domain' => Yii::$app->params['domain']
        ];
        ob_start();
        phpinfo();
        $pinfo = ob_get_contents();
        ob_end_clean();

        $info['Technical'] = $pinfo;
        return $this->render('technology', [
            'model' => $info
        ]);
    }

    /**
     * Toggle Env
     *
     * @return \yii\web\Response
     */
    public function actionToggleEnv()
    {
        Log::toggleEnv();
        \Yii::$app->getSession()->setFlash("success", 'Env Change');
        return $this->redirect([
            'technology'
        ]);
    }
}
