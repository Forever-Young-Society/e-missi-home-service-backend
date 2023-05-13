<?php
use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

//$this->title = yii::$app->name;
AppAsset::register($this);
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport"
	content="width=device-width,initial-scale=1,maximum-scale=1">
<meta charset="<?= Yii::$app->charset ?>" />
    <?= Html::csrfMetaTags()?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head()?>


<link rel="shortcut icon"
	href="<?= $this->theme->getUrl('img/favicon.ico')?>" type="image/png">
<link id="theme-style" rel="stylesheet" href="<?php echo $this->theme->getUrl('css/styles.css')?>">


</head>
<body class="home-page">
<?php $this->beginBody()?>
<!-- * Facebook Like button script starts -->

	<!-- ******HEADER****** -->
	<header id="header" class="header">
		<div class="container">
			<h1 class="logo mb-3">
				<a href="<?= Url::home();?>"> <img
					src="<?= $this->theme->getUrl('img/logo.png');?>" width="130px"
					alt="<?=Yii::$app->name?>">
				</a>
			</h1>
			<!--//logo-->
			 <nav id="main-nav" class="navbar navbar-expand-lg main-nav pr-0 mb-0">
               <button class="navbar-toggler navbar-toggle" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
               <span class="navbar-toggler-icon icon-bar"></span>
               <span class="navbar-toggler-icon icon-bar"></span>
               <span class="navbar-toggler-icon icon-bar"></span>
               </button>
           </nav>
		
			<!--//main-nav-->
		</div>
		<!--//container-->
	</header>
	<!--//header-->



	<!-- body content start-->
	<div class="main_wrapper">
		<?= $content?>
       
	</div>
	<!--body wrapper end-->

	<footer>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="hk_tiny_footer">
						<p>&copy; 2016-<?php echo date('Y')?>  <a target="_blank"
								href="<?= Yii::$app->params['companyUrl'];?>"><?= Yii::$app->params['company']?></a>
							| All Rights Reserved
						</p>
					</div>
				</div>
			</div>
		</div>
	</footer>


	<script type="text/javascript"
		src="<?php echo $this->theme->getUrl('assets/js/main.js')?>"></script>


	<!--[if !IE]>-->

	<!--<![endif]-->
    <?php $this->endBody()?>

</body>
<?php $this->endPage()?>
</html>