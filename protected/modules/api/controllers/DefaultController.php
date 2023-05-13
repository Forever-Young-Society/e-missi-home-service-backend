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
namespace app\modules\api\controllers;

use app\components\TController;
use Yii;
use yii\web\Response;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;

/**
 * Default controller for the `Api` module
 */
class DefaultController extends TController
{

    /**
     * Renders the index view for the module
     *
     * @return string
     */

    /**
     *
     * @OA\Info(
     *   version="1.0",
     *   title="Application API",
     *   description="Userimplements the API actions for User model.",
     *   @OA\Contact(
     *     name="Shiv Charan Panjeta",
     *     email="shiv@toxsl.com",
     *   ),
     * ),
     * @OA\SecurityScheme(
     * securityScheme="bearerAuth",
     * in="header",
     * name="bearerAuth",
     * type="http",
     * scheme="bearer",
     * )
     *   @OA\Server(
     *   url="http://192.168.2.171/e-missi-home-service-yii2-1843/api",
     *   description="local server",
     * )
     *   @OA\Server(
     *   url="http://localhost/e-missi-home-service-yii2-1843/api",
     *   description="local server",
     * )
     * @OA\Server(
     *   url="https://dev.toxsl.in/e-missi-home-service-yii2-1843/api",
     *   description="dev server",
     * ),
     *
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'json'
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

    public function actions()
    {
        return [
            'index' => [
                'class' => 'genxoft\swagger\ViewAction',
                'apiJsonUrl' => \yii\helpers\Url::to([
                    '/api/default/json'
                ], true)
            ],
            'json' => [
                'class' => 'genxoft\swagger\JsonAction',
                'dirs' => [
                    Yii::getAlias('@app/modules/logger/controllers'),
                    Yii::getAlias('@app/modules/api/controllers'),
                    Yii::getAlias('@app/modules/api/models'),
                    Yii::getAlias('@app/models')
                ]
            ]
        ];
    }

    public function beforeAction($action)
    {
        if (! parent::beforeAction($action)) {
            return false;
        }

        $this->layout = 'guest-main';

        return true;
    }
}
