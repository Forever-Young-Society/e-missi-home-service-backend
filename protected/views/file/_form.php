<?php
use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\File */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php
    $form = TActiveForm::begin([
      'id' => 'file-form',
        'options' => [
            'class' => 'row'
        ]
    ]);
    ?>
<div class="col-lg-6">
		 <?php echo $form->field($model, 'name')->textInput(['maxlength' => 1024]) ?>
		 <?php echo $form->field($model, 'size')->textInput() ?>
		 <?php echo $form->field($model, 'key')->textInput(['maxlength' => 255]) ?>
	 		
</div>
	<div class="col-lg-6">
		 <?php echo $form->field($model, 'model_type')->textInput(['maxlength' => 128]) ?>
		 <?php echo $form->field($model, 'model_id')->textInput() ?>
	 
		 <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
	 		</div>

	<div class="col-lg-12 form-group text-right">
		
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'file-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php TActiveForm::end(); ?>

</div>
