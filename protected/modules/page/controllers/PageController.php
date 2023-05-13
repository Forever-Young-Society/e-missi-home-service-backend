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
namespace app\modules\page\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\User;
use app\modules\page\models\Page;
use app\modules\page\models\search\Page as PageSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends TController
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
                            'add',
                            'view',
                            'update',
                            'ajax',
                            'mass',
                            'delete',
                            'save',
                            'clear',
                            'save-content'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
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
     * actionMass delete in mass as items are checked
     *
     * @param string $action
     * @return string
     */
    public function actionMass($action = 'delete')
    {
        \Yii::$app->response->format = 'json';
        $response['status'] = 'NOK';
        $status = Page::massDelete();
        if ($status == true) {
            $response['status'] = 'OK';
        }
        return $response;
    }

    /**
     * Lists all Page models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Page model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $title = null)
    {
        $model = $this->findModel($id);
        if (is_null($title))
            $this->redirect($model->getUrl());
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

    public function actionSave($id)
    {
        $post = \yii::$app->request->post();
        if (! empty($post)) {
            $model = $this->findModel($id);
            $model->description = $_POST['editabledata'];
            $model->save();
        }
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new Page();

        $model->loadDefaultValues();
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {
                if ($model->save()) {

                    \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Page Added Successfully.'));
                    return $this->redirect($model->getUrl());
                } else {
                    \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
                }
          
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
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
        if ($model->load($post)) {
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Page Updated Successfully.'));
                return $this->redirect($model->getUrl());
            } else {
                \Yii::$app->getSession()->setFlash('error', "Error !!" . $model->getErrorsString());
            }
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
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Page Deleted Successfully.'));
        return $this->redirect([
            'index'
        ]);
    }

    public function actionClear($truncate = true)
    {
        $query = Page::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Page::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Done !!!');
        return $this->redirect([
            'index'
        ]);
    }

    public function actionSaveContent($id = null, $title = null, $type = null)
    {
        Yii::$app->response->format = "json";
        $data = [
            'status' => 'NOK'
        ];
        $post = Yii::$app->request->post('contentTools0');
        if (! empty($post)) {

            $page = Page::findOne($id);
            if (empty($page)) {
                $page = new Page();
                $page->created_on = date("Y-m-d H:i:s");
                $page->updated_on = date("Y-m-d H:i:s");
                $page->created_by_id = Yii::$app->user->id;
                $page->type_id = $type;
            }

            $page->title = $title;

            if (! empty($page)) {
                $page->description = $post;
                if ($page->save(false, [
                    'description',
                    'title',
                    'created_on',
                    'updated_on',
                    'created_by_id',
                    'type_id'
                ])) {
                    $data['status'] = "OK";
                }
            }
        }
        return $data;
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {

            if (! ($model->isAllowed()))
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
                    $this->menu['clear'] = [
                        'label' => '<span class=" glyphicon glyphicon-remove"></span>',
                        'title' => Yii::t('app', 'Clear'),
                        'url' => [
                            'clear'
                            /* 'id' => $model->id */
                        ],
                        'visible' => User::isAdmin()
                    ];
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add'
                        ]
                        // 'visible' => User::isAdmin ()
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
                    if ($model != null) {
                        $this->menu['index'] = [
                            'label' => '<span class="glyphicon glyphicon-list"></span>',
                            'title' => Yii::t('app', 'Manage'),
                            'url' => [
                                'index'
                            ]
                            // 'visible' => User::isAdmin ()
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
                            'url' => $model->getUrl('delete'),
                            'htmlOptions' => [
                                'data-method' => 'post',
                                'data-confirm' => 'Do you really want to delete this user?'
                            ]
                            // 'visible' => User::isAdmin ()
                        ];
                    }
                }
        }
    }
}
