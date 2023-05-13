<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\api\components;

use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

abstract class ApiBaseController extends ActiveController
{

    public $serializer = 'app\modules\api\components\TSerializer';

    public $modelSearchClass;

    public function init()
    {
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->loginUrl = null;
        \Yii::$app->response->format = "json";
        \Yii::$app->user->enableAutoLogin = false;

        parent::init();
        if ($this->modelSearchClass === null) {
            $this->modelSearchClass = str_replace('model\\', 'model\\search\\', $this->modelClass);
        }
    }

    public function behaviorsUpdate($behaviors = [])
    {
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];

        $behaviors['verbFilter'] = [
            'class' => VerbFilter::className(),
            'actions' => $this->verbs()
        ];

        $behaviors['authenticator'] = [
            'class' => TRestAuth::className()
        ];

        return $behaviors;
    }

    /* Declare methods supported by APIs */
    protected function verbs()
    {
        return [
            'create' => [
                'POST'
            ],
            'update' => [
                'PUT',
                'PATCH',
                'POST'
            ],
            'delete' => [
                'DELETE'
            ],
            'view' => [
                'GET'
            ],
            'index' => [
                'GET'
            ]
        ];
    }

    public function beforeAction($action)
    {
        $behaviors = $this->behaviorsUpdate($this->behaviors);
        // $access = $behaviors['access'];
        // unset($behaviors['access']);
        $this->detachBehavior('access');
        // $behaviors['access'] = $access;
        $this->attachBehaviors(array_reverse($behaviors));

        if (! parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    public function setStatus($status_code, $status_message = '')
    {
        \Yii::$app->response->setStatusCode($status_code, trim(preg_replace('/\s\s+/', ' ', $status_message)));
    }

    public function txDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            $this->setStatus(200);
            $data['message'] = $this->modelClass . ' is deleted Successfully.';
        }
        return $data;
    }

    public function txSave($fileAttributes = [])
    {
        $model = new $this->modelClass();
        if ($model->load(Yii::$app->request->post())) {
            foreach ($fileAttributes as $file) {
                $model->saveUploadedFile($model, $file);
            }
            if ($model->save()) {
                $this->setStatus(200);
                $data['detail'] = $model;
            } else {
                $err = '';
                foreach ($model->getErrors() as $error) {
                    $err .= implode(',', $error);
                }
                $data['message'] = $err;
            }
        }
        return $data;
    }

    public function txGet($id)
    {
        $model = $this->findModel($id);
        $data['detail'] = $model->asJson();
        $this->setStatus(200);
        return $data;
    }

    public function txIndex($modelSearchClass = null)
    {
        if ($modelSearchClass == null) {
            $modelSearchClass = $this->modelSearchClass;
        }
        $model = new $modelSearchClass();
        $dataProvider = $model->search(\Yii::$app->request->bodyParams);
        $this->setStatus(200);
        return $dataProvider;
    }

    protected function findModel($id, $modelClass = null)
    {
        if ($modelClass == null) {
            $modelClass = $this->modelClass;
        }
        if (($model = $modelClass::findOne($id)) !== null) {
            if (! ($model->isAllowed()))
                throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function serializeData($data)
    {
        $alldata = Yii::createObject($this->serializer)->serialize($data);
        if (defined('DATECHECK')) {
            $alldata['datecheck'] = DATECHECK;
        }
        $alldata['copyrighths'] = 'ToXSL';
        return $alldata;
    }
}
