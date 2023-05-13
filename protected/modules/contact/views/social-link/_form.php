<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\SocialLink */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'social-link-form',
    'options' => [
        'class' => 'row'
    ]
]);
?>  <div class="col-md-6 offset-md-3">
             <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
             <?php echo $form->field($model, 'ext_url')->textInput(['maxlength' => 512]) ?>
                
                  <div class="col-md-12 text-center">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'social-link-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
	</div>
   <?php TActiveForm::end(); ?>
</div>