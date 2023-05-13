<?php
use yii\helpers\Html;
use app\modules\rating\models\Rating;
use yii\helpers\Url;
use kartik\rating\StarRating;
/* @var $model Rating */

?>
<div class="row">
	<div class="col-sm-2">
		
			<?php

if (! empty($model->createdBy->profile_file)) {
    ?>
		<img src="<?=Url::toRoute(['//file/thumbnail','filename' => $model->createdBy->profile_file])?>"
			class="img-rounded"><?php
}
?>
		<div class="review-block-name">
			<a href="<?=Url::toRoute(['//user/profile','id' => $model->created_by_id])?>">
			<?=Html::encode($model->createdBy->full_name);?>
			</a>
		</div>
		<div class="review-block-date">
			<?=date("M d , Y", strtotime($model->created_on))?><br />
			<?=Yii::$app->formatter->format($model->created_on, 'relativeTime')?>

		</div>
	</div>
	<div class="col-sm-10">
		<div class="review-block-rate">
			<?php

echo StarRating::widget([
    'name' => 'rating_2',
    'value' => $model->rating,
    'disabled' => true
]);
?>
		</div>
		<div class="review-block-title"><?=Html::encode($model->title);?></div>
		<?php

if (! empty($model->comment)) {
    ?>
		<div class="review-block-description"><?=Html::encode($model->comment);?></div><?php
}
?>
	</div>
</div>
<hr />