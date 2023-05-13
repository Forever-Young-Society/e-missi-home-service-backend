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
namespace app\modules\service\controllers;

use Yii;
use app\modules\service\models\SubCategory;
use app\modules\service\models\search\SubCategory as SubCategorySearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
use app\modules\service\models\Category;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

/**
 * SubCategoryController implements the CRUD actions for SubCategory model.
 */
class SubCategoryController extends TController
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
                            'final-delete',
                            'direct-booking'
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
     * Lists all SubCategory models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            's.type_id' => SubCategory::TYPE_SUB_SERVICE_YES,
            's.service_type' => SubCategory::SERVICE_TYPE_NORMAL
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Lists all SubCategory models.
     *
     * @return mixed
     */
    public function actionDirectBooking()
    {
        $searchModel = new SubCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            's.type_id' => SubCategory::TYPE_SUB_SERVICE_YES,
            's.service_type' => SubCategory::SERVICE_TYPE_DIRECT_BOOKING
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single SubCategory model.
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

    public function actionAjax($type, $id, $function, $grid = '_ajax-grid', $addMenu = true, $action = null)
    {
        $model = $type::findOne([
            'id' => $id
        ]);
        if (! empty($model)) {

            if (! ($model->isAllowed()))
                // throw new \yii\web\HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));
                exit();
            $function = 'get' . ucfirst($function);
            $query = $model->$function();
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_ASC
                    ]
                ],
                'pagination' => [
                    'pageSize' => 10
                ]
            ]);
            $menu = [];
            if ($model && $addMenu) {

                if ($action != null) {
                    if (strstr($action, '/')) {
                        $menu['url'] = Url::toRoute($action, [
                            'id' => $model->id
                        ]);
                    } else {
                        $menu['url'] = $model->getUrl($action);
                    }
                } else {
                    $linkModel = new $query->modelClass();
                    $action = 'add';
                    $menu['url'] = $linkModel->getUrl($action, $model->id);
                }
                $menu['label'] = '<i class="fa fa-plus"></i> <span></span>';
                $menu['htmlOptions'] = [
                    'class' => 'btn btn-success pull-right',
                    'title' => $action
                ];
            }
            return $this->renderAjax($grid, [
                'dataProvider' => $dataProvider,
                'searchModel' => null,
                'id' => $id,
                'menu' => $menu
            ]);
        }
    }

    /**
     * Creates a new SubCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd($service_type = SubCategory::SERVICE_TYPE_NORMAL, $id = '')
    {
        $model = new SubCategory();
        $model->loadDefaultValues();
        $model->state_id = SubCategory::STATE_ACTIVE;
        $model->service_type = SubCategory::SERVICE_TYPE_NORMAL;
        if ($service_type == SubCategory::SERVICE_TYPE_DIRECT_BOOKING) {
            $categoryModel = Category::findOne([
                'type_id' => Category::TYPE_NURSING_SERVICE
            ]);
            if (! empty($categoryModel)) {
                $model->service_type = $service_type;
                $model->category_id = $categoryModel->id;
                $exist = SubCategory::findOne([
                    'service_type' => SubCategory::SERVICE_TYPE_DIRECT_BOOKING
                ]);
                if (! empty($exist)) {
                    $model = $exist;
                }
            }
        }

        if (is_numeric($id)) {
            $category = Category::findOne($id);
            if ($category == null) {
                throw new NotFoundHttpException('The requested post does not exist.');
            }
            $model->category_id = $id;
        }

        $model->checkRelatedData([
            'created_by_id' => User::class
        ]);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }

        if ($model->load($post)) {
            $model->type_id = SubCategory::TYPE_SUB_SERVICE_YES;
            if ($model->save()) {

                if (! empty($model->sub_service)) {
                    SubCategory::updateAll([
                        'state_id' => SubCategory::STATE_DELETED
                    ], [
                        'parent_id' => $model->id
                    ]);
                    foreach ($model->sub_service as $key => $value) {
                        if (! empty($value)) {
                            $title = trim($value);
                            $existsModel = SubCategory::find()->where([
                                'parent_id' => $model->id,
                                'LOWER(title)' => strtolower($title)
                            ])->one();
                            if (empty($existsModel)) {
                                $subSkillModel = new SubCategory();
                                $subSkillModel->title = $title;
                                $subSkillModel->price = $model->price;
                                $subSkillModel->provider_price = $model->provider_price;
                                $subSkillModel->type_id = SubCategory::TYPE_SUB_SERVICE_NO;
                                $subSkillModel->parent_id = $model->id;
                                $subSkillModel->category_id = $model->category_id;
                                $subSkillModel->state_id = SubCategory::STATE_ACTIVE;
                                if (! $subSkillModel->save()) {
                                    \Yii::$app->session->setFlash('error', $subSkillModel->getErrorsString());
                                }
                            } else {
                                $existsModel->updateAttributes([
                                    'state_id' => SubCategory::STATE_ACTIVE
                                ]);
                            }
                        }
                    }
                }

                \Yii::$app->session->setFlash('success', 'Service Added.');
                if (! empty($id)) {
                    return $this->redirect(Url::toRoute([
                        '/service/category/view',
                        'id' => $model->category_id
                    ]));
                }
                return $this->redirect([
                    'index'
                ]);
            }
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model,
            'service_type' => $service_type
        ]);
    }

    /**
     * Updates an existing SubCategory model.
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
            $model->type_id = SubCategory::TYPE_SUB_SERVICE_YES;
            if ($model->save()) {
                if (! empty($model->sub_service)) {
                    SubCategory::updateAll([
                        'state_id' => SubCategory::STATE_DELETED
                    ], [
                        'parent_id' => $model->id
                    ]);
                    foreach ($model->sub_service as $key => $value) {
                        if (! empty($value)) {
                            $title = trim($value);
                            $existsModel = SubCategory::find()->where([
                                'parent_id' => $model->id,
                                'LOWER(title)' => strtolower($title)
                            ])->one();
                            if (empty($existsModel)) {
                                $subSkillModel = new SubCategory();
                                $subSkillModel->title = $title;
                                $subSkillModel->price = $model->price;
                                $subSkillModel->provider_price = $model->provider_price;
                                $subSkillModel->type_id = SubCategory::TYPE_SUB_SERVICE_NO;
                                $subSkillModel->parent_id = $model->id;
                                $subSkillModel->category_id = $model->category_id;
                                $subSkillModel->state_id = SubCategory::STATE_ACTIVE;
                                if (! $subSkillModel->save()) {

                                    \Yii::$app->session->setFlash('error', $subSkillModel->getErrorsString());
                                }
                            } else {
                                $existsModel->updateAttributes([
                                    'state_id' => SubCategory::STATE_ACTIVE
                                ]);
                            }
                        }
                    }
                }
                return $this->redirect($model->getUrl());
            }
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Clone an existing SubCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClone($id)
    {
        $old = $this->findModel($id);

        $model = new SubCategory();
        $model->loadDefaultValues();
        $model->state_id = SubCategory::STATE_ACTIVE;

        // $model->id = $old->id;
        $model->title = $old->title;
        $model->image_file = $old->image_file;
        $model->category_id = $old->category_id;
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
     * Deletes an existing SubCategory model.
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
                'state_id' => Category::STATE_DELETED
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

            if ($model->checkIsSkillSelectedByProvider()) {
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
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Skill Deleted Successfully.'));

            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('final-delete', [
            'model' => $model
        ]);
    }

    /**
     * Truncate an existing SubCategory model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = SubCategory::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            SubCategory::truncate();
        }
        \Yii::$app->session->setFlash('success', 'SubCategory Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the SubCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return SubCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = SubCategory::findOne($id)) !== null) {

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
                    $this->menu['clear'] = [
                        'label' => '<span class="glyphicon glyphicon-remove"></span>',
                        'title' => Yii::t('app', 'Clear'),
                        'url' => [
                            'clear'
                        ],
                        'htmlOptions' => [
                            'data-confirm' => "Are you sure to delete these items?"
                        ],
                        'visible' => false
                    ];
                }
                break;
            case 'direct-booking':
                {
                    $this->menu['add'] = [
                        'label' => '<span class="glyphicon glyphicon-plus"></span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'add',
                            'service_type' => SubCategory::SERVICE_TYPE_DIRECT_BOOKING
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
                            'url' => $model->getUrl('delete'),
                            'visible' => false
                        ];
                        if ($model != null)
                            $this->menu['final-delete'] = [
                                'label' => '<span class="glyphicon glyphicon-trash"></span>',
                                'title' => Yii::t('app', 'Final Delete'),
                                'url' => $model->getUrl('final-delete'),
                                'class' => 'btn btn-danger',
                                'visible' => true
                            ];
                    }
                }
        }
    }
}
