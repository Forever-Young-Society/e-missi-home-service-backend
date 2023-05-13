<?php
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\UserWorkzone $searchModel
 */

?>

<?php Pjax::begin(['id'=>'user-workzone-pjax-ajax-grid','enablePushState'=>false,'enableReplaceState'=>false]); ?>
    <?php

    echo TGridView::widget([
        'id' => 'user-workzone-ajax-grid-view',
        'dataProvider' => $dataProvider,
        'enableRowClick'=>false,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'title',
            /* 'workzone_id',*/
            /* ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],*/
            /* [
			'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
			'value' => function ($data) { return $data->getStateBadge();  },],*/
            
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                }
            ],
            'created_on:datetime',

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>',
                'visible' => false
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>

