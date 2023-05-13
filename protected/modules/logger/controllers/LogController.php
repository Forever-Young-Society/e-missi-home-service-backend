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

use Yii;
use app\modules\logger\models\Log;
use app\modules\logger\models\search\Log as LogSearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
use yii\helpers\Url;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends TController
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
                            'view',
                            'mass',
                            'clear',
                            'send-now',
                            'delete'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'custom-error',
                            'exception'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@',
                            '?',
                            '*'
                        ]
                    ]
                ]
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Log models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Log model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Log model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (\yii::$app->request->post()) {
            $model->delete();
            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('delete', [
            'model' => $model
        ]);
    }

    /**
     * Truncate an existing Log model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = Log::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Log::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Log Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    public function actionCustomError()
    {
        if (\Yii::$app->user->isGuest) {
            $this->layout = "guest-main";
        }

        $exception = Yii::$app->errorHandler->exception;

        if ($exception == null) {
            return $this->redirect(Url::home());
        }

        $name = $exception->getMessage();

        if ($exception instanceof yii\web\HttpException) {
            $status = (int) $exception->statusCode;
        } else {
            $status = 500;
        }

        if ($status == 404 && \yii::$app->getModule('seo')) {
            $class = 'app\modules\seo\models\Redirect';

            if (class_exists($class) && method_exists($class, 'check')) {
                $class::check();
            }
        }
        if (YII_ENV == 'prod' && ! in_array($status, [
            403,
            404,
            400,
            406
        ])) {

            $name = "Functionality is restricted. ";
            Log::addException($exception, $status);
        }

        return $this->render('error', [
            'name' => $name,
            'status' => $status
        ]);
    }

    public function beforeAction($action)
    {
        if ($action->id == 'exception') {
            $this->enableCsrfValidation = false;
        }
        if (! parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    /**
     *
     * @OA\Post(path="/log/exception",
     *   summary="Create new log",
     *   tags={"Logger"},
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"Log[user_ip]","Log[link]","Log[error]"},
     *              @OA\Property(property="Log[error]", type="string", example="400",description=""),
     *              @OA\Property(property="Log[description]", type="string", example="Error Description",description=""),
     *              @OA\Property(property="Log[link]", type="string", example="http://localhost/yii2-admin-panel-rest-1392/api/log/expeption",description=""),
     *              @OA\Property(property="Log[user_ip]", type="string", example="192.168.10.0",description=""),
     *              @OA\Property(property="Log[referer_link]", type="string", example="http://localhost/taxi-booking-yii2-1821/api/log/expeption",description="")
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new log",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionException()
    {
        \Yii::$app->response->format = "json";
        $data = [];
        $post = \Yii::$app->request->post();
        $model = new Log();
        if ($model->load($post)) {
            $model->type_id = Log::TYPE_APP;
            $model->description = "StackTrace :" . $model->description . PHP_EOL;
            $model->description .= "Package Name: " . $model->error . PHP_EOL;
            $model->description .= "Package Version: " . $model->link . PHP_EOL;
            $model->description .= "Android Version: " . $model->user_ip . PHP_EOL;
            $model->description .= "Phone Model :" . $model->referer_link . PHP_EOL;
            $model->description .= 'User Agent : ' . \Yii::$app->request->getUserAgent() . PHP_EOL;
            if ($model->save()) {
                $model->sendMailToAdmin();
                $data['message'] = \Yii::t('app', 'Log is created');
                return $data;
            }
            $data['message'] = $model->getErrorsString();
            return $data;
        }
        $data['message'] = \Yii::t('app', 'Data Not Posted');
        return $data;
    }

    public function actionSendNow($id)
    {
        $model = $this->findModel($id);

        $model->sendMailToAdmin();
        return $this->redirect($model->getUrl());
    }

    /**
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Log::findOne($id)) !== null) {

            if ($accessCheck && ! ($model->isAllowed()))
                throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));

            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function updateMenuItems($model = null)
    {
        switch (\Yii::$app->controller->action->id) {

            case 'index':
                {

                    $this->menu['clear'] = [
                        'label' => '<span class="glyphicon glyphicon-remove"></span>',
                        'title' => Yii::t('app', 'Clear'),
                        'url' => [
                            'clear'
                        ],
                        'htmlOptions' => [
                            'data-confirm' => "Are you sure to delete these items?"
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                break;

            default:
            case 'view':
                {
                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    if ($model != null) {
                        $this->menu['send-now'] = [
                            'label' => '<span class="glyphicon glyphicon-cog"></span>',
                            'title' => Yii::t('app', 'Send Now'),
                            'url' => [
                                'send-now',
                                'id' => $model->id
                            ],
                            'visible' => User::isAdmin()
                        ];
                        $this->menu['delete'] = [
                            'label' => '<span class="glyphicon glyphicon-trash"></span>',
                            'title' => Yii::t('app', 'Delete'),
                            'url' => $model->getUrl('delete')
                            // 'visible' => User::isAdmin ()
                        ];
                    }
                }
        }
    }
}
