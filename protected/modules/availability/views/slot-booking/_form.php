<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\availability\models\SlotBooking */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([
    //
    'id' => 'slot-booking-form'
]);
echo $form->errorSummary($model);
?>
         <div class="col-md-6">
                  <?php

echo $form->field($model, 'start_time')->widget(yii\jui\DatePicker::class, [
                    // 'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control'
                    ],
                    'clientOptions' => [
                        'minDate' => \date('Y-m-d'),
                        'maxDate' => \date('Y-m-d', strtotime('+30 days')),
                        'changeMonth' => true,
                        'changeYear' => true
                    ]
                ])?>
                              <?php

echo $form->field($model, 'end_time')->widget(yii\jui\DatePicker::class, [
                                // 'dateFormat' => 'php:Y-m-d',
                                'options' => [
                                    'class' => 'form-control'
                                ],
                                'clientOptions' => [
                                    'minDate' => \date('Y-m-d'),
                                    'maxDate' => \date('Y-m-d', strtotime('+30 days')),
                                    'changeMonth' => true,
                                    'changeYear' => true
                                ]
                            ])?>
                              <?php echo $form->field($model, 'provider_id')->dropDownList($model->getProviderOptions(), ['prompt' => '']) ?>
                              <?php /*echo $form->field($model, 'dependant_id')->dropDownList($model->getDependantOptions(), ['prompt' => '']) */ ?>
                              <?php echo $form->field($model, 'slot_id')->dropDownList($model->getSlotOptions(), ['prompt' => '']) ?>
                              <?php /*echo  $form->field($model, 'description')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'description')->textarea(['rows' => 6]); */ ?>
                              <?php /*echo $form->field($model, 'provider_reschedule')->textInput() */ ?>
                     </div>
	<div class="col-md-6">
                  <?php /*echo $form->field($model, 'user_reschedule')->textInput() */ ?>
                              <?php /*echo $form->field($model, 'is_reschedule_confirm')->textInput() */ ?>
                              <?php 
/*
                                * echo $form->field($model, 'old_start_time')->widget(yii\jui\DatePicker::class,
                                * [
                                * //'dateFormat' => 'php:Y-m-d',
                                * 'options' => [ 'class' => 'form-control' ],
                                * 'clientOptions' =>
                                * [
                                * 'minDate' => \date('Y-m-d'),
                                * 'maxDate' => \date('Y-m-d',strtotime('+30 days')),
                                * 'changeMonth' => true,'changeYear' => true ] ])
                                */
                            ?>
                              <?php 
/*
                                * echo $form->field($model, 'old_end_time')->widget(yii\jui\DatePicker::class,
                                * [
                                * //'dateFormat' => 'php:Y-m-d',
                                * 'options' => [ 'class' => 'form-control' ],
                                * 'clientOptions' =>
                                * [
                                * 'minDate' => \date('Y-m-d'),
                                * 'maxDate' => \date('Y-m-d',strtotime('+30 days')),
                                * 'changeMonth' => true,'changeYear' => true ] ])
                                */
                            ?>
                        <?php if(User::isAdmin()){?>      <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
      <?php }?>                        <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
               </div>
	<div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'slot-booking-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>