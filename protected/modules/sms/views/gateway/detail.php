<?php
use app\components\TActiveForm;
use yii\helpers\Html;
use app\modules\sms\models\Gateway;

/* @var $this yii\web\View */
/* @var $model app\models\PaymentGateway */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="wrapper">
	<div class="card">
		<div class="payment-gateway-create">
	<?=  \app\components\PageHeader::widget(); ?>
</div>
	</div>
	<div class="content-section  card">
		<header class="card-header">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
		<div class="card-body">
    <?php
    $form = TActiveForm::begin([
        'options'=>[
            'class'=>'row'
        ],
        'id' => 'payment-gateway-form'
    ]);
    ?>
    <div class="col-md-6 offset-md-3">
				
				<?php
    
    $gatewayFields = Gateway::gatewayForm($model->type_id);
    
    if (! empty($gatewayFields)) {
        foreach ($gatewayFields as $key => $field) {
            echo Gateway::generateField($key, $field);
        }
    }
    ?>
  <div class="form-group text-center">
		<?= Html::submitButton(Yii::t('app', 'Save'), ['id'=> 'payment-gateway-form-submit','class' => 'btn btn-primary']) ?>
    </div>
			</div>
    <?php TActiveForm::end(); ?>
</div>
	</div>
</div>