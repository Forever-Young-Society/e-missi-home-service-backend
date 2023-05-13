<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\modules\contact\models\Information;
use yii\helpers\Url;
use borales\extensions\phoneInput\PhoneInput;

/**
 *
 * @copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 *        
 *         All Rights Reserved.
 *         Proprietary and confidential : All information contained herein is, and remains
 *         the property of ToXSL Technologies Pvt. Ltd. and its partners.
 *         Unauthorized copying of this file, via any medium is strictly prohibited.
 *        
 */
?>
<div class="wrapper">
	<div class="page-title-wrapper">
		<div class="container-fluid main-container form-main-container">
			<div class="row align-items-center">
				<div class="col-md-8 col-lg-6  col-xl-5 offset-lg-2 mx-auto py-5">
					<div class="contact-right-wrapper form-cover">
						<h3 class="mb-md-25 mb-15">We shall be happy to help you.</h3>
						<?php
    $keyword = Yii::$app->request->getQueryParam('keyword');
    if ($keyword) {
        echo 'Are you looking for <b>' . $keyword . '</b>';
    }
    ?>
					
						 <?php
    $form = TActiveForm::begin([
        'enableAntispamTags' => true,
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
			</div>
		</div>
	</div>
</div>


