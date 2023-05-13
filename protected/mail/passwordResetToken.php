<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = $user->getResetUrl();
?>
<!--- body start-->
<tr>
	<td style="padding: 20px 30px">
		<table width="100%">
			<tr>
				<td style="padding: 20px 30px 20px 30px; background: #622B81"
					align="center">
					<h2
						style="font-size: 28px; margin: 0; color: #fff; line-height: 30px;">
						Warm Greetings ! !</h2>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td style="padding: 20px 30px 30px 30px">
		<h3 style="font-size: 20px; margin: 0px;">Hi  <?php echo  Html::encode($user->full_name) ?>,</h3>
		<p>Follow the link below to reset your password:</p>

		<p style="margin: 20px 0 30px;">

			<a href="<?= Html::encode($resetLink)?>"
				style="background-color: #622B81; border-radius: 3px; color: #fff; display: inline-block; font-size: 16px; font-weight: normal; line-height: 45px; text-align: center; text-decoration: none; width: 160px; -webkit-text-size-adjust: none; border: 1px solid #622B81;"
				target="_blank">Reset Password</a>
		</p>
	</td>
</tr>
<!--body end-->

