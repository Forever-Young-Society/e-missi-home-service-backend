<?php
namespace app\controllers;

use app\components\TController;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;

class DashboardController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class
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

    public function actionIndex()
    {
        if (User::isAdmin()) {
            $this->updateMenuItems();
            return $this->render('index');
        } else {
            return $this->redirect([
                '/'
            ]);
        }
    }
}
