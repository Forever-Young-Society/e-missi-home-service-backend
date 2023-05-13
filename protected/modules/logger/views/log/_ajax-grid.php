<?php
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\logger\models\search\Log $searchModel
 */

?>

<?php
if (! empty($menu))
    echo Html::a($menu['label'], $menu['url'], $menu['htmlOptions']);
?>



<?php Pjax::begin(['id'=>'log-pjax-ajax-grid','enablePushState'=>false,'enableReplaceState'=>false]); ?>
    <?php

echo TGridView::widget([
        'id' => 'log-ajax-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'error',
            /* 'description:html',*/
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            'link',
            [
                'attribute' => 'type_id',
                'filter' => isset($searchModel) ? $searchModel->getTypeOptions() : null,
                'value' => function ($data) {
                    return $data->getType();
                }
            ],
            /* 'referer_link',*/
            'user_ip',
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('user_id');
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

