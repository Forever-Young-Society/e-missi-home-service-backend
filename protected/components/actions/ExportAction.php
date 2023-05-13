<?php
namespace app\components\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class ExportAction extends Action
{

    /**
     * Export action
     *
     * public function actions()
     * {
     * return [
     *
     * 'export' => [
     * 'class' => 'app\components\actions\ExportAction',
     * 'modelClass' => Gateway::class,
     * ]
     * ];
     * }
     */

    /**
     *
     * @var string name of the model
     */
    public $modelClass;

    /**
     *
     * @var string pk field name
     */
    public $primaryKey = 'id';

    public function run($id)
    {
        if (empty($this->modelClass) || ! class_exists($this->modelClass)) {
            throw new InvalidConfigException("Model class doesn't exist");
        }
        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $this->modelClass;

        $model = $modelClass::find()->where([
            $this->primaryKey => $id
        ])->one();

        if (is_null($model)) {
            throw new NotFoundHttpException("Model  doesn't exist");
        }
        if (! $model->isAllowed()) {
            throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page.'));
        }
        $file = $model->id . '-' . str_replace(' ', '-', $model->title) . '.json';

        return Yii::$app->response->sendContentAsFile(json_encode($model->asJson(true)), $file);
    }
}
?>