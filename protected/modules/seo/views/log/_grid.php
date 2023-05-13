<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\seo\models\search\Log $searchModel
 */

?>

<?php Pjax::begin(['id'=>'log-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'log-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'enableRowClick' => true,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'current_url:url',
            'referer_link',
            'view_count',
            'message',
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
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
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('user_id');
                }
            ],
            'user_ip',
            'user_agent',
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