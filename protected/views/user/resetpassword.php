<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\TActiveForm;

// $this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="login-signup py-5">
	<div class="container-fluid">
	<div class="login-box">
		<div class=" row p-3 justify-content-center">
			<div class="order-1 col-lg-5 order-lg-2">
				<div class="login-box">
					<h3 class="section-title mb-3">change password</h3>
					<p>Please fill out the following fields to change password :</p>
					 <?php

    $form = TActiveForm::begin([
        'id' => 'changepassword-form',
        'options' => [
            'class' => 'form-horizontal'
        ],
        'fieldConfig' => [
            'template' => "{label}
                                        {input}
                                        {error}"
        ]
    ]);
    ?>
                         <?=$form->field ( $model, 'password', [ 'inputOptions' => [ 'placeholder' => '' ] ] )->passwordInput ()?>
                         <?=$form->field ( $model, 'confirm_password', [ 'inputOptions' => [ 'placeholder' => '' ] ] )->passwordInput (['class' => 'form-control'])?>
                        <div class="text-center">
                			 <?=Html::submitButton ( 'Change password', [ 'class' => 'btn btn-primary' ] )?>
                        </div>
                    <?php TActiveForm::end(); ?>
					     </div>
			</div>
		</div>
</div>
	</div>
</section>




	