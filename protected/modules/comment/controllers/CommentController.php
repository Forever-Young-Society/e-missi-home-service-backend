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
namespace app\modules\comment\controllers;

use app\components\TController;
use app\models\User;
use app\modules\comment\models\Comment;
use app\modules\comment\models\search\Comment as CommentSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends TController
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
                            'index',
                            'clear',
                            'view'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ],
                    [
                        'actions' => [
                            'get'
                        ],
                        'allow' => true,
                        'roles' => [
                            '*',
                            '@'
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
     * Lists all Comment models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        $this->updateMenuItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Lists all Comment models.
     *
     * @return mixed
     */
    public function actionGet($model, $id, $u = null, $q = null)
    {
        if (! class_exists($model)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $object = $model::findOne($id);

        if ($object == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (! $object->isAllowed()) {
            throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $object->getComments(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $dataProvider->query->andFilterWhere([
            'created_by' => $u
        ]);

        $this->updateMenuItems();
        return $this->render('comments', [
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
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->getModel())
            $url = $model->getModel()->getUrl();

        $model->delete();

        if ($url) {
            return $this->redirect($url);
        }

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
}
