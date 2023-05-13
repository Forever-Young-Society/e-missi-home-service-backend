<?php
use app\components\grid\TGridView;
use app\models\User;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\LoginHistory $searchModel
 */

?>
<?php Pjax::begin(['id'=>'login-history-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'login-history-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            /* [
			'attribute' => 'user_id',
			'format'=>'raw',
			'value' => function ($data) { return $data->getRelatedDataLink('user_id');  },],*/
            'user_ip',
            'user_agent',
            /* 'failer_reason',*/
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
            'code',
            'created_on:datetime',

            [
                'class' => 'app\components\TActionColumn',
                'template' => '{view} {delete}',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>

