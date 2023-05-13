<?php
use app\modules\rating\models\Rating;
use kartik\rating\StarRating;
use app\models\User;

/* @var $dataProvider Rating */
/* @var $query Rating */
/* @var $model User */
/* @var $options */
?>
<div class="container">

	<div class="row">
	<?php
$average = $query->average('rating');
$total = $query->count();
if ($options['showAverage']) {

    ?>
		<div class="col-sm-6">
			<div class="rating-block">
				<h4>Average Rating</h4>
				<h2 class="bold padding-bottom-7">
					<?=number_format((float) $average, 1, '.', '');?> <small>/ 5</small>
				</h2>
		<?php

    echo StarRating::widget([
        'name' => 'rating_2',
        'value' => $average,
        'disabled' => true
    ]);
    ?>
			</div>
		</div><?php
}
?>
	<?php

if ($options['showBreakdown']) {
    $path = '//../modules/rating/widgets/views/_breakdown';
    ?>
		<div class="col-sm-6">
			<h4>Rating breakdown</h4>
			<div class="row">
				<div class="col-sm-12"><?=Yii::$app->controller->renderPartial($path, ['count' => (new Rating())->getStarCount($model, [5,4.5]),'total' => $total]);?></div>
				<div class="col-sm-12"><?=Yii::$app->controller->renderPartial($path, ['count' => (new Rating())->getStarCount($model, [4,3.5]),'total' => $total]);?></div>
				<div class="col-sm-12"><?=Yii::$app->controller->renderPartial($path, ['count' => (new Rating())->getStarCount($model, [3,2.5]),'total' => $total]);?></div>
				<div class="col-sm-12"><?=Yii::$app->controller->renderPartial($path, ['count' => (new Rating())->getStarCount($model, [2,1.5]),'total' => $total]);?></div>
				<div class="col-sm-12"><?=Yii::$app->controller->renderPartial($path, ['count' => (new Rating())->getStarCount($model, [1,0.5]),'total' => $total]);?></div>
			</div>
		<?php
}
?>
	</div>
	</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="review-block">
<?php

\yii\widgets\Pjax::begin();
?>
			
								<?php
        echo \yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'itemOptions' => [ // 'class' => 'item'
            ],
            'itemView' => '_comments',
            'options' => [ // 'class' => 'comments'
            ]
        ]);
        ?>
        <?php

        \yii\widgets\Pjax::end();
        ?>
			</div>
			</div>
		</div>

	</div>
	<!-- /container -->