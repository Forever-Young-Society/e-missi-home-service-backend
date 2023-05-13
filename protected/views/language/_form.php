<?php

use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Language */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

   $form = TActiveForm::begin([

      'id' => 'language-form'
   ]);
   echo $form->errorSummary($model);
   ?>
   <div class="row offset-md-3">
      <div class="col-md-6">
         <?php echo $form->field($model, 'title')->textInput(['maxlength' => 16])  ?>
      </div>
      <div class="col-md-6">
         <?php //echo $form->field($model, 'code')->dropDownList($model->getLanguageCode(),['maxlength' => 255]) 
         ?>
      </div>
      <?php /*echo $form->field($model, 'code')->textInput(['maxlength' => 16]) */ ?>
      <?php if (User::isAdmin()) { ?> <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
      <?php } ?> <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
      <div class="col-md-6 text-center">
         <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'language-form-submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      </div>
      <?php TActiveForm::end(); ?>
   </div>
</div>