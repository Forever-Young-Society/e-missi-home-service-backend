<?php
use app\components\TActiveForm;
use app\modules\contact\models\Address;
use app\modules\contact\models\Information;
use app\modules\contact\models\Phone;
use borales\extensions\phoneInput\PhoneInput;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<section class="page-title">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="page-title-inner">
					<h1 itemprop="name">Contact Us</h1>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="py-70">
	<div class="container-fluid">
		<section class="contact-form-main">
			<div class="row">
				<div class="col-md-8 col-lg-6 col-xl-4 offset-lg-2 mx-auto">
					<div class="contact-right-wrapper form-cover">
						<h3 class="mb-md-25 mb-15">
							<b> I'm Looking For...</b>
						</h3>
						 <?php
    $form = TActiveForm::begin([
        'options' => [
            'id' => 'quote_form_id'
        ],
        'action' => Url::toRoute([
            '/contact/information/info',
            'type' => Information::TYPE_QUOTE
        ])
    ]);
    ?>
 <?php echo $form->field($model, 'type_id',['options' => ['class' => 'form-custom-radio form-group']])->radioList($model->getTypeLabelOptions(),['class' => 'form-custom-radio'])->label(false) ?>
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
        'id' => 'quote_phone_number',
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
	  <?php echo $form->field($model, 'budget_type_id')->dropDownList($model->getBudgetTypeOptions(),['prompt' => 'Select Budget'])->label(false);  ?>
      <?php // echo $form->field($model, 'subject')->textInput(['maxlength' => 255,'placeholder' => 'Subject*'])->label(false) ?>
      <?php echo $form->field($model, 'description')->textarea(['rows' => 4,'placeholder' => 'Description'])->label(false);  ?>
<div class="form-group">
							<div class="text-center">
         <?php
        echo Html::submitButton('Send Message', [
            'class' => 'contact-form-btn w-100',
            'id' => 'quote-form-submit',
            'name' => 'submit-button'
        ])?> 
      </div>
						</div>
<?php TActiveForm::end(); ?>
						
						<!-- <p class="lead">Just a few details from you and one of our representatives shall get in touch with you soon.</p> -->

					</div>
				</div>

				<div class="col-xl-4 col-lg-12 col-md-12">
                  <?php
                $addressQuery = Address::findActive();
             
                foreach ($addressQuery->each() as $add) { 
                    $sales = $add->contacts;
                    ?>
					<div class="row">
						<div class="col-lg-6 col-xl-12 col-md-6 col-sm-12 mt-4">
							<div class="company-address">
								<h3 class="section-title box"><?= $add->title?> office</h3>
								<hr>
								<?php foreach ($sales as $sale) {?>
								<ul class="d-flex list-unstyled">
									<li class="mr-3">
										<div class="contact-icon">
											<i class="fa fa-phone"></i>
										</div>
									</li>
									<li>
										<div class="icon-info"><?php if(isset($sale->contact_no)) echo \yii\helpers\Html::encode($sale->contact_no)?>
                               </div>
									</li>
								</ul>
								<?php } ?>
								<ul class="d-flex list-unstyled">
									<li class="mr-3">
										<div class="contact-icon">
											<i class="fa fa-envelope"></i>
										</div>
									</li>
									<li>
										<div class="icon-info">
											<a
												href="mailto:<?php if(isset($add->email)) echo \yii\helpers\Html::encode($add->email) ?>"><?php if(isset($add->email)) echo \yii\helpers\Html::encode($add->email)?></a>
										</div>
									</li>
								</ul>
								<ul class="d-flex list-unstyled">
									<li class="mr-3">
										<div class="contact-icon">
											<i class="fa fa-map-marker"></i>
										</div>
									</li>
									<li>
										<div class="icon-info">
                                 <?php if(isset($add->address)) echo \yii\helpers\Html::encode($add->address)?>
                              </div>
									</li>
								</ul>
							</div>
						</div>
					</div>
<?php }?>
				</div>
			</div>
			<div class="row mt-5">
				<div class="col-md-12">
					<div class="google-map" itemscope itemprop="hasMap"
						itemtype="http://schema.org/Map">
						<iframe
							src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3430.1884307269543!2d76.70714381514965!3d30.713102693629434!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fee5ff25dac21%3A0x729bb5c6625a533d!2sOZVID+Technologies+Pvt+Ltd!5e0!3m2!1sen!2sin!4v1514273454928"
							width="100%" height="400" frameborder="0" style="border: 0"
							allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</section>
	</div>
</section>
<!-- contact section end---->
<script>
	$('#information-type_id').change(function() {
		selected_value = $("input[name='Information[type_id]']:checked").val();
		if(selected_value == 0){
			$('#information-budget_type_id').hide();
		}else if(selected_value == 1){
			$('#information-budget_type_id').show();
		}
	});

</script>