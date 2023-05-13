<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\Information */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-body">

     <?php

    $form = TActiveForm::begin([
        // 'layout' => 'horizontal',
        'id' => 'inforation-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'options' => [
            'class' => 'row'
        ]
    ]);
    ?>
    
<div class="col-md-6">

		 <?php echo $form->field($model, 'full_name')->textInput(['maxlength' => 255]) ?>
		 <?php echo $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
                           <?php
	 		
                        echo $form->field($model, 'mobile')->widget(PhoneInput::className(), [
                            'jsOptions' => [
                                'separateDialCode' => true,
                                'autoPlaceholder' => 'off',
                                'initialCountry' => $model->country_code
                            ]
                        ]);
                        ?>
	</div>
	<div class="col-md-6">
	
	     <?php echo $form->field($model, 'subject')->textInput(['maxlength' => 255]) ?>
	     
	
		 <?php echo $form->field($model, 'description')->textarea(['rows' => 6]);  ?>
	 		

	 			</div>

	<div class="form-group col-md-12 text-right">
		 <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'information-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	 <?php TActiveForm::end(); ?>

</div>
