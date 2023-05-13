<li class="clearfix">
	<img src="https://bootdey.com/img/Content/user_1.jpg" class="avatar" alt="">
	<div class="post-comments">
	<?php if(isset($model->created_by_id)) {?>
		<p class="meta"> 
		<?= \yii::$app->formatter->asDatetime($model->created_on)?> 
		<?= $model->createdBy->linkify() ?> says : 
		<!-- <i class="pull-right"><a href="#"><small>Reply</small></a></i> -->
		</p>
	<?php }else{?>
		<p class="meta"> 
		<?= \yii::$app->formatter->asDatetime($model->created_on)?>
		<?= $model->getModel()->linkify() ?> says : 
		<!-- <i class="pull-right"><a href="#"><small>Reply</small></a></i> -->
		</p>
	<?php }?>
		 <?= $model->message?>
	</div>
</li>
