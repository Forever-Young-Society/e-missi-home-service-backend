<?php
use yii\widgets\Pjax;
use app\components\grid\TGridView;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\sms\models\search\History $searchModel
 */

?>
<?php Pjax::begin(['id'=>'history-pjax-ajax-grid','enablePushState'=>false,'enableReplaceState'=>false]); ?>
    <?php
    
    echo TGridView::widget([
        'id' => 'history-ajax-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            'id',
            'from',
            'to',
            /* 'model_id',*/
            /* 'model_type',*/
            /* 'text',*/
            'gateway_id',
            /* 'sms_detail',*/
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
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>

