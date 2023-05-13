<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\message;

use app\models\Message;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\File;

/**
 * This is just an example.
 */
class MessagesWidget extends \yii\base\Widget
{

    /**
     *
     * @var Model
     */
    public $model;

    public $readOnly = false;

    public $disabled = false;

    /*
     * public function rules() {
     * return
     * [
     * [
     * 'uploaded_file'
     * ],
     * 'file',
     * 'skipOnEmpty' => true,
     * 'extensions' => 'jpg'
     * ];
     *
     * }
     */
    protected function getRecentMessages()
    {
        if ($this->model == null)
            return null;
        $query = Message::find()->where([
            'model_type' => get_class($this->model),
            'project_id' => $this->model->id
        ])->orderBy('id ASC');
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    protected function formModel()
    {
        $message = null;
        if ($this->readOnly == false) {
            $message = new Message();
            $message->loadDefaultValues();
            $message->model_type = get_class($this->model);
            $message->project_id = $this->model->id;
        }
        return $message;
    }

    public function run()
    {
        if ($this->disabled)
            return; // Do nothing
        $message = new Message();
        $message->loadDefaultValues();
        if (isset($_FILES['Message'])) {
            $uploaded_file = UploadedFile::getInstance($message, 'file');
            if ($uploaded_file != null && File::add($this->model, $uploaded_file)) {
                $_POST['Message']['message'] = 'File uploaded ' . $uploaded_file->getBaseName() . $uploaded_file->getExtension();
            }
        }
        if (isset($_POST['Message'])) {
            $message->load($_POST);
            $message->model_type = get_class($this->model);
            $message->project_id = $this->model->id;
            $message->state_id = 0;
            $message->type_id = 0;
            if (! $message->save()) {
                VarDumper::dump($message->errors);
            } else {
               //$this->goBack();
            }
        }
        return $this->render('messages', [
            'messages' => $this->getRecentMessages(),
            'model' => $this->formModel()
        ]);
    }
}
