<?php

use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\seo\models\Redirect */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php 
$form = TActiveForm::begin([
					 //'layout' => 'horizontal',
						'id'	=> 'redirect-form',
						'options'=>[
					        	'class'=>'row'
					        ]
						]);?>



<div class="col-sm-6 offset-sm-3">

<?php						
echo $form->errorSummary($model);	
?>
 <?php echo $form->field($model, 'old_url')->textInput(['maxlength' => 255]) ?>
 <?php echo $form->field($model, 'new_url')->textInput(['maxlength' => 255]) ?>
	 		
<div class="text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'redirect-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
