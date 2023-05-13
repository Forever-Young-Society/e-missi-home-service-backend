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
namespace app\modules\notification\controllers;

use app\components\TController;
use app\models\User;
use app\modules\notification\models\Notification;
use app\modules\notification\models\search\Notification as NotificationSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * NotificationController implements the CRUD actions for Notification model.
 */
class NotificationController extends TController
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
                            'delete',
                            'notify',
                            'ajax',
                            'mass'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isManager();
                        }
                    ],
                    [
                        'actions' => [
                            'delete',
                            'mass',
                            'clear',
                            'manage'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'notify',
                            'read'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
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
     * Lists all Notification models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NotificationSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->my('to_user_id');

        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Lists all Notification models.
     *
     * @return mixed
     */
    public function actionManage()
    {
        $searchModel = new NotificationSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionRead()
    {
        $query = Notification::find()->andWhere([
            'is_read' => Notification::IS_NOT_READ,
            'to_user_id' => \Yii::$app->user->id
        ]);

        $count = $query->count();

        if ($count > 0) {

            foreach ($query->each() as $notification) {
                $notification->state_id = Notification::STATE_DELETED;
                $notification->is_read = Notification::IS_NOT_READ;
                $notification->updateAttributes([
                    'state_id',
                    'is_read'
                ]);
                $notification->delete();
            }
        }
        return $this->redirect([
            'index'
        ]);
    }

    public function actionNotify()
    {
        \Yii::$app->response->format = 'json';
        $response = [
            'status' => '400',
            'count' => 0
        ];

        $query = Notification::find()->cache()->where([
            'is_read' => Notification::IS_NOT_READ,
            'to_user_id' => \Yii::$app->user->id
        ]);

        $count = $query->count();

        $response['message'] = $count . ' New Notificaions for: ' . \Yii::$app->user->identity;

        if ($count > 0) {
            $query->orderBy('ID DESC');
            $query->limit(10);
            foreach ($query->each() as $notification) {
                $url = $notification->getModel() ? $notification->getModel()->getUrl() : $notification->getUrl();
                $time = \Yii::$app->formatter->asRelativeTime(strtotime($notification->created_on));
                $description = '';
                if ($notification->description) {
                    $description = StringHelper::truncate($notification->description, 600);
                }
                $response['message'] .= $notification->title . PHP_EOL;
                $response['data'][] = [
                    'key' => $notification->id,
                    'html' => "<a class='content' data-id='{$notification->id}' href='$url'> <div class='notification-item'>     						
                        <p class='item-title'> $notification->title   <spna class='pull-right'> $time </span></p>     					
                        <p class='item-info'>$description</p> 				
                        </div> 				 
                        </a>"
                ];
            }

            $response['status'] = 200;
            $response['count'] = $count;
        }

        return $response;
    }

    public function actionClear($truncate = true)
    {
        $query = Notification::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Notification::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Done !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Displays a single Notification model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $model->is_read = Notification::IS_READ;
        $model->updateAttributes([
            'is_read' => true
        ]);
        $this->updateMenuItems($model);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Notification model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Notification model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Notification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Notification::findOne($id)) !== null) {

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
                    $this->menu['clear'] = array(
                        'label' => '<span class=" glyphicon glyphicon-remove"></span>',
                        'title' => Yii::t('app', 'Clear'),
                        'url' => [
                            'clear'
                            /* 'id' => $model->id */
                        ],
                        'visible' => User::isAdmin()
                    );
                }
                break;
            case 'view':
                {
                    $this->menu['index'] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    );
                    if ($model != null) {
                        $this->menu['delete'] = array(
                            'label' => '<span class="glyphicon glyphicon-trash"></span>',
                            'title' => Yii::t('app', 'Delete'),
                            'url' => [
                                'delete',
                                'id' => $model->id
                            ]
                            // 'visible' => User::isAdmin ()
                        );
                    }
                }
        }
    }
}
