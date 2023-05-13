<?php
use yii\helpers\Html;
use app\models\User;
?>

<li><div class="items">
		<div class="menu-icon">	
	<?php
if (isset($model->createdBy)) {
    echo Html::img($model->createdBy->getImageUrl(), [
        'class' => 'img-responsive',
        'alt' => $model->createdBy,
        'height' => '50',
        'width' => '50'
    ]);
}
?>
		</div>
		<div class="menu-text">
			<p><?php echo $model->comment?> </p>
			<ul class="nav" style="display: inline;"></ul>
		</div>
		<div class="menu-text">
			<div class="menu-info">
		<?php if(isset($model->createdBy)) {?>
				<?= $model->createdBy->linkify() ?> - <span class="menu-date"><?= \yii::$app->formatter->asDatetime($model->created_on)?> </span>
			<?php } else {?>
			<?= !empty($model->getModel()) ? $model->getModel()->linkify() : '' ?> - <span
					class="menu-date"><?= \yii::$app->formatter->asDatetime($model->created_on)?> </span>
			<?php }?>

			</div>

			<div class="menu-text" style="text-align: right">
			<?php
			if( User::isAdmin()){
   echo Html::a('<i class="fa fa-times"></i>', $model->getUrl('delete'), [
    'class' => 'badge badge-danger',
    'data' => [
        'method' => 'POST',
        
        'confirm' => Yii::t('app', 'Are you sure you want to delete this ?')
    ]
]);
			}
?>
</div>
		</div>
	</div></li>
<div class="clearfix"></div>





