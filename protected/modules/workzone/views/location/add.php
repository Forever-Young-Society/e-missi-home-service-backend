<?php

/* @var $this yii\web\View */
/* @var $model app\modules\workzone\models\Location */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Locations'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="location-create">
			<?=  \app\components\PageHeader::widget(['title'=>'Workzones']); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


