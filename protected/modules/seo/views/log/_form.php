<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\seo\models\Log */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'log-form'
]);
echo $form->errorSummary($model);
?>
                  <?php /*echo $form->field($model, 'referer_link')->textInput(['maxlength' => 255]) */ ?>
                              <?php /*echo $form->field($model, 'message')->textInput(['maxlength' => 1000]) */ ?>
                              <?php /*echo $form->field($model, 'current_url')->textInput(['maxlength' => 512]) */ ?>
                        <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php }?>                        <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
                              <?php echo $form->field($model, 'user_id')->dropDownList($model->getUserOptions(), ['prompt' => '']) ?>
                              <?php /*echo $form->field($model, 'user_ip')->textInput(['maxlength' => 255]) */ ?>
                              <?php /*echo $form->field($model, 'user_agent')->textInput(['maxlength' => 255]) */ ?>
                  <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'log-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>