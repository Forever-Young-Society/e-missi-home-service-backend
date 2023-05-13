<?php
use app\components\TActiveForm;
use app\components\World;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Address */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php
echo strtoupper(Yii::$app->controller->action->id);
?>
</header>
<div class="card-body">
   <?php
$form = TActiveForm::begin([
    'id' => 'address-form',
    'layout' => TActiveForm::LAYOUT_HORIZONTAL,
    'options' => [
        'class' => 'row'
    ]
]);
?>
   <div class="col-md-6">
      <?php
    echo $form->field($model, 'title')->textInput([
        'maxlength' => 255
    ])?>
      <?php
    echo $form->field($model, 'address')->textarea()?>
      <?php
    echo $form->field($model, 'email')->textInput([
        'maxlength' => 255
    ])?>
      <?php
    echo $form->field($model, 'latitude')->textInput([
        'maxlength' => 255
    ])?>
      <?php
    echo $form->field($model, 'longitude')->textInput([
        'maxlength' => 255
    ])?>
   </div>
	<div class="col-md-6">
	<?php
echo $form->field($model, 'country')->dropDownList(World::countries())?>
      <?php
    echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), [
        'prompt' => ''
    ])?>
      <?php
    echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), [
        'prompt' => ''
    ])?>
      <?php
    echo $form->field($model, 'tel')->textInput([
        'maxlength' => 255
    ])?>
      <?php
    echo $form->field($model, 'mobile')->textInput([
        'maxlength' => 255
    ])?>
     <?php  echo $form->field($model, 'image_file')->checkbox();?>
   </div>
	<div class="col-md-12 text-right">
     <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'address-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>
      </div>
  
   <?php
TActiveForm::end();
?>
</div>
