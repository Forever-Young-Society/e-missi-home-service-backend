<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\scheduler\models\search\Cronjob $searchModel
 */

?>

<?php Pjax::begin(['id'=>'cronjob-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'cronjob-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'title',
            'when',
            /* 'command:html',*/
            [
                'attribute' => 'type_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('type_id');
                }
            ],
            [
                'attribute' => 'Logs',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->getLogs()->count();
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
            'created_on:datetime',
            /* [
				'attribute' => 'created_by_id',
				'format'=>'raw',
				'value' => function ($data) { return $data->getRelatedDataLink('created_by_id');  },
				],*/

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>