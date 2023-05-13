<?php

/* @var $this yii\web\View */
/* @var $model app\models\File */

/*
 * $this->title = Yii::t('app', 'Update {modelClass}: ', [
 * 'modelClass' => 'File',
 * ]) . ' ' . $model->name;
 */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Files'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->name,
    'url' => [
        'view',
        'id' => $model->id
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class="card">
		<div class="file-update">
	<?=  \app\components\PageHeader::widget(['model' => $model]); ?>
	</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

