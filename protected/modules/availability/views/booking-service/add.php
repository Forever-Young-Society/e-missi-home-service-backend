<?php

/* @var $this yii\web\View */
/* @var $model app\modules\availability\models\BookingService */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Booking Services'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="booking-service-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


