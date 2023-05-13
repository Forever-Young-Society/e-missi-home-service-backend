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
$bundle = ContactAsset::register(Yii::$app->view);
$addressQuery = Address::getAllAddress();
$addresscount = $addressQuery->count();
?>
<section class="contact-us-wrapper pt-90">
	<div class="container pb-5">
		<div class="card-header my-5 border-0 mb-3 py-4">
			<h5><?= $model->description?></h5>
		</div>
		<div class="row shadow-lg">
			<div class="col-lg-8 col-md-12 mx-auto pr-0 p-5">
				<div class="titlebar pt-0 h-100 pr-0">
					<!--------------------Sales Box Start----------------------->
					<div class="h-100 bg-transparent">
						<h6 class="global-text d-flex align-items-center mb-4">
							<img src="<?= $bundle->baseUrl . '/img/call.svg'?>" alt="image"
								class="mr-2" /> <?=Yii::t('app',"Can't wait call us now")?>
						</h6>
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
							
							</div>
					</div>

				</div>
			</div>
			<!--------------------Sales Box End----------------------->

			<!-------------------Contact Form Start ------------------>
			<div class="col-lg-4 col-md-12  mx-auto mycontact-box yellow-box shadow-none">





				
				<?php
    $form = TActiveForm::begin([
        'options' => [
            'id' => 'contact_form_id',
            'class' => ''
        ]
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

					<?php echo $form->field($model, 'description')->hiddenInput()->label(false);  ?>
					<div class="col-lg-12 col-md-12 text-center">
					<div class="text-center">
							<?php
    echo Html::submitButton('Confirm', [
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


</section>
