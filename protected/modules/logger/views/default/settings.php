<?php
use app\components\TActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\logger\models\SettingsForm $model */
/** @var TActiveForm $form */
?>
<div class="wrapper">
	<div class="card">
		<header class="card-header"> 
			  <?php echo ucfirst(Yii::$app->controller->module->id); ?> 
			</header>
		<div class="card-body">	
   <?php

$form = TActiveForm::begin([
    'id' => 'setting-form',
    'layout' => TActiveForm::LAYOUT_HORIZONTAL,
    'enableAjaxValidation' => false,
    'enableClientValidation' => false
]);
?>
<div class="row justify-content-center">
				<div class="col-xl-4 col-lg-6 col-md-6 custom-control custom-switch">
<?php
echo $form->field($model, 'enable')
    ->checkbox([
    'id' => $model->getFormAttributeId('enable'),
    'class' => 'custom-control-input',
    'onchange' => 'this.form.submit()'
])
    ->label(true, [
    'class' => 'custom-control-label'
]);
?>
</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-xl-4 col-lg-6 col-md-6 custom-control custom-switch">
<?php
echo $form->field($model, 'enableEmails')
    ->checkbox([
    'id' => $model->getFormAttributeId('enableEmails'),
    'class' => 'custom-control-input',
    'onchange' => 'this.form.submit()'
])
    ->label(true, [
    'class' => 'custom-control-label'
]);
?>
</div>
			</div>

			<div class="row justify-content-center">
				<div
					class="col-xl-6 col-lg-8 col-md-10 custom-control custom-switch">
<?php
echo $form->field($model, 'sendLogEmailsTo')->textInput([
    'maxlength' => 64
]);
?>
</div>
			</div>

			<div class="text-center">
    	<?= Html::submitButton('Submit', ['id'=> 'setting-form-submit','class' => 'btn btn-primary']) ?>
  
</div>
		</div>
 <?php TActiveForm::end(); ?>
		
		<!-- settings -->

	</div>
</div>