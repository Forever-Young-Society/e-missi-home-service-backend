<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
use yii\helpers\Url;

?>

<div class="container">
	<section class="call-action">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<h3 class="call-title">Ready to discuss your requirements?</h3>
					<div class="pad-btm5">
						<a
							href="<?= Url::toRoute(['/contact-us' ,'ref' => Url::canonical()]) ?>"
							class="btn btn-default"> Get in touch</a>
					</div>


				</div>

			</div>
		</div>
	</section>
</div>

