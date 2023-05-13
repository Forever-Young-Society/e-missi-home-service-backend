<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\UserLanguage */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'user-language-form'
]);
echo $form->errorSummary($model);
?>
                  <?php /*echo $form->field($model, 'title')->textInput(['maxlength' => 16]) */ ?>
                              <?php /*echo $form->field($model, 'code')->textInput(['maxlength' => 16]) */ ?>
                              <?php /*echo $form->field($model, 'language_id')->dropDownList($model->getLanguageOptions(), ['prompt' => '']) */ ?>
                        <?php if(User::isAdmin()){?>      <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
      <?php }?>                        <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
                  <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'user-language-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>