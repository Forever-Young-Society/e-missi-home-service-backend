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
namespace app\modules\smtp\controllers;

use app\components\TActiveForm;
use app\components\TController;
use app\models\User;
use app\modules\smtp\models\Account;
use app\modules\smtp\models\ImportForm;
use app\modules\smtp\models\ReplyForm;
use app\modules\smtp\models\search\Account as AccountSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class AccountController extends TController
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
                            'mass',
                            'export',
                            'test',
                            'import'
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
     * Lists all Account models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Account model.
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
     * Creates a new Account model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new Account();
        $model->loadDefaultValues();
        $model->state_id = Account::STATE_ACTIVE;
        $model->port = '587';
        $model->encryption_type = Account::TYPE_ENCRYPTION_TLS;
        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $model->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($model);
        }
        if ($model->load($post)) {
            if (isset($post['Account']['password']))
                $model->setEncryptedPassword($post['Account']['password']);

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Added Successfully!');
                return $this->redirect($model->getUrl());
            }
        }
        $this->updateMenuItems();
        return $this->render('add', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Account model.
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
        $model->password = $model->getDecryptedPassword($model->password);
        $password = $model->password;
        if ($model->load($post)) {
            if (! empty($post['Account']['password']))
                $model->setEncryptedPassword($post['Account']['password']);
            else
                $model->password = $model->setEncryptedPassword($password);
            if ($model->save())
                \Yii::$app->session->setFlash('success', 'Updated Successfully!');
            return $this->redirect($model->getUrl());
        }

        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Clone an existing Account model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClone($id)
    {
        $old = $this->findModel($id);

        $model = new Account();
        $model->loadDefaultValues();
        $model->state_id = Account::STATE_ACTIVE;

        // $model->id = $old->id;
        $model->title = $old->title;
        $model->email = $old->email;
        // $model->password = $old->password;
        $model->server = $old->server;
        $model->port = $old->port;
        $model->encryption_type = $old->encryption_type;
        $model->limit_per_email = $old->limit_per_email;
        // $model->state_id = $old->state_id;
        $model->type_id = $old->type_id;
        // $model->created_on = $old->created_on;
        $model->updated_on = $old->updated_on;
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
     * Deletes an existing Account model.
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
            \Yii::$app->session->setFlash('app', 'Deleted Successfully!');
            return $this->redirect([
                'index'
            ]);
        }
        return $this->render('delete', [
            'model' => $model
        ]);
    }

    public function actionTest($id)
    {
        $model = $this->findModel($id);

        $test = new ReplyForm();

        $post = \yii::$app->request->post();
        if (\yii::$app->request->isAjax && $test->load($post)) {
            \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return TActiveForm::validate($test);
        }
        if ($test->load($post)) {
            $model->test($test->email, $model->email);
            return $this->redirect([
                'view',
                'id' => $model->id
            ]);
        }
        $this->updateMenuItems($model);
        return $this->render('test', [
            'model' => $model,
            'test' => $test
        ]);
    }

    public function actionExport($id)
    {
        $rule = $this->findModel($id);

        $file = tempnam(sys_get_temp_dir(), str_replace(' ', '-', $rule->title));
        file_put_contents($file, json_encode($rule->asJson(true)));

        return Yii::$app->response->sendFile($file, basename($file) . '.json');
    }

    public function actionImport()
    {
        $item = [];
        $import = new ImportForm();

        $model = new Account();

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

                $str = file_get_contents($filename);
                $json = json_decode($str, true);

                $model->setAttributes($json);
                $model->setEncryptedPassword($model->password);
                if ($model->save()) {
                    $this->redirect([
                        'view',
                        'id' => $model->id
                    ]);
                }
            }
        }
        return $this->render('import', [
            'model' => $model,
            'import' => $import
        ]);
    }

    /**
     * Truncate an existing Account model.
     * If truncate is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionClear($truncate = true)
    {
        $query = Account::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Account::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Account Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Account the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Account::findOne($id)) !== null) {

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
                        ],
                        'visible' => User::isAdmin()
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
                            'data-confirm' => "Are you sure to delete all items?"
                        ],
                        'visible' => User::isAdmin()
                    ];
                }
                break;
            case 'update':
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
                        $this->menu['clone'] = array(
                            'label' => '<span class="glyphicon glyphicon-copy">Clone</span>',
                            'title' => Yii::t('app', 'Clone'),
                            'url' => $model->getUrl('clone')
                            // 'visible' => User::isAdmin ()
                        );
                        $this->menu['update'] = [
                            'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                            'title' => Yii::t('app', 'Update'),
                            'url' => $model->getUrl('update'),
                            'visible' => User::isAdmin()
                        ];
                        $this->menu['test'] = [
                            'label' => '<span class="glyphicon glyphicon-ok-circle"></span>',
                            'title' => Yii::t('app', 'Test'),
                            'url' => [
                                'test',
                                'id' => $model->id
                            ],
                            'visible' => User::isAdmin()
                        ];
                        $this->menu['export'] = [
                            'label' => '<span class="glyphicon glyphicon-print">Export</span>',
                            'title' => Yii::t('app', 'Export'),
                            'url' => $model->getUrl('export')
                        ];
                        $this->menu['delete'] = [
                            'label' => '<span class="glyphicon glyphicon-trash"></span>',
                            'title' => Yii::t('app', 'Delete'),
                            'url' => $model->getUrl('delete'),
                            'visible' => User::isAdmin()
                        ];
                    }
                }
        }
    }
}
