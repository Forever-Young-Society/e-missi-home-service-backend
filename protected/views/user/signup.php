 <?php
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */


// $this->title = 'Signup';
?>
<section class="login-signup ">
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="login-outr order-1 col-lg-5 order-lg-2">
				<div class="login-box">
					<h3 id="profile-name" class="section-title">Sign Up</h3>
					<br>
            	<?php

            $form = TActiveForm::begin([
                'id' => 'form-signup',
                'options' => [
                    'class' => 'form-signin'
                ]
            ]);
            ?>
                <span id="reauth-email" class="reauth-email"></span>

     <?=$form->field ( $model, 'full_name', [ 'template' => '{input}{error}' ] )->textInput ( [ 'maxlength' => true,'placeholder' => 'Full Name' ] )->label ( false )?>
<?=$form->field ( $model, 'email', [ 'template' => '{input}{error}' ] )->textInput ( [ 'maxlength' => true,'placeholder' => 'Email' ] )->label ( false )?>
	<?=$form->field ( $model, 'password', [ 'template' => '{input}{error}' ] )->passwordInput ( [ 'maxlength' => true,'placeholder' => 'Password' ] )->label ( false )?>
		<?=$form->field ( $model, 'confirm_password', [ 'template' => '{input}{error}' ] )->passwordInput ( [ 'maxlength' => true,'placeholder' => 'Confirm Password' ] )->label ( false )?>
 <?php
if (Yii::$app->controller->action->id != 'add-admin') {
    echo $form->field($model, 'verifyCode', [
        'enableClientValidation' => true
    ])
        ->widget(Captcha::class, [
        'template' => '<div class="row"><div class="col-lg-4 verfication-img"><span class="verification-card border d-inline-block">{image}</span></div><div class="col-lg-8">{input}</div></div>'
    ])
        ->label(false);
}
?>
     <?=Html::submitButton ( 'Signup', [ 'class' => 'btn  btn-success btn-block btn-signin','name' => 'signup-button' ] )?>

		<!-- /form -->
            	<?php TActiveForm::end(); ?>
		
		</div>
			</div>

		</div>
	</div>
</section>
