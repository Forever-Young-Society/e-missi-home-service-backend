<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
use app\models\User;
use app\modules\availability\models\SlotBooking;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\availability\models\search\SlotBooking $searchModel
 */

?>
<?php
$earning_sum = $dataProvider->query->sum('admin_revenue');
$sum = (! empty($earning_sum)) ? round($earning_sum, 2) : 0;
$provider_sum = $dataProvider->query->sum('provider_amount');
$provider_sum = (! empty($provider_sum)) ? round($provider_sum, 2) : 0;
$exportColumns = [
    [
        'class' => 'yii\grid\SerialColumn',
        'header' => '<a>S.No.<a/>'
    ],

    'id',
    [
        'attribute' => 'service_id',
        'format' => 'raw',
        'value' => function ($data) {
            return $data->getRelatedDataLink('service_id');
        }
    ],
    [
        'attribute' => 'admin_revenue',
        'format' => 'raw',
        'value' => function ($data) {
            return ! empty($data->admin_revenue) ? User::DEFAULT_CURRENCY . ' ' . $data->admin_revenue : SlotBooking::STATE_PENDING;
        },
        'footer' => User::DEFAULT_CURRENCY . ' ' . $sum
    ],
    'start_time:datetime',
    'end_time:datetime',
    [
        'attribute' => 'state_id',
        'format' => 'raw',
        'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
        'value' => function ($data) {
            return $data->getStateBadge();
        }
    ],
    [
        'attribute' => 'payment_status',
        'format' => 'raw',
        'filter' => isset($searchModel) ? $searchModel->getPaymentStateOptions() : null,
        'value' => function ($data) {
            return $data->getPaymentStateBadge();
        }
    ],
    [
        'attribute' => 'type_id',
        'filter' => isset($searchModel) ? $searchModel->getTypeOptions() : null,
        'value' => function ($data) {
            return $data->getType();
        }
    ],

    [
        'attribute' => 'provider_id',
        'format' => 'raw',
        'value' => function ($data) {
            return $data->getRelatedDataLink('provider_id');
        }
    ],
    [
        'attribute' => 'created_by_id',
        'format' => 'raw',
        'value' => function ($data) {
            return $data->getRelatedDataLink('created_by_id');
        }
    ],
    'created_on:datetime'
];
?>
<?php Pjax::begin(['id'=>'slot-booking-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'slot-booking-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'exportable' => true,
        'exportColumns' => $exportColumns,
        'showFooter' => true,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            [
                'attribute' => 'service_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('service_id');
                }
            ],
            [
                'attribute' => 'admin_revenue',
                'format' => 'raw',
                'value' => function ($data) {
                    return ! empty($data->admin_revenue) ? User::DEFAULT_CURRENCY . ' ' . $data->admin_revenue : SlotBooking::STATE_PENDING;
                },
                'footer' => User::DEFAULT_CURRENCY . ' ' . $sum
            ],

            'start_time:datetime',
            'end_time:datetime',
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            [
                'attribute' => 'payment_status',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getPaymentStateOptions() : null,
                'value' => function ($data) {
                    return $data->getPaymentStateBadge();
                }
            ],
            [
                'attribute' => 'type_id',
                'filter' => isset($searchModel) ? $searchModel->getTypeOptions() : null,
                'value' => function ($data) {
                    return $data->getType();
                }
            ],

            [
                'attribute' => 'service_type',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getServiceTypeOptions() : null,
                'value' => function ($data) {
                    return $data->getRelatedDataLink('service_type');
                }
            ],
            'created_on:datetime',
            [
                'attribute' => 'provider_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('provider_id');
                }
            ],
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                }
            ],

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>',
                'template' => '{view}'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>