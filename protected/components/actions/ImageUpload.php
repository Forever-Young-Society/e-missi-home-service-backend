<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\actions;

use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\models\File;
use yii\helpers\StringHelper;
use app\components\helpers\TFileHelper;

/**
 * Image upload action
 *
 * public function actions()
 * {
 * return [
 *
 * 'image-upload' => [
 * 'class' => 'app\components\actions\ImageUpload',
 * 'modelClass' => Profile::class,
 * 'attribute' => 'profile_image',
 * 'useFileObject' => true
 * ]
 * ];
 * }
 */
class ImageUpload extends Action
{

    /**
     *
     * @var string name of the model
     */
    public $modelClass;

    /**
     *
     * @var string name of the model
     */
    public $useFileObject = false;

    /**
     *
     * @var string model attribute
     */
    public $attribute = 'image_file';

    /**
     *
     * @var string pk field name
     */
    public $primaryKey = 'id';

    /**
     * Run the action
     *
     * @param $id integer
     *            id of model to be loaded
     *
     * @throws \yii\web\MethodNotAllowedHttpException
     * @throws \yii\base\InvalidConfigException
     * @return mixed
     */
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

        $post = \Yii::$app->request->post();
        if (isset($post['image'])) {
            $data = $post['image'];
            $image_array_1 = explode(";", $data);
            $image_array_2 = explode(",", $image_array_1[1]);
            $imagedata = base64_decode($image_array_2[1]);
            if ($this->useFileObject) {
                $myimgName = $model . '_' . time() . '.png';
                $image = File::add($model, $imagedata, $myimgName, true);
                return $image->getImageUrl();
            } else {
                $myimgName = $model . '_' . time() . '.png';
                if ($model->hasAttribute($this->attribute)) {
                    $row = $this->attribute;
                    $models = StringHelper::dirname(get_class($model));
                    $module = StringHelper::dirname($models);
                    $dir = StringHelper::basename($module);
                    if ($dir == 'app') {
                        $dir = '.';
                    }
                    $dir = $dir . '/' . StringHelper::basename(get_class($model));

                    if (! is_dir(UPLOAD_PATH . $dir)) {
                        TFileHelper::createDirectory(UPLOAD_PATH . $dir);
                    }
                    $filename = $model->id . '_' . preg_replace("/[^A-Za-z0-9\_\-\.]/", '-', $myimgName);

                    $filename = $dir . '/' . $filename;
                    if (is_file(UPLOAD_PATH . $filename)) {
                        TFileHelper::unlink(UPLOAD_PATH . $filename);
                    }
                    @file_put_contents(UPLOAD_PATH . $filename, $imagedata);
                    $model->$row = $filename;
                    $model->updateAttributes([
                        $row
                    ]);
                    return $model->getImageUrl();
                } else {}
            }
        }
        return false;
    }
}