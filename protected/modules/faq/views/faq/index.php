<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\faq\models\search\Faq */
/* @var $dataProvider yii\data\ActiveDataProvider */
$action = Yii::$app->controller->action->id;
$title = "User FAQ's";
if ($action == "provider-index") {
    $title = "Provider FAQ's";
}
?>
<div class="wrapper">
	<div class="card">
		<div class="faq-index">
				<?=  \app\components\PageHeader::widget(['title' => $title]); ?>

			</div>

	</div>
	<div class="card">
		<header class="card-header"> 
			  <?php echo Yii::t('app','INDEX'); ?> 
			</header>
		<div class="card-body">
			<div class="content-section clearfix">
					<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
				</div>
		</div>
	</div>
</div>

