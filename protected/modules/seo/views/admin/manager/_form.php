<?php

use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Seo */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php 
$form = TActiveForm::begin([
						//'layout' => 'horizontal',
						'id'	=> 'seo-form',
						  'options'=>[
					        	'class'=>'row'
					        ]
						]);
?>
<div class="col-md-9">
	<?php echo $form->field($model, 'title')->textInput(['maxlength' => 255])  ?>
	<?php echo $form->field($model, 'description')->textarea(['rows' => 6]); //$form->field($model, 'description')->widget(kartik\widgets\Html5Input::class,[]); */ ?>
</div>
<div class="col-md-3">

 <?php echo $form->field($model, 'route')->textInput(['maxlength' => 255]) ?>
	 
	<?php echo $form->field($model, 'keywords')->textInput(['maxlength' => 255])  ?>
	 
	<?php echo $form->field($model, 'data')->textInput(['maxlength' => 255]) ?>
	
	   <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php }?> 
	 		
<div class="form-group text-right col-md-12">
	 <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=>'seo-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
 <?php TActiveForm::end(); ?>

</div>
