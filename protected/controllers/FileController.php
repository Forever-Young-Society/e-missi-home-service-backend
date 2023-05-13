<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\controllers;

use alexantr\elfinder\CKEditorAction;
use alexantr\elfinder\ConnectorAction;
use alexantr\elfinder\InputFileAction;
use app\components\TController;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\File;
use app\models\User;
use app\models\search\File as FileSearch;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends TController
{

    public function actions()
    {
        return [
            'connector' => [
                'class' => ConnectorAction::class,
                'options' => [
                    'roots' => [
                        [
                            'driver' => 'LocalFileSystem',
                            'path' => UPLOAD_PATH,
                            'URL' => Url::to([
                                '/file/files'
                            ]),
                            'mimeDetect' => 'internal',
                            'imgLib' => 'gd',
                            'debug' => true,
                            'accessControl' => function ($attr, $path) {
                                // hide files/folders which begins with dot
                                return (strpos(basename($path), '.') === 0) ? ! ($attr == 'read' || $attr == 'write') : null;
                            }
                        ]
                    ]
                ]
            ],
            'input' => [
                'class' => InputFileAction::class,
                'connectorRoute' => 'connector'
            ],
            'image' => [
                'class' => 'app\components\actions\ImageAction',
                'modelClass' => File::class,
                'attribute' => 'key',
                'default' => true
            ],
            'ckeditor' => [
                'class' => CKEditorAction::class,
                'connectorRoute' => 'connector'
            ] /*
               * ,
               * 'tinymce' => [
               * 'class' => TinyMCEAction::class,
               * 'connectorRoute' => 'connector',
               * ],
               */
        ];
    }

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
                            'index',
                            'view',

                            'ajax',
                            'files',
                            'connector',
                            'input',
                            'ckeditor',
                            'files',
                            'view',
                            'image'
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'mass',
                            'clear',
                            'delete',
                            'image'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'files',
                            'view',
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

    /**
     * Lists all File models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionFiles($file)
    {
        if (strstr($file, '..')) {
            throw new NotFoundHttpException(\Yii::t('app', "File not found"));
        }
        $image_path = UPLOAD_PATH . basename($file);
        if (! file_exists($image_path)) {
            throw new NotFoundHttpException(\Yii::t('app', "File not found" . $image_path));
        }
        return \yii::$app->response->sendFile($image_path, $file);
    }

    /**
     * Displays a single File model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, false);
        $file = $model->getFullPath();

        if (is_file($file))
            return Yii::$app->response->sendFile($file);
        throw new NotFoundHttpException(Yii::t('app', "File not found"));
    }

    /**
     * Deletes an existing File model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        if (\Yii::$app->request->isAjax) {
            return true;
        }
        \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'File Deleted Successfully.'));
        return $this->redirect([
            'index'
        ]);
    }

    public function actionMass($action = 'delete')
    {
        \Yii::$app->response->format = 'json';
        $response['status'] = 'NOK';
        $status = File::massDelete('delete');
        if ($status == true) {
            $response['status'] = 'OK';
        }
        return $response;
    }

    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return File the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = File::findOne($id)) !== null) {

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
                {}
                break;

            default:
            case 'view':
                {
                    $this->menu['manage'] = [
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ],
                        'visible' => User::isAdmin()
                    ];
                    if ($model != null) {
                        $this->menu['update'] = [
                            'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                            'title' => Yii::t('app', 'Update'),
                            'url' => [
                                'update',
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
