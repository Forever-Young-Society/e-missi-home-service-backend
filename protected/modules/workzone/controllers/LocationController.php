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
namespace app\modules\workzone\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\User;
use app\modules\workzone\models\Location;
use app\modules\workzone\models\Postcode;
use app\modules\workzone\models\search\Location as LocationSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\modules\workzone\models\Zone;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * LocationController implements the CRUD actions for Location model.
 */
class LocationController extends TController
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
                            'ajax',
                            'final-delete'
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
     * Lists all Location models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Location model.
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

    public function actionAdd(/* $id*/)
    {
        $model = new Location();
        $model->loadDefaultValues();
        $model->state_id = Location::STATE_ACTIVE;

        /*
         * if (is_numeric($id)) {
         * $post = Post::findOne($id);
         * if ($post == null)
         * {
         * throw new NotFoundHttpException('The requested post does not exist.');
         * }
         * $model->id = $id;
         *
         * }
         */

        $model->checkRelatedData([
            'created_by_id' => User::class
        ]);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {
            $primary_location = Zone::findOne($model->primary_location);
            if (! empty($primary_location)) {
                $model->title = $primary_location->title;
            }
            if ($model->save()) {
                return $this->redirect($model->getUrl());
            }
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Location model.
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
        if ($model->load($post)) {
            $primary_location = Zone::findOne($model->primary_location);
            if (! empty($primary_location)) {
                $model->title = $primary_location->title;
            }
            if ($model->save()) {
                return $this->redirect($model->getUrl());
            }
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Clone an existing Location model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClone($id)
    {
        $old = $this->findModel($id);

        $model = new Location();
        $model->loadDefaultValues();
        $model->state_id = Location::STATE_ACTIVE;

        // $model->id = $old->id;
        $model->title = $old->title;
        $model->secondary_location = $old->secondary_location;
        $model->type_id = $old->type_id;
        // $model->state_id = $old->state_id;
        // $model->created_on = $old->created_on;
        // $model->created_by_id = $old->created_by_id;

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
     * Deletes an existing Location model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (\yii::$app->request->post()) {
            $model->updateAttributes([
                'state_id' => Location::STATE_DELETED
            ]);
            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('delete', [
            'model' => $model
        ]);
    }

    public function actionFinalDelete($id)
    {
        $model = $this->findModel($id);
        $this->updateMenuItems($model);
        if (\Yii::$app->request->isPost) {

            if ($model->checkIsWorkzoneSelectedByProvider()) {
                \Yii::$app->getSession()->setFlash('warning', \Yii::t('app', 'You can not hard delete as it is selected by service provider'));
                return $this->redirect([
                    'view',
                    'id' => $id
                ]);
            }
            $model->delete();
            if (\Yii::$app->request->isAjax) {
                return true;
            }
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Workzone Deleted Successfully.'));

            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('final-delete', [
            'model' => $model
        ]);
    }

    /**
     * Truncate an existing Location model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = Location::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Location::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Location Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Location::findOne($id)) !== null) {

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
                    $this->menu['index'] = [
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
                    $this->menu['import'] = [
                        'label' => '<span class="glyphicon glyphicon-import"></span>',
                        'title' => Yii::t('app', 'Import'),
                        'url' => [
                            'import'
                        ],
                        'visible' => false
                    ];
                }
                break;
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
                    $this->menu['index'] = [
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
                    $this->menu['index'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    ];
                    if ($model != null) {
                        $this->menu['clone'] = [
                            'label' => '<span class="glyphicon glyphicon-copy">Clone</span>',
                            'title' => Yii::t('app', 'Clone'),
                            'url' => $model->getUrl('clone'),
                            'visible' => false
                        ];
                        $this->menu['update'] = [
                            'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                            'title' => Yii::t('app', 'Update'),
                            'url' => $model->getUrl('update')
                            // 'visible' => User::isAdmin ()
                        ];
                        $this->menu['delete'] = [
                            'label' => '<span class="glyphicon glyphicon-trash"></span>',
                            'title' => Yii::t('app', 'Delete'),
                            'url' => $model->getUrl('final-delete'),
                            'visible' => false
                        ];
                    }
                }
        }
    }
}
