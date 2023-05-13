<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index'); */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Users'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
if (\Yii::$app->controller->action->id == 'provider') {
    $title = "Service Providers";
    $header = "Index";
} elseif (\Yii::$app->controller->action->id == 'dependent-user') {
    $title = "Dependent Users";
    $header = "Index";
} elseif (\Yii::$app->controller->action->id == 'incomplete') {
    $title = "Incomplete Users";
    $header = "Index";
} elseif (\Yii::$app->controller->action->id == 'rejected') {
    $title = "Rejected Providers";
    $header = "Index";
} else {
    $title = "Users";
    $header = "Index";
}
?>
<div class="wrapper">
	<div class="card">
		<?=  \app\components\PageHeader::widget(['title'=>Yii::t('app',$title)]); ?>
	</div>
	<div class="card">
		<header class="card-header">   <?php echo strtoupper($header); ?> </header>
		<div class="card-body">
			<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
		</div>

	</div>
</div>


