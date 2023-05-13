<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

// $this->title = $name;
?>
<section class="py-5">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10 col-md-12 text-center">
				<div class="bg-white box-shadow p-4">
					<a href="<?= Yii::$app->homeUrl ?>"> <img
						src="<?php echo $this->theme->getUrl("img/error.png")?>"></a>
						<h3 class="mb-20 text-danger"><?php echo $name?></h3>
						<a class="btn btn-success rounded-lg mt-3"
						href="<?= Yii::$app->homeUrl ?>"><span>Back to Home</span> <i
						class="fa fa-space-shuttle"></i></a>
					</div>
				</div>
			</div>
		</div>
</section>
