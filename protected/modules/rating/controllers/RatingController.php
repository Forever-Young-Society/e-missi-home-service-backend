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
namespace app\modules\rating\controllers;

use Yii;
use app\modules\rating\models\Rating;
use app\modules\rating\models\search\Rating as RatingSearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;

/**
 * RatingController implements the CRUD actions for Rating model.
 */
class RatingController extends TController
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
                            'clear',
                            'delete',
                            'index',
                            'add',
                            'view',
                            'update',
                            'clone',
                            'ajax',
                            'mass'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [

                            'index',
                            'add',
                            'view',
                            'ajax'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isSeller() || User::isUser();
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Rating models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RatingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Rating model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model
            ]);
        }
        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * Creates a new Rating model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd()
    {
        $post = \yii::$app->request->post();
        if (! empty($post)) {
            $model = Rating::find()->where([
                'model_id' => $post['Rating']['model_id'],
                'model_type' => $post['Rating']['model_type'],
                'created_by_id' => \Yii::$app->user->id
            ])->one();

            if (empty($model)) {

                $model = new Rating();
            }

            $model->loadDefaultValues();
            $model->state_id = Rating::STATE_ACTIVE;

            if (\yii::$app->request->isAjax && $model->load($post)) {
                \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return TActiveForm::validate($model);
            }
            if ($model->load($post)) {

                if ($model->save()) {
                    $msg = ($model->isNewRecord) ? \Yii::t('app', "added") : \Yii::t('app', "updated");
                    \Yii::$app->getSession()->setFlash('success', \Yii::t('app', "Rating has been $msg Successfully."));
                } else {
                    \Yii::$app->getSession()->setFlash('error', $model->getErrorsString());
                }
            }
        } else {
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', "No Data Posted."));
        }

        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Updates an existing Rating model.
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
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', "Record has been updated Successfully."));
            return $this->redirect($model->getUrl());
        }
        $this->updateMenuItems($model);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model
            ]);
        }
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Clone an existing Rating model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClone($id)
    {
        $old = $this->findModel($id);

        $model = new Rating();
        $model->loadDefaultValues();
        $model->state_id = Rating::STATE_ACTIVE;

        $model->model_id = $old->model_id;
        $model->model_type = $old->model_type;
        $model->rating = $old->rating;
        $model->comment = $old->comment;
        // $model->state_id = $old->state_id $model->state_id = $old->state_id;
        $model->type_id = $old->type_id;
        // $model->created_on = $old->created_on $model->created_on = $old->created_on;
        $model->updated_on = $old->updated_on;
        // $model->created_by_id = $old->created_by_id $model->created_by_id = $old->created_by_id;

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
     * Deletes an existing Rating model.
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
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', "Record has been deleted Successfully."));
            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('delete', [
            'model' => $model
        ]);
    }

    /**
     * Truncate an existing Rating model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = Rating::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Rating::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Rating Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Rating model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Rating the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Rating::findOne($id)) !== null) {

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

            case 'add':
                {
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                }
                break;
            case 'index':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
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
            case 'update':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                }
                break;

            default:
            case 'view':
                {
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    if ($model != null) {
                        $this->menu['clone'] = array(
                            'label' => '<span class="glyphicon glyphicon-file">Clone</span>',
                            'title' => Yii::t('app', 'Clone'),
                            'url' => $model->getUrl('clone')
                            // 'visible' => User::isAdmin ()
                        );
                        $this->menu['update'] = [
                            'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                            'title' => Yii::t('app', 'Update'),
                            'url' => $model->getUrl('update')
                            // 'visible' => User::isAdmin ()
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
