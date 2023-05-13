<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sms\models\search\History */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index'); */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Histories'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');

?>
<div class="wrapper">
		<div class="card">
			<div class="history-index">				
			<?=  \app\components\PageHeader::widget(); ?>
			</div>

		</div>
		<div class="card">
			<header class="card-header ">   <?php echo strtoupper(Yii::$app->controller->action->id); ?> </header>
			<div class="card-body">
				<div class="content-section clearfix">
					<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
				</div>
			</div>
		</div>
	</div>


