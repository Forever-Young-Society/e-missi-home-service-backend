<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\sitemap\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([
    'layout' => TActiveForm::LAYOUT_HORIZONTAL,
    'id' => 'item-form'
]);
echo $form->errorSummary($model);
?>
                  	  <?php echo $form->field($model, 'location')->textInput(['maxlength' => 255]) ?>
                      <?php //echo $form->field($model, 'module')->textInput(['maxlength' => 64]) ?>
                      <?php echo $form->field($model, 'priority_id')->dropDownList($model->getPriorityOptions(), ['prompt' => '']) ?>
                      <?php echo $form->field($model, 'change_frequency_id')->dropDownList($model->getChangeFrequencyOptions()) ?>
                      <?php /*echo $form->field($model, 'model_type')->textInput(['maxlength' => 128]) */ ?>
                      <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
                      <?php if(User::isAdmin()){?>      
                       	<?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      	   			  <?php }?>                       
                  <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'item-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>