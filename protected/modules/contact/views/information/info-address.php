<?php
use app\components\TActiveForm;
use app\modules\contact\assets\ContactAsset;
use app\modules\contact\models\Address;
use app\modules\contact\models\Phone;
use borales\extensions\phoneInput\PhoneInput;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\datetime\DateTimePicker;
?>
<?php
$this->registerCss("
.invalid-feedback {
	display: block !important;
}

.invalid {
	width: 100%;
	margin-top: .25rem;
	font-size: 80%;
	color: #dc3545;
}
");
?>
<?php
$bundle = ContactAsset::register(Yii::$app->view);
$addressQuery = Address::getAllAddress();
$addresscount = $addressQuery->count();
?>
<section class="contact-us-wrapper pt-90">
	<div class="container-fluid fluid-2">
		<div class="row shadow-lg rounded-lg overflow-hidden">
			<div class="col-lg-8 col-md-12 mx-auto px-0">
				<div class="titlebar pt-0 pr-0 h-100">
					<!--------------------Sales Box Start----------------------->
					<div class="number-box h-100">
						<h6 class="global-text d-flex align-items-center">
							<img src="<?= $bundle->baseUrl . '/img/call.svg'?>" alt="image"
								class="mr-2" /> <?=Yii::t('app',"Get in touch")?>
						</h6>
						<p><?=Yii::t('app', "Interested in our services? Just pick up the phone to talk to our sales team.")?></p>
						<div class="row">
							<?php
    $phoneQuery = Phone::getAllContacts();
    foreach ($phoneQuery->each() as $sale) {

        if ($sale->hasProperty('contact_no')) {
            ?>
                
										<?php if($sale->type_id == Phone::CONTACT_TYPE_SALES){?>
										<div class=" col-lg-6 col-md-6  mb-md-0 ">
								<div class="d-flex mb-3">
									<p class="mb-0">
										<img
											src="<?= $bundle->baseUrl . '/img/' .$sale->country.'.svg'?>"
											alt="<?= $sale->title?>" class="mr-2" width="24px">
									</p>
									
									
										<?= $sale->getContactLink()?>
										<?php  if($sale->whatsapp_enable){?>
							         <span class="what-app-ic"><a
										href="<?= $sale->getWhatsappLink()?>"> <img
											src="<?= $bundle->baseUrl . '/img/whatsapp.png'?>"
											alt="Whatsapp" width="20px"></a> </span>
											
									
									<?php }?>
									<?php if($sale->toll_free_enable){?>
											<span class="toll-free-ic"> (Toll-Free)</span>
									<?php }?>
									
										</div>
							</div>
										<?php }?>
										<?php }}?>
										
										
										<?php
        /*
         * If contacts count equal to One then contacts shown in this box
         */
        if ($addresscount == 1) {
            ?>
     <div class="col-md-12">
								<section class="pt-4">
									<div class="container-fluid">
										<div class="row ">
						    <?=\Yii::$app->controller->renderPartial('_address', ['addressQuery' => $addressQuery,'bundle' => $bundle,'q' => true])?>	
							</div>
									</div>
								</section>
							
							</div>
<?php }?>
							<div class="col-md-12 mt-5">
								<a href="<?=Url::toRoute('/contact/information/meeting')?>"
									class="btn-main bg-btn3 lnk mt20">Schedule a Meeting <i
									class="fa fa-chevron-right fa-icon"></i><span class="circle"></span>
								</a>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!--------------------Sales Box End----------------------->

			<!-------------------Contact Form Start ------------------>
			<div
				class="col-lg-4 col-md-12  mx-auto mycontact-box yellow-box shadow-none">





				<h3>Get a quote</h3>
				<?php
    $form = TActiveForm::begin([
        'enableAntispamTags' => true
    ]);

    ?>
		
		<?php echo $form->field($model, 'type_id')->hiddenInput()->label(false) ?>
					<?php echo $form->field($model, 'full_name')->textInput(['maxlength' => 255])->label('Name')?>
					<?php echo $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
					<?php

    echo $form->field($model, 'mobile')
        ->input('tel', [
        'id' => "phone_number",
        'maxlength' => 10
    ])
        ->widget(PhoneInput::className(), [
        'options' => [
            'id' => 'contact_phone_number'
        ],
        'jsOptions' => [
            'separateDialCode' => true,
            'autoPlaceholder' => 'off',
            'initialCountry' => $model->country_code
        ]
    ]);
    ?>

					<?php echo $form->field($model, 'description')->textarea(['rows' => 4]);  ?>
					<div class="col-lg-12 col-md-12 text-center">
					<div class="text-center">
							<?php
    echo Html::submitButton('Send Message', [
        'class' => 'mt-20 butn btn-gredient',
        'id' => 'contact-form-submit',
        'name' => 'submit-button'
    ])?>
						</div>
				</div>
					<?php TActiveForm::end(); ?>
				</div>
			<!-------------------Contact Form End -------------->
		</div>
	</div>
	
<?php
/*
 * If contacts count greater than One then contact boxes shown here
 */
if ($addresscount > 1) {
    ?>
	<section class="global-bg">
		<div class="container-fluid fluid-2">
			<div class="row ">
				<div class="col-lg-12  mx-aut">
					<h2 class="global-text d-flex align-items-center">
						<img src="<?= $bundle->baseUrl . '/img/pin.svg'?>" alt="image"
							class="mr-2" /> <?=Yii::t('app',"Find us globally")?>
					</h2>
				</div>
       				<?=\Yii::$app->controller->renderPartial('_address', ['addressQuery' => $addressQuery,'bundle' => $bundle])?>	
			</div>
		</div>
	</section>
<?php }?>
</section>
