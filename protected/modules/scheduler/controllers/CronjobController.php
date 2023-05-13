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
namespace app\modules\scheduler\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\User;
use app\modules\scheduler\models\Cronjob;
use app\modules\scheduler\models\ImportForm;
use app\modules\scheduler\models\Type;
use app\modules\scheduler\models\search\Cronjob as CronjobSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * CronjobController implements the CRUD actions for Cronjob model.
 */
class CronjobController extends TController
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
                            'delete'
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
                            'update',
                            'clone',
                            'ajax',
                            'next',
                            'export',
                            'import',
                            'run'
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
     * Lists all Cronjob models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CronjobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Cronjob model.
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
     * Run a single Cronjob model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionRun($id)
    {
        $model = $this->findModel($id);
        $log = $model->scheduleNext(true);

        if ($log && $log->runAction()) {
            \Yii::$app->session->setFlash('success', 'Executed successfully!!');
        } else {
            \Yii::$app->session->setFlash('error', 'Cron Failed!!');
        }

        $this->updateMenuItems($model);
        return $this->redirect($model->getUrl());
    }

    /**
     * Displays a single Cronjob model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionNext($id)
    {
        $model = $this->findModel($id);
        $model->scheduleNext();
        $this->updateMenuItems($model);
        return $this->redirect($model->getUrl());
    }

    /**
     * Creates a new Cronjob model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new Cronjob();
        $model->loadDefaultValues();
        $model->state_id = Cronjob::STATE_ACTIVE;
        $model->checkRelatedData([
            'created_by_id' => User::class,
            'type_id' => Type::class
        ]);
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post) && $model->save()) {
            return $this->redirect($model->getUrl());
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Cronjob model.
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
     * Export a single Cronjob model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionExport()
    {
        $content = '';
        $jobQuery = Cronjob::find();
        foreach ($jobQuery->each() as $job) {
            $content .= $job->exportText() . PHP_EOL;
        }

        $file = str_replace(' ', '-', 'Cronjob') . '.txt';

        return Yii::$app->response->sendContentAsFile($content, $file);
    }

    /**
     * Import a single Cronjob model.
     *
     *
     * @return mixed
     */
    public function actionImport()
    {
        $item = [];

        $import = new ImportForm();

        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $import->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($import);
        }

        $fileset = false;
        if ($import->load($post)) {
            $uploaded_file = UploadedFile::getInstance($import, "file");
            if ($uploaded_file != null) {

                $filename = $uploaded_file->tempName;

                $lines = file($filename);
                foreach ($lines as $line) {
                    Cronjob::importline($line);
                }
                return $this->redirect([
                    'index'
                ]);
            }
        }
        return $this->render('import', [
            'import' => $import
        ]);
    }

    /**
     * Clone an existing Cronjob model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClone($id)
    {
        $old = $this->findModel($id);

        $model = new Cronjob();
        $model->loadDefaultValues();
        $model->state_id = Cronjob::STATE_ACTIVE;

        // $model->id = $old->id;
        $model->title = $old->title;
        $model->when = $old->when;
        $model->command = $old->command;
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
     * Deletes an existing Cronjob model.
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
     * Truncate an existing Cronjob model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = Cronjob::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Cronjob::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Cronjob Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Cronjob model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Cronjob the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Cronjob::findOne($id)) !== null) {

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
                    $this->menu['export'] = [
                        'label' => '<span class="glyphicon glyphicon-export">Export</span>',
                        'title' => Yii::t('app', 'Export'),
                        'url' => [
                            'export'
                        ]
                    ];
                    $this->menu['import'] = [
                        'label' => '<span class="glyphicon glyphicon-import">Import</span>',
                        'title' => Yii::t('app', 'Add'),
                        'url' => [
                            'import'
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
                        $this->menu['run'] = [
                            'label' => '<span class="glyphicon glyphicon-plus"></span>Run',
                            'title' => Yii::t('app', 'Run Now'),
                            'url' => $model->getUrl('run')
                            // 'visible' => User::isAdmin ()
                        ];
                        $this->menu['next'] = [
                            'label' => '<span class="glyphicon glyphicon-plus">Next</span>',
                            'title' => Yii::t('app', 'Next'),
                            'url' => $model->getUrl('next')
                            // 'visible' => User::isAdmin ()
                        ];
                        $this->menu['clone'] = [
                            'label' => '<span class="glyphicon glyphicon-copy">Clone</span>',
                            'title' => Yii::t('app', 'Clone'),
                            'url' => $model->getUrl('clone')
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
                            'url' => $model->getUrl('delete')
                            // 'visible' => User::isAdmin ()
                        ];
                    }
                }
        }
    }
}
