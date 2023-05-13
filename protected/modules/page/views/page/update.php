<?php
use app\modules\page\models\Page;

/* @var $this yii\web\View */
/* @var $model Page */

/*
 * $this->title = Yii::t('app', 'Update {modelClass}: ', [
 * 'modelClass' => 'Page',
 * ]) . ' ' . $model->title;
 */
$this->params['breadcrumbs'][] = [
	'label' => Yii::t('app', 'Pages'),
	'url' => [
		'index'
	]
];
$this->params['breadcrumbs'][] = [
	'label' => $model->title,
	'url' => [
		'view',
		'id' => $model->id
	]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class="card ">
		<div
			class="page-update">
	<?=\app\components\PageHeader::widget(['model' => $model]);?>
	</div>
	</div>


	<div class="content-section clearfix panel">
		<?=$this->render('_form', ['model' => $model])?></div>
</div>

