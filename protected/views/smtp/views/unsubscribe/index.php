<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\smtp\models\search\Unsubscribe */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'SMTP'),
    'url' => [
        '/smtp'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Unsubscribes'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>
<div class="wrapper">
	<div class="card">
		<div class="unsubscribe-index">
				<?=  \app\components\PageHeader::widget(); ?>
			</div>

	</div>
	<div class="card">
	   <?php echo $this->render ( '_form', [ 'model' => $model ] )?>
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

