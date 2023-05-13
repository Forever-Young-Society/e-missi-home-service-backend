<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\comment\controllers\admin;

use app\components\TActiveForm;
use app\models\User;
use app\modules\comment\controllers\CommentController as BaseCommentController;
use app\modules\comment\models\Comment;
use app\modules\comment\models\search\Comment as CommentSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends BaseCommentController
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
                            'index',
                            'view',
                            'update',
                            'delete',
                            'ajax',
                            'mass',
                            'clear'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
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
     * Lists all Comment models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Comment model.
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
     * Updates an existing Comment model.
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
            return $this->redirect([
                'view',
                'id' => $model->id
            ]);
        }
        $this->updateMenuItems($model);
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = Comment::find()->where([
            'id' => $id
        ])->one();
        
        if ($model->getModel())
            $url = $model->getModel()->getUrl();
        
        $model->delete();
        
        if ($url)
            return $this->redirect($url);
        
        return $this->redirect([
            'index'
        ]);
    }

    public function actionClear($truncate = true)
    {
        $query = Comment::find();
        foreach ($query->each() as $model) {
            $model->delete();
        }
        if ($truncate) {
            Comment::truncate();
        }
        \Yii::$app->session->setFlash('success', 'Comments Cleared !!!');
        return $this->redirect([
            'index'
        ]);
    }
    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $accessCheck = true)
    {
        if (($model = Comment::findOne($id)) !== null) {
            
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
            
            case 'update':
                {
                    $this->menu['index'] = array(
                        'label' => '<span class="glyphicon glyphicon-list"></span>',
                        'title' => Yii::t('app', 'Manage'),
                        'url' => [
                            'index'
                        ]
                        // 'visible' => User::isAdmin ()
                    );
                }
                break;
            default:
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
                    $this->menu['clear'] = array(
                        'label' => '<span class=" glyphicon glyphicon-remove"></span>',
                        'title' => Yii::t('app', 'Clear'),
                        'url' => [
                            'clear'
                            /* 'id' => $model->id */
                        ],
                        'htmlOptions' => [
                            'data-confirm' => "Are you sure to delete all items?"
                        ],
                        'visible' => User::isAdmin()
                    );
                    if ($model != null) {
                        $this->menu['update'] = array(
                            'label' => '<span class="glyphicon glyphicon-pencil"></span>',
                            'title' => Yii::t('app', 'Update'),
                            'url' => [
                                'update',
                                'id' => $model->id
                            ]
                            // 'visible' => User::isAdmin ()
                        );
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
