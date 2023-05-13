<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use app\modules\comment\models\SettingsForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model use app\modules\comment\models\Comment */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="comment-form">

    <?php

    $form = TActiveForm::begin([
        'enableAjaxValidation' => false,
        'enableClientValidation' => false
    ]);
    $moduleSettings = new SettingsForm();
    if ($moduleSettings->richText) {

        echo $form->field($model, 'comment')
            ->label(false)
            ->widget(app\components\TRichTextEditor::className(), [
            'options' => [
                'rows' => 6
            ],
            'preset' => 'basic'
        ]);
    } else {
        echo $form->field($model, 'comment')->textarea([
            'rows' => 6
        ]);
    }
    ?>


    <?php  echo $form->field($model, 'file')->fileInput()->label("Upload File");?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php TActiveForm::end(); ?>

</div>
