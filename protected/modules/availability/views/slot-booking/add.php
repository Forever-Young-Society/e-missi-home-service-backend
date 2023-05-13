<?php

/* @var $this yii\web\View */
/* @var $model app\modules\availability\models\SlotBooking */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Slot Bookings'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="slot-booking-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


