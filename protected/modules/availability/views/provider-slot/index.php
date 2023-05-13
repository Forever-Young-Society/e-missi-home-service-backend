<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\availability\models\search\ProviderSlot */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Availability Slots'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>
<div class="wrapper">
	<div class="card">
		<div class="provider-slot-index">
				<?=  \app\components\PageHeader::widget(['title'=>'Availability Slots']); ?>
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

