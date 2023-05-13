<?php
use app\modules\page\models\Page;

/* @var $this yii\web\View */
/* @var $searchModel Page */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index'); */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Pages'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>
<div class="wrapper">
	<div class="user-index">
		<div class="card ">
				<div class="page-index">

<?=\app\components\PageHeader::widget();?>
    <?php 
// echo $this->render('_search', ['model' => $searchModel]); ?>
  </div>
		</div>
		<div class="card panel-margin">
			<header class="card-header head-border">   <?php

echo strtoupper(Yii::$app->controller->action->id);
?> </header>
			<div class="card-body">
				<div class="content-section clearfix">
					
		<?php

echo $this->render('_grid', [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel
]);
?>
</div>
			</div>
		</div>
	</div>

</div>

