<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\settings\models\search\Variable */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Settings'),
    'url' => [
        '/settings/default'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Variables'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>
<div class="wrapper">
	<div class="card">
		<div class="variable-index">
				<?=  \app\components\PageHeader::widget(); ?>
			</div>

	</div>
	<div class="card">
		<header class="card-header"> 
			  <?php echo strtoupper(Yii::$app->controller->action->id); ?> 
			</header>
		<div class="card-body">
			<div class="content-section clearfix">
			<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel,'model' => $model]); ?>
				</div>
		</div>
	</div>
</div>

