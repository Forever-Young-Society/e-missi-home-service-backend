<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\workzone\models\Location */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'location-form'
]);
?>
                 <?php echo $form->field($model, 'primary_location')->dropDownList($model->getLocationOptions(), ['prompt' => '']) ?>
                 <?php echo $form->field($model, 'secondary_location')->dropDownList($model->getLocationOptions(), ['prompt' => '']) ?>
                 <?php echo $form->field($model, 'second_secondary_location')->dropDownList($model->getLocationOptions(), ['prompt' => '']) ?>
                  <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'location-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>