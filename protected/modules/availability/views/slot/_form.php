<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use kartik\time\TimePicker;
/* @var $this yii\web\View */
/* @var $model app\modules\availability\models\Slot */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'slot-form'
]);
echo $form->errorSummary($model);
?>
                  <?php echo $form->field($model, 'start_time')->widget(TimePicker::class,[]) ?>
                              <?php echo $form->field($model, 'end_time')->widget(TimePicker::class,[]) ?>
                              <?php //echo $form->field($model, 'slot_gap_time')->textInput() ?>
                        <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php }?>                        <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
                  <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'slot-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>