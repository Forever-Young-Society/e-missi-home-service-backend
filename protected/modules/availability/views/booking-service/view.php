<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\availability\models\BookingService */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Booking Services'),
    'url' => [
        'index'
    ]
];

$this->params['breadcrumbs'][] = (string) $model;

?>
<div class="wrapper">
	<div class="card">
		<div class="booking-service-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'booking-service-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
            /*'title',*/
            'booking_id',
                'service_id',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
                'created_on:datetime',
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
            ]
        ])?>
         <?php

        echo UserAction::widget([
            'model' => $model,
            'attribute' => 'state_id',
            'states' => $model->getStateOptions()
        ]);
        ?>
      </div>
	</div>

</div>
