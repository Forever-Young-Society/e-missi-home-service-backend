<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\smtp\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([
   //  'layout' => TActiveForm::LAYOUT_HORIZONTAL,
    'id' => 'account-form',
    'options' => [
        'class' => 'row'
    ]
]);

?>
         <div class="col-md-6">
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                  <?php echo $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
                  <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>
                  <?php echo $form->field($model, 'server')->textInput(['maxlength' => 255]) ?>
                     </div>
	<div class="col-md-6">
             <?php echo $form->field($model, 'encryption_type')->dropDownList($model->getEncryptionOptions(), ['prompt' => 'Select'])  ?>                              <?php echo $form->field($model, 'limit_per_email')->textInput() ?>
             <?php if(User::isAdmin()){?>    
                   <?php //echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      	     <?php }?>                        
             <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>

             <?php echo $form->field($model, 'port')->textInput() ?>
        </div>
	<div class="col-md-12 text-right">
             <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'account-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
     </div>
   <?php TActiveForm::end(); ?>
</div>