<?php
use app\components\grid\TGridView;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\notification\models\search\Notification $searchModel
 */

?>
<?php Pjax::begin(['id'=>'notification-pjax-grid','enablePushState'=>false,'enableReplaceState'=>false]); ?>
    <?php

    echo TGridView::widget([
        'id' => 'notification-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
            'id',
            'title',
//             [
//                 'attribute' => 'model_id',
//                 'format' => 'raw',
//                 'value' => function ($data) {
//                     return $data->getModel() ? $data->getModel()->linkify() : '';
//                 }
//             ],
            [
                'attribute' => 'is_read',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getIsReadOptions() : null,
                'value' => function ($data) {
                    return $data->getIsReadBadge();
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
                'template' => '{view}{delete}'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>

