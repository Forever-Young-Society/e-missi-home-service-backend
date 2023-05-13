<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user @app\models\User */
?>
<tr>
	<td bgcolor="#622B81" style="padding: 20px;">
		<h4
			style="font-size: 18px; margin: 0; font-weight: 600; color: rgb(33, 33, 33); font-weight: 100;">
					Hi <?php echo  Html::encode($user->full_name) ?>,
				</h4>
		<p style="margin: 0;">Welcome to <?=Yii::$app->name; ?><br> Thanks for
			signing up. To continue, please confirm your email address by filling
			the OTP in OTP verification screen.
		</p>
		<p style="margin-bottom: 20px;">OTP is <?= $user->otp ?>.</p>
	</td>
</tr>
