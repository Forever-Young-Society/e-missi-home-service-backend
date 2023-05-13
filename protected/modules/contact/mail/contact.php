<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\components\Json2Table;

/* @var $this yii\web\View */

// $Link = $user->getLoginUrl();
?>
<?=$this->render('@app/mail/header.php');?>

<tr>
	<td style="padding: 30px 40px 40px 40px">
		<table style="border: 2px solid #022a5e" width="100%">
			<tr>
				<td style="text-align: center; padding-top: 20px;">
					<p
						style="color: #022a5e; font-size: 24px; margin: 0px; font-family: 'Oswald-Regular';">
						New Contact Request!</p>
				</td>
			</tr>
			<tr>
				<td style="padding: 20px;">
					<h2 style="margin: 0; font-size: 16px;">Dear
						Admin</h2>
					<p>A new request has been generated. Below are the complete details of the user.
					</p>
					<p>Details:</p>
					<?php echo Json2Table::arrayToHtmlTableRecursive($user->asJson()) ?>
					
								<p style="margin: 0">Verify the details by clicking the link: <?= $user->linkify(null,true) ?></p>

				</td>
			</tr>
		</table>
	</td>
</tr>


<?=$this->render('@app/mail/footer.php');?>






