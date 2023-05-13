<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\comment\widgets;

use app\components\TBaseWidget;
use app\models\File;
use app\models\User;
use app\modules\comment\models\Comment;
use Yii;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is just an example.
 */
class CommentsWidget extends TBaseWidget
{

    public $model;

    public $readOnly = false;

    public $disabled = false;

    public $type = 1;

    protected function getRecentComments()
    {
        if ($this->model == null)
            return null;

        $query = Comment::find()->where([
            'model_type' => get_class($this->model),
            'model_id' => $this->model->id
        ])->orderBy('id DESC');
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    protected function getRecentCommentsUrl()
    {
        return Url::toRoute([
            '/comment/comment/get',
            'model' => get_class($this->model),
            'id' => $this->model->id
        ]);
    }

    public function run()
    {
        if ($this->disabled) {
            return; // Do nothing
        }

        if (User::isGuest() && $this->type == 2) {

            return $this->render('facebook');
        }

        $comment = new Comment();
        $comment->loadDefaultValues();

        if (isset($_FILES['Comment'])) {
            $uploaded_file = UploadedFile::getInstance($comment, 'file');
            if ($uploaded_file != null) {
                $file = File::add($this->model, $uploaded_file);

                if ($file != null) {
                    $_POST['Comment']['comment'] = $_POST['Comment']['comment'] . '<br/><br/>File uploaded ' . $file->linkify();
                } else {
                    $comment->addError('comment', 'File upload error');
                }
            }
        }

        if (! empty($_POST['Comment']['comment'])) {

            $comment->load($_POST);
            $comment->model_type = get_class($this->model);
            $comment->model_id = $this->model->id;
            $comment->state_id = 0;
            $comment->comment = nl2br($comment->comment);
            if ($comment->save()) {
                \Yii::$app->controller->redirect(\Yii::$app->request->referrer);
            }
            TagDependency::invalidate(Yii::$app->cache, $this->model->getCacheTag());
        }
        return $this->render('comments', [
            'model' => $comment,
            'url' => $this->getRecentCommentsUrl()
        ]);
    }
}
