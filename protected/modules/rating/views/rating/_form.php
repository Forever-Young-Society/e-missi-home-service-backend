<?php

use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\rating\models\Rating */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
                            <?= strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php 
$form = TActiveForm::begin([
					 'layout' => 'horizontal',
						'id'	=> 'rating-form',
						  'options' => [
          				  'class' => 'row'
       								   ]
						]);
						
						
echo $form->errorSummary($model);	
?>




	</div>
	<div class="col-md-6">

		
		 <?php echo $form->field($model, 'model_id')->dropDownList($model->getModelOptions(), ['prompt' => '']) ?>
	 		


		 <?php echo $form->field($model, 'model_type')->textInput(['maxlength' => 256]) ?>
	 		


		 <?php echo $form->field($model, 'rating')->textInput(['maxlength' => 2]) ?>
	 		


		 <?php /*echo  $form->field($model, 'comment')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'comment')->textarea(['rows' => 6]); */ ?>
	 		


	<?php if(User::isAdmin()){?>	 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 <?php }?>		


		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
	 		


	   <div class="form-group">
		<div
			class="col-md-12 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'rating-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
