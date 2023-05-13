<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\modules\availability\models\SlotBooking;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\availability\models\SlotBooking */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Slot Bookings'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="slot-booking-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'slot-booking-detail-view',
            'model' => $model,
            'attributes' => [
                'id',

                [
                    'attribute' => 'service_id',
                    'label' => 'Booked Services',
                    'format' => 'raw',
                    'value' => $model->getBookedServicesName()
                ],
                'start_time:datetime',
                'end_time:datetime',
                [
                    'attribute' => 'user_amount',
                    'value' => ! empty($model->user_amount) ? User::DEFAULT_CURRENCY . ' ' . $model->user_amount : SlotBooking::STATE_PENDING
                ],
                [
                    'attribute' => 'provider_amount',
                    'value' => ! empty($model->provider_amount) ? User::DEFAULT_CURRENCY . ' ' . $model->provider_amount : SlotBooking::STATE_PENDING
                ],
                [
                    'attribute' => 'admin_revenue',
                    'value' => ! empty($model->admin_revenue) ? User::DEFAULT_CURRENCY . ' ' . $model->admin_revenue : SlotBooking::STATE_PENDING
                ],
                [
                    'attribute' => 'dependant_id',
                    'format' => 'raw',
                    'visible' => ($model->type_id == SlotBooking::TYPE_DEPENDANT),
                    'value' => $model->getRelatedDataLink('dependant_id')
                ],
            /*'description:html',*/
                [
                    'attribute' => 'user_reschedule',
                    'format' => 'raw',
                    'value' => $model->getUserRescheduleType()
                ],
                // 'old_start_time:datetime',
                // 'old_end_time:datetime',
                [
                    'attribute' => 'state_id',
                    'format' => 'raw',
                    'value' => $model->getStateBadge()
                ],

                [
                    'attribute' => 'transaction_id',
                    'format' => 'raw',
                    'visible' => ($model->payment_status == SlotBooking::PAYMENT_SUCCESS)
                ],

                [
                    'attribute' => 'payment_status',
                    'format' => 'raw',
                    'value' => $model->getPaymentStateBadge()
                ],

                [
                    'attribute' => 'cancel_reason',
                    'format' => 'raw',
                    'visible' => ($model->state_id == SlotBooking::STATE_CANCELED)
                ],

                [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],

                [
                    'attribute' => 'provider_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('provider_id')
                ],
                'zipcode',
                'address',
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ],
                'created_on:datetime'
            ]
        ])?>
         <?php  //echo $model->description;?>
         <?php

        // echo UserAction::widget([
        // 'model' => $model,
        // 'attribute' => 'state_id',
        // 'states' => $model->getStateOptions()
        // ]);
        ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="slot-booking-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Service Details', 'skillDetail', 'SubCategory', $model /* ,null,true */);
            $this->context->addPanel('Provider Details', 'providerDetail', 'User', $model /* ,null,true */);
            $this->context->addPanel('User Details', 'createdBy', 'User', $model /* ,null,true */);
            if ($model->type_id == SlotBooking::TYPE_DEPENDANT) {
                $this->context->addPanel('Dependant Detail', 'dependantDetail', 'User', $model /* ,null,true */);
            }
            if ($model->state_id == SlotBooking::STATE_COMPLETED) {
                $this->context->addPanel('Booking Report', 'reportDetail', 'Report', $model /* ,null,true */);
            }
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
</div>
