<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\Chatscript */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([
    //
    'id' => 'chatscript-form',
    'layout' => TActiveForm::LAYOUT_HORIZONTAL
]);
echo $form->errorSummary($model);
?>
        
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 64]) ?>
                              <?php echo $form->field($model, 'domain')->textInput(['maxlength' => 64])  ?>
                              <?php echo $form->field($model, 'script_code')->textInput(['maxlength' => 1024]) ?>
                               <?php echo $form->field($model, 'chat_server')->textInput(['maxlength' => 255])  ?>
      
                  <?php echo $form->field($model, 'popup_delay')->textInput()  ?>
                        <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions()) ?>
      <?php }?>                       
       <?php echo $form->field($model, 'role_id')->dropDownList(User::getRoleOptions(), ['prompt' => '']) ?>
       <div class='row col-md-6 mx-auto'>
		<div class="col-lg-6 col-xl-4 ml-auto">
                <?php echo $form->field($model, 'contact_link')->checkbox()  ?>
       </div>
		<div class="col-lg-6 col-xl-4 mr-auto">
                              <?php echo $form->field($model, 'show_bubble')->checkbox()  ?>
       </div>
	</div>
	<div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'chatscript-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>