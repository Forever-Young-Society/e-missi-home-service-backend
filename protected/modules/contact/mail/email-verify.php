<?php
use yii\helpers\Url;

/**
 *
 * @copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 *        
 *         All Rights Reserved.
 *         Proprietary and confidential : All information contained herein is, and remains
 *         the property of ToXSL Technologies Pvt. Ltd. and its partners.
 *         Unauthorized copying of this file, via any medium is strictly prohibited.
 *        
 */
/* @var $this yii\web\View */
// $Link = $user->getLoginUrl();
?>
<style>
.theme-btn {
	border: 2px solid #0083ebff;
	padding: 10px 30px;
	display: inline-block;
	background-color: #0083ebff;
}
</style>
<?=$this->render('@app/mail/header.php');?>

<!--body start-->

<tr>
	<td style="background-color: #022a5e;">
		<table style="width: 100%;">
			<tbody>
				<tr>
					<td align="center" class="rounded-icon" style="padding: 60px 0">
						<h1 style="color: #fff; margin-bottom: 0;">Verify email address</h1>
						<p
							style="color: #fff; font-weight: 500; margin-bottom: 30px; line-height: 1.5;">
							Thank you for contacting us! We have received your request and
							are happy to have you. Please confirm your e-mail address by
							clicking the button below.</p> <a
						href="<?= Url::toRoute(['/contact/information/verify-email','id' => $model->id])?>"
						class="theme-btn"
						style="color: #fff; font-weight: 500; text-decoration: none;">VERIFY
							EMAIL</a>
					</td>

				</tr>
			</tbody>
		</table>
	</td>
</tr>
<!--body end-->

<?=$this->render('@app/mail/footer.php');?>


        