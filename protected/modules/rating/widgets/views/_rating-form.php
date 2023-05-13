<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use kartik\rating\StarRating;
use app\modules\rating\models\Rating;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model User */
/* @var $form yii\widgets\ActiveForm */
/* @var $ratingModel Rating */
?>


<div class="container" style="margin-bottom: 40px;">
	<div class="col-md-12">
		<div class="text-right">
			<a class="btn btn-success btn-green" href="#reviews-anchor"
				id="open-review-box">Leave a Review</a>
		</div>

		<div class="row" id="post-review-box" style="display: none;">
			<div class="panel">
				<div class="panel-heading">
					<h4>Give your Rating</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
                  <?php
                $form = TActiveForm::begin([
                    'layout' => 'horizontal',
                    'id' => 'rating-form',
                    'options' => [
                        'class' => 'row'
                    ],
                    'action' => [
                        '/rating/rating/add'
                    ]
                ]);

                echo $form->errorSummary($model);
                ?>
 <?=$form->field($ratingModel, 'model_id')->hiddenInput(['value' => $model->id])->label(false);?>
 <?=$form->field($ratingModel, 'model_type')->hiddenInput(['value' => get_class($model)])->label(false);?>
	 <?php
echo $form->field($ratingModel, 'title')->textInput();
?>
								 <?php
        echo $form->field($ratingModel, 'comment')->textarea([
            'rows' => 5,
            'cols' => 50,
            'id' => 'new-review',
            'placeholder' => 'Enter your review here...'
        ]);
        ?>
        
        	<?=$form->field($ratingModel, 'rating')->widget(StarRating::classname());?>
        
</div>

						<div class="row">
							<div class="col-md-6 col-md-offset-3">
								<div class="text-right">

									<a class="btn btn-success btn-sm" href="#"
										id="close-review-box"
										style="display: none; margin-right: 10px;"> <span
										class="glyphicon glyphicon-remove"></span>Cancel
									</a>
<?=Html::submitButton(Yii::t('app', 'Save'), ['id' => 'rating-form-submit','class' => 'btn btn-success btn-sm'])?>
							
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