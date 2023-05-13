<?php
use app\components\TActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

// $this->title = 'Request password reset';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Request passwordReset'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = \yii\helpers\Inflector::camel2words(Yii::$app->controller->action->id);
?>
<div class="box-header with-border">
    <?php

if (Yii::$app->session->hasFlash('success')) {
        ?>
    	<div class="alert alert-success">
    <?php echo Yii::$app->session->getFlash('success') ?>
            </div>
    <?php
    } else {
        ?>
    <section class="login-signup  py-5">
		<div class="container-fluid">
			<div class="login-box">
				<h3 id="profile-name" class="section-title">Reset Password</h3>
				<br>
				<p>Please fill out your email. A link to reset password will be sent
					there.</p>
          	  <?php $form = TActiveForm::begin(['id' => 'request-password-reset-form','enableClientValidation'=>true,'enableAjaxValidation'=>false]); ?>
                    <?= $form->field($model, 'email') ?>
                    <div class="form-group">
                        <?= Html::submitButton('Send', ['name'=>'send-button','class' => 'btn btn-success btn-block']) ?>
                    </div>
                <?php TActiveForm::end(); ?>
			</div>
		</div>
	</section>
<?php } ?>
</div>


	