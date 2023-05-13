<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\service\models\search\Term */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Terms'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>
<div class="wrapper">
	<div class="card">
		<div class="term-index">
				<?=  \app\components\PageHeader::widget(); ?>
			</div>

	</div>
	<div class="card">
		<header class="card-header"> 
			  <?php echo strtoupper(Yii::$app->controller->action->id); ?> 
			</header>
		<div class="card-body">
			<div class="content-section clearfix">
					<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
				</div>
		</div>
	</div>
</div>

