<?php
use app\components\TActiveForm;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;

use app\modules\contact\assets\ContactAsset;

$bundle = ContactAsset::register(Yii::$app->view);
?>
<section class="contact-us-wrapper pt-90">
	<div class="container-fluid fluid-2">
		<div class=" bg-light shadow-lg rounded-lg pr-lg-5">
			<div class="row align-items-center">
				<div class="col-xl-6 mx-auto">
					<div class="my-5 mx-lg-0 mx-4">
						<div class="text-center mt-5 mb-5">
							<h1><?=Yii::t('app',' Schedule a Meeting')?></h1>
						</div>
						<div>
							<div class="w-100 mt-4">
                     <?php
                    $form = TActiveForm::begin([
                        'id' => 'interview-form',
                        'method' => 'GET',
                        'options' => [
                            'class' => ''
                        ]
                    ]);
                    ?>
    						  <div
									class="w-100 pb-20 text-right mt-5 mt-lg-0 pl-lg-4 d-flex flex-column">
									<span class="d-block text-left mb-3"><?= Yii::t('app','Select Date and Time')?></span>
                    <?php
                    echo DateTimePicker::widget([
                        'name' => 'date',
                        'attribute' => 'date',
                        'value' => date('Y-m-d H:i', strtotime('+30 minutes')),
                        // 'type' => DateTimePicker::TYPE_INLINE,
                        'pluginOptions' => [
                            // 'format' => 'D, dd-M-yyyy, hh:ii',
                            'startDate' => date('Y-m-d H:i'),
                            'endDate' => date('Y-m-d', strtotime("+7 day")),
                            'autoclose' => true,
                            "hoursDisabled" => "0,1,2,3,4,5,6,7,8,21,22,23,24",
                            'daysOfWeekDisabled' => [
                                0
                            ]
                        ]
                    ])?>
                        </div>
								<div class="text-right mt-5 mt-lg-3 pl-lg-4 d-flex flex-column">
									<span class="d-block text-left"><?= Yii::t('app','Enter Purpose')?></span>
									<?php $p = 'Discuss about project.'?>
    					<?php echo Html::textarea( 'p', $p ,['rows' => 6,'class' => 'meeting-purpose mt-2 form-control bg-white text-dark pt-2']);  ?>
    					<?=Html::submitButton(Yii::t('app', 'Confirm Meeting'), ['id' => 'interview-form-submit','class' => 'btn btn-primary d-inline-block ml-auto mt-4'])?>
    				</div>
                   <?php
                TActiveForm::end();
                ?>
                </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
