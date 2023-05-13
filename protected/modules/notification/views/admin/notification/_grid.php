<?php
use app\components\grid\TGridView;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\notification\models\search\Notification $searchModel
 */

?>
<?php Pjax::begin(['id'=>'notification-pjax-grid']); ?>
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
            //'description:html',
            // 'model_id',
            // 'model_type',
            [
                'attribute' => 'is_read',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getIsReadOptions() : null,
                'value' => function ($data) {
                    return $data->getIsReadBadge();
                }
            ],
            'created_on:datetime',
            [
                'attribute' => 'to_user_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('to_user_id');
                }
            ],
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


