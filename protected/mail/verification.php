<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user @app\models\User */

$Link = $user->getVerified();

?>
<tr>
	<td bgcolor="#ffffff" style="padding: 20px;">
		<h4
					style="font-size: 18px; margin: 0; font-weight: 600; color: rgb(33, 33, 33);font-weight:100;">
					Hi <?php echo  Html::encode($user->full_name) ?>,
				</h4>
				<p style="margin:0;">Welcome to <?=Yii::$app->name; ?><br>
				Thanks for signing
					up. To continue, please confirm your email address by clicking the
					button below.</p>
				<p style="padding: 10px 20px">
					<a
						style="display: inline-block; text-decoration: none; background-color: #3d9c68; padding:10px 20px;border: 1px solid #3d9c68; border-radius: 3px; color: #FFF; font-weight: bold;"
						href="<?= $Link ?>" target="_blank">Verify Email</a>
				</p>
				<p
					style="margin-bottom: 20px;">If
					above link isn't working, please copy and paste it directly in you
					browser's URL field to get started.</p>
				<p style="margin-bottom: 20px;">
					<a href="<?php echo $Link;?>" style="color: #3d9c68; font-size: 14px;"><?php echo $Link;?> </a>
	     		 </p>
	</td>
</tr>
