<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Feed $searchModel
 */

?>

<?php Pjax::begin(['id'=>'feed-pjax-ajax-grid','enablePushState'=>false,'enableReplaceState'=>false]); ?>
    <?php

    echo TGridView::widget([
        'id' => 'feed-ajax-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'content:html',
            // [
            // 'attribute' => 'state_id',
            // 'format' => 'raw',
            // 'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
            // 'value' => function ($data) {
            // return $data->getStateBadge();
            // }
            // ],
            // [
            // 'attribute' => 'type_id',
            // 'filter' => isset($searchModel) ? $searchModel->getTypeOptions() : null,
            // 'value' => function ($data) {
            // return $data->getType();
            // }
            // ],
            // 'model_type',
            // 'model_id',
            'created_on:datetime',
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
                'template' => '{view} {delete}'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>

