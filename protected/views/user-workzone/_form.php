<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\UserWorkzone */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'user-workzone-form'
]);
echo $form->errorSummary($model);
?>
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 256]) ?>
                              <?php /*echo $form->field($model, 'workzone_id')->dropDownList($model->getWorkzoneOptions(), ['prompt' => '']) */ ?>
                              <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
                        <?php if(User::isAdmin()){?>      <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
      <?php }?>            <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'user-workzone-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>