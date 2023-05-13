<?php

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

?>
<?=$this->render('@app/mail/header.php');?>

<!--body start-->

<tr>
	<td style="padding: 20px 30px 30px 30px" class="inner-td">
		<table style="border: 2px solid #022a5e">
			<tr>
				<td style="text-align: center; padding-top: 20px;">
					<p
						style="color: #022a5e; font-size: 24px; margin: 0px; font-family: 'Oswald-Regular';">Request
						Sent Successfully!</p>
				</td>
			</tr>
			<tr>
				<td style="padding: 20px;">
					<h2 style="margin: 0; font-weight: normal; font-size: 15px;">Hello <?php echo \yii\helpers\Html::encode ( $model->full_name )?>,</h2>
					<p>Thank you for reaching us. We have received your request, our
						team will get back to you soon on the following details :</p>

					<table style="border-collapse: collapse;" cellpadding="6"
						width="100%">
						<tr>
							<td style="border-bottom: 1px solid #bdc8d7; color: #022a5e"
								width="50%">User Name:</td>
							<td style="border-bottom: 1px solid #bdc8d7;" width="50%"><?=$model->full_name;?></td>
						</tr>
						<tr>
							<td style="border-bottom: 1px solid #bdc8d7; color: #022a5e"
								width="50%">Email:</td>
							<td style="border-bottom: 1px solid #bdc8d7" width="50%"><?=$model->email;?></td>
						</tr>
						<tr>
							<td style="border-bottom: 1px solid #bdc8d7; color: #022a5e"
								width="50%">Contact No:</td>
							<td style="border-bottom: 1px solid #bdc8d7" width="50%"><?=$model->mobile;?></td>
						</tr>
						<tr>
							<td style="border-bottom: 1px solid #bdc8d7; color: #022a5e"
								width="50%">Skype ID:</td>
							<td style="border-bottom: 1px solid #bdc8d7" width="50%"><?=$model->skype;?></td>
						</tr>
						<tr>
							<td style="border-bottom: 1px solid #bdc8d7; color: #022a5e"
								width="50%">Country:</td>
							<td style="border-bottom: 1px solid #bdc8d7" width="50%"><?=$model->country;?></td>
						</tr>
						<tr>
							<td style="border-bottom: 1px solid #bdc8d7; color: #022a5e"
								width="50%">IP Address:</td>
							<td style="border-bottom: 1px solid #bdc8d7" width="50%"><?=$model->ip_address;?></td>
						</tr>
						<tr>
							<td style="color: #022a5e" colspan="2">Project Details:</td>
						</tr>
						<tr>
							<td colspan="2">
								<p style="margin: 0"><?= $model->detail; ?></p>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<p style="margin: 0">Please confirm the enquiry :<?= $model->getAbsoluteUrl('confirm') ?></p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
<!--body end-->

<?=$this->render('@app/mail/footer.php');?>


        