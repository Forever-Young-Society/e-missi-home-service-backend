<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user app\models\User */
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
		<p>New user is successfully registered:</p>
	</td>
</tr>
<!--body end-->
