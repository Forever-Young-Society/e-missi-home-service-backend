<?php
use yii\helpers\Html;
?>

<div class="card  widget comment-view">
	<div class="card-header">

		<h4>Comments</h4>

	</div> 	<?php //Pjax::begin(['id'=>'comments']); ?>
	<div id='comments' class="card-body panel-body-list">


<?php if ($model &&  !Yii::$app->user->isGuest) {?>
<?=$this->render ( '_form', [ 'model' => $model ] )?>

    <?php }?>
    		<div id='comments-list'
			class="content-list content-image menu-action-right">
			<?php
$this->registerJs("$('#comments-list').load('" . $url . "');");
?>
 <?php echo Html::tag('div', 'Loading');?>
		</div>
	</div>

</div>

