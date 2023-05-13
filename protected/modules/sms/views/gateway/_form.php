<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\modules\sms\models\Gateway;

/* @var $this yii\web\View */
/* @var $model app\modules\sms\models\Gateway */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php
    $form = TActiveForm::begin([
     'id' => 'gateway-form',
     'options'=>[
        'class'=>'row'
     ]
    ]);?>
    <div class="col-md-12">
    <?php
    echo $form->errorSummary($model);
    ?></div>
    <div class="col-md-6 offset-md-3">
		 <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
	 	<?php
if (! $model->isNewRecord) {
    $gatewayFields = Gateway::gatewayForm($model->type_id);
    $gateWaySettings = $model->getGatewaySettings();
    if (! empty($gatewayFields)) {
        foreach ($gatewayFields as $key => $field) {
            if ($gateWaySettings) {
                if (is_array($field)) {
                    $field['value'] = isset($gateWaySettings[$key]) ? $gateWaySettings[$key] : '';
                } else {
                    $name = $field;
                    $field = [];
                    $field['value'] = isset($gateWaySettings[$name]) ? $gateWaySettings[$name] : '';
                }
            }
            
            echo Gateway::generateField($key, $field);
        }
    }
} else {
    echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions());
}
?>	
		 <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
	 		
	   <div class="form-group text-center">
		 <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'gateway-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>

    <?php TActiveForm::end(); ?>

</div>
