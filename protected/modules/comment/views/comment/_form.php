<?php
use yii\helpers\Html;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model use app\modules\comment\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php
    $form = TActiveForm::begin([
        'layout' => 'horizontal',
        'id' => 'comment-form'
    ]);
    ?>
		 <?php echo  $form->field($model, 'comment')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'comment')->textarea(['rows' => 6]);  ?>
	 		
		 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 		
	   <div class="form-group">
		<div
			class="col-md-6 col-md-offset-3 bottom-admin-button btn-space-bottom text-right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'comment-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>
</div>