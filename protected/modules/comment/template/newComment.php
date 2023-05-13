  <?php

/* @var $this yii\web\View */
/* @var $user @app\models\User */
?>
<tr>
	<td align="left" style="padding: 20px 40px;">
		<h3
			style="margin: 0; font-weight: 700; font-size: 20px; margin-bottom: 20px;">
			Dear <span><?php echo $user->full_name;?></span>,
		</h3>

		<p>A new comment has been added.</p>
		<p>
			Click link to see the comment <br> <?= $model->linkify(); ?></p>
		<p>
	
	</td>
</tr>
