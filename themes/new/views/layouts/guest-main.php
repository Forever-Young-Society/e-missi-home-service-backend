<?php
use app\assets\AppAsset;
use app\components\gdpr\Gdpr;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\FlashMessage;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$controller = \Yii::$app->controller->id;
$action = \Yii::$app->controller->action->id;
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta name="viewport"
	content="width=device-width,initial-scale=1,maximum-scale=1">
<meta charset="<?= Yii::$app->charset ?>" />
      <?= Html::csrfMetaTags()?>
      <title> <?= Html::encode($this->title) ?></title>
      <?php $this->head()?>
         <?php
        $this->registerLinkTag([
            'rel' => 'icon',
            'type' => 'image/png',
            'href' => $this->theme->getUrl('img/favicon.ico')
        ]);
        /* -- Plugins CSS -- */
        $this->registerCssFile($this->theme->getUrl('css/font-awesome.css'));
        /* --Theme CSS --- */
        $this->registerCssFile($this->theme->getUrl('css/styles.css'));
        $action = Yii::$app->controller->action->id;
        ?>
   </head>
<body class="home-page">
      <?php $this->beginBody()?>
      <!-- ******HEADER****** -->
      <?php if($action != 'success' && $action != 'failed'){?>
	<header class="py-2 hd-card">
		<div class="container-fluid">
			<nav class="navbar navbar-expand-lg bg-white align-items-center p-0">
				<a class="navbar-brand" href="<?= Url::home();?>"> <img
					src="<?=$this->theme->getUrl ('img/forever.png')?>">
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse"
					data-target="#collapsibleNavbar">
					<span class="navbar-toggler-icon w-auto h-auto"><i
						class="fa fa-bars"></i></span>
				</button>
				<div class="collapse navbar-collapse" id="collapsibleNavbar">
					<ul class="navbar-nav ml-auto align-items-center d-lg-flex d-block">
						<li
							class="<?php echo ( $controller == 'site' && $action == 'index' ) ? 'active' : null; ?> nav-item"><a
							href="<?= Url::home();?>" class="nav-link">Home</a></li>
                        <?php if(User::isAdmin()){?>
                        <li
							class="<?php echo ( $controller == 'site' && $action == 'info' ) ? 'active' : null; ?> nav-item"><a
							href="<?=Url::to(['site/info']);?>" class="nav-link">Info</a></li>
                        <?php  } ?>
                     <li
							class="<?php echo ( $controller == 'site' && $action == 'contact' ) ? 'active' : null; ?> nav-item"><a
							href="<?=Url::to(['/contactus']);?>" class="nav-link">Contact Us</a></li>
                 		    <?php   if(User::isGuest()){?>
                 		    <li
							class="<?php echo ( $controller == 'site' && $action == 'index' ) ? 'active' : null; ?> nav-item"><a
							href="<?php echo Url::to(['/site/terms']);?>" class="nav-link">Terms</a></li>
						<li
							class="<?php echo ( $controller == 'site' && $action == 'index' ) ? 'active' : null; ?> nav-item"><a
							href="<?php echo Url::to(['/site/about']);?>" class="nav-link">About
								Us</a></li>
								<?php if($action != 'login'){?>
						<li class="nav-item  ml-0 ml-lg-3 py-lg-0 py-2"><a
							class="btn-success" href="<?php echo Url::to(['/user/login']);?>">
								log in</a></li>
								<?php }?>
                     <?php
        } else {
            ?>
                     <li class="nav-item nav-item-cta last ml-0 ml-lg-3"><a
							href="<?php echo Url::to(['dashboard/index']);?>"
							class="nav-link"><button type="button"
									class="btn btn-success nav-link">Dashboard</button></a></li>
                     <?php    }?>    
                  </ul>
				</div>
			</nav>
		</div>
	</header>
	<?php }?>
	<!--//header-->
      <?= Gdpr::widget();?>
      <!-- body content start-->
	<div class="main_wrapper">
         <?= $content?>
         <?php //echo FlashMessage::widget()?>
      </div>
	<!--body wrapper end-->
	<?php if($action != 'success' && $action != 'failed'){?>
	<div class="footer-bottom">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 text-center">
					<p class="text-white mb-0">&copy; <?php echo date('Y')?>  
                        <a class="ftr-anch" href="<?= Url::home();?>"><?=Yii::$app->name?></a>
						| All Rights Reserved.
					</p>

				</div>
			</div>
		</div>
	</div>
	<?php }?>
	<!-- Javascript -->
   
     <?php $this->endBody()?>
     
   </body>
   <?php $this->endPage()?>
</html>