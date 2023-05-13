<?php
use app\components\TActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

// $this->title = 'Sign In';
?>
 <?php

$fieldOptions1 = [
    'options' => [
        'class' => 'form-group has-feedback'
    ],

    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => [
        'class' => 'form-group has-feedback'
    ],
    'inputTemplate' => "{input}<span class='fa fa-lock form-control-feedback'></span>"
];
$fieldOptions3 = [
    'inputTemplate' => "{input}<span  class='fa fa-fw fa-eye field-icon toggle-password' toggle='#loginform-password' id='password-reveal'></span>"
];
?>
<style>
.field-loginform-password span {
	float: right;
	margin-top: -31px;
	position: relative;
	z-index: 2;
	margin-right: 5px;
}
</style>

<section class="login-signup ">
	<div class="container-fluid">
		<div class=" row justify-content-center">
			<div class="login-outr order-1 col-lg-5 order-lg-2">
				<div class="login-box">
					<h3 id="profile-name" class="section-title">Log In</h3>

             <?php

            $form = TActiveForm::begin([
                'id' => 'login-form',
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'options' => [
                    'class' => 'form-signin'
                ]
            ]);
            ?>

                <span id="reauth-email" class="reauth-email"></span>
                
                
                     <?= $form->field ( $model, 'username', $fieldOptions1 )->label ( false )->textInput ( [ 'placeholder' => $model->getAttributeLabel ( 'email' ) ] )?>
            <?= $form->field ( $model, 'password', $fieldOptions3 )->label ( false )->passwordInput ( [ 'placeholder' => $model->getAttributeLabel ( 'password' ) ] )?>
           <div class="row">
						<div class="col-md-6">
							<div id="remember" class="checkbox">
                  <?php echo $form->field($model, 'rememberMe')->checkbox();?>
            </div>
						</div>
						<div class="col-md-6">
							<a class="forgot-password float-none float-md-right"
								href="<?php echo Url::toRoute(['user/recover'])?>">Forgot
								Password? </a>
						</div>
					</div>
                <?=Html::submitButton ( 'Log In', [ 'class' => 'btn btn-success btn-block btn-signin mt-4 mt-md-0','id' => 'login','name' => 'login-button' ] )?>
                            <h4 class="text-center dont-text"></h4>
    
            <?php TActiveForm::end()?>
        </div>
			</div>
		</div>
		<!-- /card-container -->
	</div>
	<!-- /container -->
</section>

<script>
$(".toggle-password").click(function() {
  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
  console.log('teete');
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>