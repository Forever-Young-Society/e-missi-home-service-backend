<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\scheduler\models\Log */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php    $form = TActiveForm::begin([
    
   'id' => 'log-form',
   ]);
   echo $form->errorSummary($model);    
   ?>
            <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions()) ?>
      <?php }?>                        <?php //echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
                              <?php /*echo  $form->field($model, 'result')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'result')->textarea(['rows' => 6]); */ ?>
                              <?php echo $form->field($model, 'cronjob_id')->dropDownList($model->getCronjobOptions(), ['prompt' => '']) ?>
                              <?php echo $form->field($model, 'scheduled_on')->widget(yii\jui\DatePicker::class,
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			'minDate' => \date('Y-m-d'),
            'maxDate' => \date('Y-m-d',strtotime('+30 days')),
			'changeMonth' => true,'changeYear' => true ] ]) ?>
                              <?php /*echo $form->field($model, 'executed_on')->widget(yii\jui\DatePicker::class,
			[
					//'dateFormat' => 'php:Y-m-d',
	 				'options' => [ 'class' => 'form-control' ],
	 				'clientOptions' =>
	 				[
			'minDate' => \date('Y-m-d'),
            'maxDate' => \date('Y-m-d',strtotime('+30 days')),
			'changeMonth' => true,'changeYear' => true ] ]) */ ?>
                  <div
      class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'log-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>