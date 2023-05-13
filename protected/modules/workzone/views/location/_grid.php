<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\workzone\models\search\Location $searchModel
 */

?>

<?php Pjax::begin(['id'=>'location-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'location-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '<a>S.No.<a/>'
            ],

            // 'id',
            [
                'attribute' => 'primary_location',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('primary_location');
                }
            ],
            [
                'attribute' => 'secondary_location',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('secondary_location');
                }
            ],
            [
                'attribute' => 'second_secondary_location',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('second_secondary_location');
                }
            ],
            /* ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],*/
             [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            'created_on:datetime',

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>