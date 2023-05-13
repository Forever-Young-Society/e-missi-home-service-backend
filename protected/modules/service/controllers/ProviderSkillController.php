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
use app\modules\service\models\ProviderSkill;
use app\modules\service\models\search\ProviderSkill as ProviderSkillSearch;
use app\components\TController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use app\models\User;
use yii\web\HttpException;
use app\components\TActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * ProviderSkillController implements the CRUD actions for ProviderSkill model.
 */
class ProviderSkillController extends TController
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
                            'mass'
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
     * Lists all ProviderSkill models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProviderSkillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            'type_id' => ProviderSkill::TYPE_SUB_SKILL_YES
        ]);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single ProviderSkill model.
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
     * Creates a new ProviderSkill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd(/* $id*/)
    {
        $model = new ProviderSkill();
        $model->loadDefaultValues();
        $model->state_id = ProviderSkill::STATE_ACTIVE;
        $model->checkRelatedData([
            'created_by_id' => User::class
        ]);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {
            $model->type_id = ProviderSkill::TYPE_SUB_SKILL_YES;
            if ($model->save()) {
                if (! empty($model->sub_skill)) {
                    ProviderSkill::updateAll([
                        'state_id' => ProviderSkill::STATE_DELETED
                    ], [
                        'parent_id' => $model->id
                    ]);
                    foreach ($model->sub_skill as $key => $value) {
                        if (! empty($value)) {
                            $title = trim($value);
                            $existsModel = ProviderSkill::find()->where([
                                'parent_id' => $model->id,
                                'LOWER(title)' => strtolower($title)
                            ])->one();
                            if (empty($existsModel)) {
                                $subSkillModel = new ProviderSkill();
                                $subSkillModel->title = $title;
                                $subSkillModel->type_id = ProviderSkill::TYPE_SUB_SKILL_NO;
                                $subSkillModel->parent_id = $model->id;
                                $subSkillModel->category_id = $model->category_id;
                                $subSkillModel->state_id = ProviderSkill::STATE_ACTIVE;
                                if (! $subSkillModel->save()) {
                                    \Yii::$app->session->setFlash('error', $subSkillModel->getErrorsString());
                                }
                            } else {
                                $existsModel->updateAttributes([
                                    'state_id' => ProviderSkill::STATE_ACTIVE
                                ]);
                            }
                        }
                    }
                }

                return $this->redirect($model->getUrl());
            }
        }
        $this->updateMenuItems();
        return $this->render('add', [
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
     * Updates an existing ProviderSkill model.
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
            $model->type_id = ProviderSkill::TYPE_SUB_SKILL_YES;
            if ($model->save()) {
                if (! empty($model->sub_skill)) {
                    ProviderSkill::updateAll([
                        'state_id' => ProviderSkill::STATE_DELETED
                    ], [
                        'parent_id' => $model->id
                    ]);
                    foreach ($model->sub_skill as $key => $value) {
                        if (! empty($value)) {
                            $title = trim($value);
                            $existsModel = ProviderSkill::find()->where([
                                'parent_id' => $model->id,
                                'LOWER(title)' => strtolower($title)
                            ])->one();
                            if (empty($existsModel)) {
                                $subSkillModel = new ProviderSkill();
                                $subSkillModel->title = $title;
                                $subSkillModel->type_id = ProviderSkill::TYPE_SUB_SKILL_NO;
                                $subSkillModel->parent_id = $model->id;
                                $subSkillModel->category_id = $model->category_id;
                                $subSkillModel->state_id = ProviderSkill::STATE_ACTIVE;
                                if (! $subSkillModel->save()) {
                                    \Yii::$app->session->setFlash('error', $subSkillModel->getErrorsString());
                                }
                            } else {
                                $existsModel->updateAttributes([
                                    'state_id' => ProviderSkill::STATE_ACTIVE
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
     * Clone an existing ProviderSkill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClone($id)
    {
        $old = $this->findModel($id);

        $model = new ProviderSkill();
        $model->loadDefaultValues();
        $model->state_id = ProviderSkill::STATE_ACTIVE;

        // $model->id = $old->id;
        $model->title = $old->title;
        $model->image_file = $old->image_file;
        $model->category_id = $old->category_id;
        $model->parent_id = $old->parent_id;
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
     * Deletes an existing ProviderSkill model.
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
     * Truncate an existing ProviderSkill model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = ProviderSkill::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            ProviderSkill::truncate();
        }
        \Yii::$app->session->setFlash('success', 'ProviderSkill Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the ProviderSkill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return ProviderSkill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = ProviderSkill::findOne($id)) !== null) {

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
                        ],
                        'visible' => ($model->type_id == ProviderSkill::TYPE_SUB_SKILL_YES)
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
                            'url' => $model->getUrl('update'),
                            'visible' => ($model->type_id == ProviderSkill::TYPE_SUB_SKILL_YES)
                        ];
                        $this->menu['delete'] = [
                            'label' => '<span class="glyphicon glyphicon-trash"></span>',
                            'title' => Yii::t('app', 'Delete'),
                            'url' => $model->getUrl('delete'),
                            'visible' => false
                        ];
                    }
                }
        }
    }
}
