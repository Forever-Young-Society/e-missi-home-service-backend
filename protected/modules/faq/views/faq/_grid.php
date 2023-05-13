<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
use app\modules\faq\models\Faq;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\faq\models\search\Faq $searchModel
 */
?>
<?php

Pjax::begin([
    'id' => 'faq-pjax-grid'
]);
?>
    <?php

    echo TGridView::widget([
        'id' => 'faq-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '<a>S.No.<a/>'
            ],
            // 'id',
            [
                'attribute' => 'question',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('question');
                }
            ],
            [
                'attribute' => 'answer',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('answer');
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
                'attribute' => 'created_on',
                'filter' => \yii\jui\DatePicker::widget([
                    'inline' => false,
                    'clientOptions' => [
                        'autoclose' => true
                    ],
                    'model' => $searchModel,
                    'attribute' => 'created_on',

                    'options' => [
                        'id' => 'created_on',
                        'class' => 'form-control',
                        'autoComplete' => 'off'
                    ]
                ]),
                'value' => function ($data) {
                    return date('Y-m-d H:i ', strtotime($data->created_on));
                }
            ],

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>',
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return $model->state_id !== Faq::STATE_DELETED;
                    }
                ]
            ]
        ]
    ]);
    ?>
<?php

Pjax::end();
?>