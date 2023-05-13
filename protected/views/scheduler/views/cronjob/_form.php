<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\scheduler\models\Cronjob */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([
   //  'layout' => TActiveForm::LAYOUT_HORIZONTAL,
    'id' => 'cronjob-form'
]);
echo $form->errorSummary($model);
?>
<div class="row">
                  <div class="col-md-4">
             <?php echo $form->field($model, 'command')->textarea(['rows' => 1]);  ?>
</div>
<div class="col-md-4">
             <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
</div>
            
    <div class="col-md-4">
              <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?></div>
              <div class="col-md-4">
         <?php if(User::isAdmin()){?>    
                          <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?></div>
                          <div class="col-md-4">
              <?php echo $form->field($model, 'when')->textInput(['maxlength' => 32]) ?></div>
              
     <pre>
* * * * *
- - - - -
| | | | |
| | | | |
| | | | +----- day of week (0 - 7) (Sunday=0 or 7)
| | | +------- month (1 - 12)
| | +--------- day of month (1 - 31)
| +----------- hour (0 - 23)
+------------- min (0 - 59)
    </pre> 
      <?php }?>            <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'cronjob-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>