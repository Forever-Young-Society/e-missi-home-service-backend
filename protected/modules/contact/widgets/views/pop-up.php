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
?>
<!------Pop-up banner------->
<div class="pop-up-banner-box">
	<div class="pop-up-banner-button">
		<button class="hit-box">
		
		<?php if($badge_img_url){?>
		
					<img src="<?= $this->theme->getUrl($badge_img_url)?>"
				class="img-fluid vert-move" alt="">
		<?php }else{?>
			<div class="main-santa-box">
				<div class="santa santa-box">
					<div class="santa__content">
						<div class="santa__head">
							<div class="santa__hat">
								<span></span>
							</div>
							<span class="santa__face"></span>
							<div class="santa__eyes">
								<span class="santa__eye"></span> <span class="santa__eye"></span>
							</div>
							<span class="santa__nose"></span>
							<div class="santa__beard">
								<span class="santa__moustache"></span>
							</div>
							<span class="santa__mouth"></span>
						</div>
						<div class="santa__arms">
							<div class="santa__arm">
								<span class="santa__hand"></span>
							</div>
							<div class="santa__arm">
								<span class="santa__hand"></span>
							</div>
						</div>
						<div class="santa__body">
							<span class="santa__belt"></span>
						</div>
						<div class="santa__legs">
							<span class="santa__leg"></span> <span class="santa__leg"></span>
						</div>
					</div>
					<img
						src="https://thumbs.gfycat.com/IllegalLimitedBettong-max-1mb.gif">
				</div>
			</div>
				<?php }?>
		</button>
	</div>
	<div class="black-friday-wrapper">
		<a class="triigerModal" data-toggle="modal" data-target="#popUpDeal">
			<img src="<?= $this->theme->getUrl($img_url)?>" class="img-fluid"
			alt="">
		</a>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="popUpDeal" tabindex="-1" role="dialog"
	aria-labelledby="blackFridayTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			
			<div class="modal-body">
				<a
					href="<?= Url::toRoute(['/contact-us','d'=>$cookie_name])?>">
					<button type="button" class="close" data-dismiss="modal"
				aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
					<img src="<?= $this->theme->getUrl($img_url)?>" class="img-fluid"
					alt="">
				</a>
			</div>
		</div>
	</div>
</div>
<!------Pop-up banner Ends------->
<script>

      $('#popUpDeal').modal({
         backdrop: 'static',
         keyboard: false,
         show: false
      }); 
<?php

$cookies = \Yii::$app->request->cookies;
if (empty($cookies[$cookie_name])) {
    ?>
	   $('#popUpDeal').modal('show');
	   
<?php
} else {
    ?>
$('.pop-up-banner-box').addClass('show-box');
<?php }?>
      $('#popUpDeal').on('hidden.bs.modal', function () {
         $('.pop-up-banner-box').addClass('show-box');
      })
      $('.hit-box').on('click', function () {
         $('.pop-up-banner-box').toggleClass('showtopbox');
      });
      $('.triigerModal').on('click', function () {
         $('.pop-up-banner-box').toggleClass('show-box').removeClass('showtopbox');
         ;
      });
      
      
   </script>
