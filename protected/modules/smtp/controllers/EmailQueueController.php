<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\smtp\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\File;
use app\models\User;
use app\modules\comment\models\Comment;
use app\modules\smtp\models\EmailQueue;
use app\modules\smtp\models\Unsubscribe;
use app\modules\smtp\models\search\EmailQueue as EmailQueueSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * EmailQueueController implements the CRUD actions for EmailQueue model.
 */
class EmailQueueController extends TController
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
                            'delete',
                            'clear',
                            'mass',
                            'update'
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
                            'ajax',
                            'send-now',
                            'ajax',
                            'view',
                            'show',
                            'image'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isManager();
                        }
                    ],
                    [
                        'actions' => [
                            'unsubscribe',
                            'subscribe',
                            'show',
                            'image'
                        ],
                        'allow' => true,
                        'roles' => [
                            '?',
                            '*'
                        ]
                    ]
                ]
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => [
                        'post'
                    ]
                ]
            ]
        ];
    }

    public function actionMass($action = 'delete')
    {
        \Yii::$app->response->format = 'json';
        $response['status'] = 'NOK';
        $Ids = Yii::$app->request->post('ids');
        foreach ($Ids as $Id) {
            $model = $this->findModel($Id);

            if ($action == 'delete') {
                if (! $model->delete()) {
                    return $response['status'] = 'NOK';
                }
            }
        }

        $response['status'] = 'OK';

        return $response;
    }

    public function actionClear($truncate = true)
    {
        $query = EmailQueue::find();
        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                $model->delete();
            }
        }
        File::deleteRelatedAll([
            'model_type' => EmailQueue::class
        ]);
        Comment::deleteRelatedAll([
            'model_type' => EmailQueue::class
        ]);
        if ($truncate) {
            EmailQueue::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Done !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Lists all EmailQueue models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmailQueueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect($model->getUrl());
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Displays a single EmailQueue model.
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

    public function actionSendNow($id)
    {
        $model = $this->findModel($id);
        if ($model->state_id == EmailQueue::STATE_PENDING) {
            $model->sendNow();
        }
        return $this->redirect($model->getUrl());
    }

    /**
     * Deletes an existing EmailQueue model.
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

    public function actionShow($id, $footer = 1)
    {
        $model = $this->findModel($id, false);
        if ($model == null)
            throw new NotFoundHttpException('The requested page does not exist.');
        echo $model->content . $model->getFooter($footer);
        exit();
    }

    public function actionUnsubscribe($id)
    {
        $this->layout = 'guest-out';
        $model = $this->findModel($id, false);
        Unsubscribe::add($model->to);
        return $this->render('unsubscribe-view', [
            'model' => $model
        ]);
    }

    public function actionSubscribe($id)
    {
        $this->layout = 'guest-out';
        $model = $this->findModel($id, false);
        Unsubscribe::remove($model->to);
        return $this->render('subscribe-view', [
            'model' => $model
        ]);
    }

    public function actionImage($id)
    {
        $model = $this->findModel($id, false);

        $campaign = $model->getModel();

        EmailQueue::log('image called on model:' . $campaign);

        if ($campaign && method_exists($campaign, 'handleSeen')) {
            EmailQueue::log('handleSeen called on :' . $campaign);
            $campaign->handleSeen();
            if ($model->state_id == EmailQueue::STATE_SENT) {

                $model->state_id = EmailQueue::STATE_SEEN;
                $model->save();
            }
        }

        header("Content-Type: image/png");
        $image = imagecreate(1, 1);

        $text_color = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
        imagestring($image, 5, 5, 5, $id, $text_color);

        imagepng($image);
        imagedestroy($image);
        exit();
    }

    /**
     * Finds the EmailQueue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return EmailQueue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = EmailQueue::findOne($id)) !== null) {

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
                            'data-confirm' => "Are you sure to delete all items?"
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
                        ],
                        'visible' => User::isAdmin()
                    ];
                    if ($model != null) {
                        /* $previous = $model->getPreviousItem();
                        if ($previous) {
                            $this->menu['previous'] = [
                                'label' => '<span class="glyphicon glyphicon-arrow-left"></span>',
                                'title' => Yii::t('app', 'Previous'),
                                'url' => $previous->getUrl()
                            ];
                        }
                        $next = $model->getNextItem();
                        if ($next) {
                            $this->menu['next'] = [
                                'label' => '<span class="glyphicon glyphicon-arrow-right"></span>',
                                'title' => Yii::t('app', 'Next'),
                                'url' => $next->getUrl()
                            ];
                        } */
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
                            'url' => [
                                'delete',
                                'id' => $model->id
                            ],
                            'visible' => User::isAdmin()
                        ];
                    }
                }
        }
    }
}
