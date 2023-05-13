  <?php
use app\modules\contact\models\SocialLink;
use app\modules\contact\assets\ContactAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$bundle = ContactAsset::register(Yii::$app->view);
?>
<section class="tnq-wrapper">
	<div class="container vector">
		<div class="row">
			<div class="col-lg-8">
				<h1 class="c-heading bg"
					style="font-family: poppins, sans-serif, open sans, roboto;"><?php echo \Yii::t('app', "Thank You!  "); ?></h1>
				<p class="mt-2"><?= \Yii::t('app', 'We have received your inquiry. We appericiate you for your interest in our services. Our team will get back to you soon.');?></p>
				<br><br>
				<a href="<?= $model->getUrl('callme') ?>"
					class="butn btn-gredient btn-theme">Request Callback Now </a>

				<?php if(SocialLink::getSocialLinksCount() > 0){?>
				<br><br><br><p><?= \Yii::t('app','Meanwhile, follow us on our social channels to get the latest updates.'); ?></p>
				<div class="social">
                    <?php if($linkModel = SocialLink::getLinkModel('twitter')){?>
                                    <a href="<?=$linkModel->ext_url?>">
						<img src="<?= $bundle->baseUrl . '/img/twitter.png'?>"
						alt="Twitter">
					</a>
                         <?php }?>
                                     <?php if($linkModel = SocialLink::getLinkModel('linkedin')){?> 
									<a href="<?=$linkModel->ext_url?>"> <img
						src="<?= $bundle->baseUrl . '/img/linkedin.png'?>" alt="Linkdin"></a>
                                    <?php } ?>
                                    <?php if($linkModel = SocialLink::getLinkModel('facebook')){?>
                                   <a href="<?= $linkModel->ext_url?>">
						<img src="<?= $bundle->baseUrl . '/img/facebook.png'?>"
						alt="Facebook">
					</a>
                                   <?php } ?>
                                   <?php if($linkModel = SocialLink::getLinkModel('instagram')){?> 
                                  	<a href="<?=$linkModel->ext_url?>"><img
						src="<?= $bundle->baseUrl . '/img/instagram.png'?>"
						alt="Instagram"></a>
                                    <?php }?>
                                    <?php if($linkModel = SocialLink::getLinkModel('youtube')){?>  
                                   	<a href="<?=$linkModel->ext_url?>"><img
						src="<?= $bundle->baseUrl . '/img/youtube.png'?>" alt="Youtube"></a>
							 <?php }?>  
                        </div>
                   <?php } ?>
			</div>
			<div class="col-lg-4">
				<img src="<?= $bundle->baseUrl . '/img/support.png'?>" alt=""
					class="img-fluid">
			</div>
		</div>
	</div>
</section>
