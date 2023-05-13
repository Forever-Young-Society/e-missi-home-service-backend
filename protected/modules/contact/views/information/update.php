<?php

/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\Information */

/*
 * $this->title = Yii::t('app', 'Update {modelClass}: ', [
 * 'modelClass' => 'Information',
 * ]) . ' ' . $model->id;
 */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Contact'),
    'url' => [
        '/contact'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Information'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => $model->id,
    'url' => [
        'view',
        'id' => $model->id
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class="card">
		<div class="information-update">
	<?=  \app\components\PageHeader::widget(['model' => $model]); ?>
	</div>
	</div>


	<div class="content-section  card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

