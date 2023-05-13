<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\availability\models\ProviderSlot */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'provider-slot-form'
]);
echo $form->errorSummary($model);
?>
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
                              <?php /*echo $form->field($model, 'availability_slot_id')->dropDownList($model->getAvailabilitySlotOptions(), ['prompt' => '']) */ ?>
                        <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php }?>                        <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
                  <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'provider-slot-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>