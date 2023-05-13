<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\actions;

use Imagine\Image\ManipulatorInterface;
use app\components\helpers\TFileHelper;
use app\models\File;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\imagine\Image;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\VarDumper;

/**
 * Image action
 *
 * public function actions()
 * {
 * return [
 *
 * 'image' => [
 * 'class' => 'app\components\actions\ImageAction',
 * 'modelClass' => Post::class,
 * 'attribute'=> 'image_file,
 * 'default' => true [or] \Yii::$app->view->theme->basePath . '/img/default.jpg'
 * ]
 * ];
 * }
 */
class ImageAction extends Action
{

    /**
     *
     * @var string name of the model
     */
    public $modelClass;

    /**
     *
     * @var string model attribute
     */
    public $attribute = 'image_file';

    /**
     *
     * @var string image path
     */
    public $isAbsolutePath = false;

    /**
     *
     * @var string default attribute
     */
    public $default;

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
    public function run($id, $file = null, $thumbnail = false)
    {
        if (Yii::$app->request->getIsPost()) {
            throw new MethodNotAllowedHttpException();
        }
        $id = (int) $id;

        if (empty($this->modelClass) || ! class_exists($this->modelClass)) {
            throw new InvalidConfigException("Model class doesn't exist");
        }
        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $this->modelClass;

        $model = $modelClass::find()->where([
            $this->primaryKey => $id
        ]);

        $model = $model->one();
        if (is_null($model)) {
            throw new NotFoundHttpException("Model  doesn't exist");
        }

        if (! $model->hasProperty($this->attribute)) {
            throw new InvalidConfigException("Attribute doesn't exist");
        }

        $attribute = $this->attribute;

        if (empty($model->$attribute)) {
            if (isset($this->default)) {
                if (is_file($this->default)) {
                    $file = $this->default;
                } elseif ($this->default == true) {
                    $file = \Yii::$app->view->theme->basePath . '/img/default.jpg';
                } else {
                    throw new NotFoundHttpException(Yii::t('app', "File not found"));
                }
            }
        } else {
            if ($this->isAbsolutePath) {
                $file = $model->$attribute;
            } else {
                $file = UPLOAD_PATH . $model->$attribute;
            }
        }

        if (is_numeric($model->$attribute)) {
            $f = File::findOne($model->$attribute);
            if ($f) {
                $file = $f->getFullPath();
            }
        }
        if (empty($file) || ! is_file($file)) {
            throw new NotFoundHttpException(Yii::t('app', "File not found"));
        }
        $mime = TFileHelper::getMimeType($file);
        // skip png
        if (! stristr($mime, 'image/png') && $thumbnail) {
            $h = is_numeric($thumbnail) ? $thumbnail : 100;

            $thumb_path = Yii::$app->runtimePath . '/thumbnails';

            if (! is_dir($thumb_path)) {
                TFileHelper::createDirectory($thumb_path);
            }

            $thumb_path_file = $thumb_path . '/' . $h . '_' . $id . basename($file);
            if (is_file($thumb_path_file)) {
                $file = $thumb_path_file;
            } else {
                try {
                    $img = Image::thumbnail($file, null, $h, ManipulatorInterface::THUMBNAIL_INSET);

                    $img->save($thumb_path_file);
                    $file = $thumb_path_file;
                } catch (\Imagine\Exception\RuntimeException $e) {
                    // echo $e->getMessage();
                } catch (\Imagine\Exception\InvalidArgumentException $e) {
                    // echo $e->getMessage();
                }
            }
        }
        @ob_clean();
        return Yii::$app->response->sendFile($file);
    }
}