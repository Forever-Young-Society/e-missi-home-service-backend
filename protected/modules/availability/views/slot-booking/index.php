<?php
use app\modules\availability\models\SlotBooking;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\availability\models\search\SlotBooking */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Slot Bookings'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
$grid = isset($revenue) ? '_revenue-grid' : '_grid';
?>
<div class="wrapper">
	<div class="card">
		<div class="slot-booking-index">
				<?=  \app\components\PageHeader::widget(['title'=>'Bookings']); ?>
			</div>

	</div>
	<div class="card">
		<header class="card-header"> 
			  <?php echo strtoupper(Yii::$app->controller->action->id); ?> 
			</header>
		<div class="card-body">
			<div class="content-section clearfix">
					<?php echo $this->render($grid, ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
				</div>
		</div>
	</div>
</div>

