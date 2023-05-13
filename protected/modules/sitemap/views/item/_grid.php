<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\sitemap\models\search\Item $searchModel
 */

?>
<?php Pjax::begin(['id'=>'item-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'item-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'location',
            'module',
            [
                'attribute' => 'priority_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getPriorityOptions() : null,
                'value' => function ($data) {
                    return $data->getPriority();
                }
            ],
            [
                'attribute' => 'change_frequency_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getChangeFrequencyOptions() : null,
                'value' => function ($data) {
                    return $data->getChangeFrequency();
                }
            ],
            /* 'model_type',*/
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
            'created_on:datetime',
            /* 'updated_on:datetime',*/
            /* [
			'attribute' => 'created_by_id',
			'format'=>'raw',
			'value' => function ($data) { return $data->getRelatedDataLink('created_by_id');  },],*/

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>