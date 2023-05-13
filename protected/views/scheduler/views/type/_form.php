<?php
use app\components\TActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\scheduler\models\Type */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper('Add'); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'type-form',
    'options' => [
        'class' => 'row justify-content-center'
    ]
]);
echo $form->errorSummary($model);
?>
<div class="col-md-6">
		<div class="d-md-flex align-items-start">
			<div class="flex-grow-1">
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                        </div>
			<div class="mt-3 mt-sm-4 text-center ml-sm-3">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'type-form-submit','class' => $model->isNewRecord ? 'btn btn-success mt-1' : 'btn btn-primary mt-1']) ?>
   </div>
		</div>
	</div>
   <?php TActiveForm::end(); ?>
</div>