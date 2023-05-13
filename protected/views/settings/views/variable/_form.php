<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\Variable */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'variable-form'
]);
echo $form->errorSummary($model);
?>
                  <?php echo $form->field($model, 'key')->textInput(['maxlength' => 255]) ?>
        <?php
        echo $form->field($model, 'value')->textarea([
            'rows' => 6
        ]);
        ?>
        <?php echo $form->field($model, 'module')->textInput(['maxlength' => 255]) ?>         <div
		class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'variable-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>