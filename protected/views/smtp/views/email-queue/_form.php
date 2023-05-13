<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\smtp\models\EmailQueue */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([
    //
    'layout' => TActiveForm::LAYOUT_HORIZONTAL,
    'id' => 'email-queue-form'
]);
// echo $form->errorSummary($model);
?>
         <div class="col-md-6">
              <?php echo $form->field($model, 'from')->textInput(['maxlength' => 128]) ?>
              <?php echo $form->field($model, 'to')->textInput(['maxlength' => 128]) ?>
              <?php echo $form->field($model, 'subject')->textInput(['maxlength' => 255]) ?>
              <?php echo $form->field($model, 'cc')->textInput(['maxlength' => 128]) ?>
              <?php echo $form->field($model, 'bcc')->textInput(['maxlength' => 128]) ?>
              <?php echo  $form->field($model, 'content')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'content')->textarea(['rows' => 6]); */ ?>
              <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
              <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
              <?php }?>               
         </div>
	<div class="col-md-6">
                  <?php /*echo $form->field($model, 'attempts')->textInput() */ ?>
                              <?php
                            /*
                             * echo $form->field($model, 'sent_on')->widget(yii\jui\DatePicker::class,
                             * [
                             * //'dateFormat' => 'php:Y-m-d',
                             * 'options' => [ 'class' => 'form-control' ],
                             * 'clientOptions' =>
                             * [
                             * 'minDate' => date('Y-m-d'),
                             * 'maxDate' => date('Y-m-d',strtotime('+30 days')),
                             * 'changeMonth' => true,'changeYear' => true ] ])
                             */
                            ?>
                              <?php /*echo $form->field($model, 'model_id')->dropDownList($model->getModelOptions(), ['prompt' => '']) */ ?>
                              <?php /*echo $form->field($model, 'model_type')->textInput(['maxlength' => 128]) */ ?>
                              <?php /*echo $form->field($model, 'smtp_account_id')->dropDownList($model->getSmtpAccountOptions(), ['prompt' => '']) */ ?>
                              <?php /*echo $form->field($model, 'message_id')->dropDownList($model->getMessageOptions(), ['prompt' => '']) */ ?>
                              <?php /*echo $form->field($model, 're_message_id')->dropDownList($model->getReMessageOptions(), ['prompt' => '']) */ ?>
               </div>
	<div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'email-queue-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>