<?php
/* @var $this yii\web\View */
use app\modules\contact\widgets\ContactWidget;
use app\components\TActiveForm;
use borales\extensions\phoneInput\PhoneInput;
use yii\helpers\Html;

// $this->title = "Contact Us ";

?>
<style>
.invalid-feedback {
    display: block !important;
}
</style>
<section class="pagetitle-section">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1 class="mb-0 mt-0">Contact Us</h1>
			</div>
		</div>
	</div>
</section>
<section class="py-5 clr-greenn contact-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center">
				<h2>Get in touch</h2>
				<p class="mb-5">We have a great customer support team who would love
					to hear from you.</p>
			</div>
		</div>
		<div class="card-group contact-group d-lg-flex d-block">
			<div class="card">
				<ul class="d-flex">
					<li class="mr-4">
						<div class="icon">
							<span class="fa fa-envelope"></span>
						</div>
					</li>
					<li>
						<div class="details">
							<h4 class="heading-title">Email:</h4>
							<p><?= \Yii::$app->settings->getValue('email', null, 'settings')?></p>
						</div>
					</li>
				</ul>
			</div>
			<div class="card mx-lg-4 mx-0">
				<ul class="d-flex">
					<li class="mr-4">
						<div class="icon">
							<span class="fa fa-map-marker"></span>
						</div>
					</li>
					<li>
						<div class="details">
							<h4 class="heading-title">Find Us at:</h4>
							<p class="address"><?= \Yii::$app->settings->getValue('address', null, 'settings')?>
                  </p>
						</div>
					</li>
				</ul>

			</div>
			<div class="card">
				<ul class="d-flex">
					<li class="mr-4">
						<div class="icon">
							<span class="fa fa-phone"></span>
						</div>
					</li>
					<li>
						<div class="details">
							<h4 class="heading-title">Contact number:</h4>
							<p>
                     <?= \Yii::$app->settings->getValue('phone', null, 'settings')?>
                  </p>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="cntct-box mt-5 p-5 bg-white">
			<h3 class="section-title  text-center">Contact Form</h3>
			<div class="contact-form">
            <?php
            $form = TActiveForm::begin([
                'options' => [
                    'id' => 'contact_form_id'
                ]
            ]);
            ?>

      <?php echo $form->field($model, 'full_name')->textInput(['maxlength' => 255,'placeholder' => 'Name*'])->label(false) ?>
      <?php echo $form->field($model, 'email')->textInput(['maxlength' => 255,'placeholder' => 'Email*'])->label(false) ?>
 <?php
echo $form->field($model, 'mobile')
    ->input('tel', [
    'id' => "phone_number",
    'maxlength' => 10
])
    ->widget(PhoneInput::className(), [
    'options' => [
        'id' => 'contact_phone_number',
        'placeholder' => 'Mobile*'
    ],
    'jsOptions' => [
        'separateDialCode' => true,
        'autoPlaceholder' => 'off',
        'initialCountry' => $model->country_code
    ]
])
    ->label(false);
?>
      <?php // echo $form->field($model, 'subject')->textInput(['maxlength' => 255,'placeholder' => 'Subject*'])->label(false) ?>
      <?php echo $form->field($model, 'description')->textarea(['rows' => 4,'placeholder' => 'Description'])->label(false);  ?>
<div class="form-group">
					<div class="text-center">
         <?php
        echo Html::submitButton('Send Message', [
            'class' => 'contact-form-btn w-100',
            'id' => 'contact-form-submit',
            'name' => 'submit-button'
        ])?> 
      </div>
				</div>
<?php TActiveForm::end(); ?>
         </div>
		</div>
	</div>
</section>