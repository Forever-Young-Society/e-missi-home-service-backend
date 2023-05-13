<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\availability\models\search\ProviderSlot $searchModel
 */

?>

<?php Pjax::begin(['id'=>'provider-slot-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'provider-slot-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            [
                'attribute' => 'start_time',
                'filter' => \yii\jui\DatePicker::widget([
                    'language' => 'en-US',
                    'inline' => false,
                    'clientOptions' => [
                        'autoclose' => true
                    ],
                    'model' => $searchModel,
                    'attribute' => 'start_time',

                    'options' => [
                        'id' => 'created_on',
                        'class' => 'form-control',
                        'autoComplete' => 'off'
                    ]
                ]),
                'value' => function ($data) {
                    return date('Y-m-d h:i:s a', strtotime($data->start_time));
                }
            ],
            [
                'attribute' => 'end_time',
                'format' => 'raw',
                'value' => function ($data) {
                    return date('Y-m-d h:i:s a', strtotime($data->end_time));
                }
            ],
            /* 'availability_slot_id',*/
            
            /* 'created_on:datetime',*/
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                }
            ],
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
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