<?php
use yii\helpers\Url;
use yii\helpers\VarDumper;

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

?>
<style>
.theme-btn {
	border: 2px solid #fff;
	padding: 10px 30px;
	display: inline-block;
}
</style>
<?=$this->render('@app/mail/header.php');?>

<!--body start-->

<tr>
	<td style="background-color: #f5f5f5;">
		<table style="width: 100%;">
			<tbody>
				<tr>
					<td align="center" class="rounded-icon"
						style="background-color: #0083eb; padding: 60px 0">
						<h1 style="color: #fff; margin-bottom: 0;">Thank you for contacting us!</h1>
						<p
							style="color: #fff; font-weight: 500; margin-bottom: 30px; line-height: 1.5;">
							Our team will get back to you as soon as possible.</p>
					</td>

				</tr>
			</tbody>
		</table>
	</td>
</tr>
<!--body end-->

<?=$this->render('@app/mail/footer.php');?>


        