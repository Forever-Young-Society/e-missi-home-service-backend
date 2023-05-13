<?php
use app\components\grid\TGridView;
use app\models\User;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\File $searchModel
 */

?>
<?php Pjax::begin(["enablePushState"=>false,"enableReplaceState"=>false,'id' => 'file-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'file-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
            [
                'name' => 'check',
                'class' => 'yii\grid\CheckboxColumn',
                'visible' => User::isAdmin()
            ],
            'id',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($data) {
                    return (string) $data;
                }
            ],
            'size:shortSize',
            'key',
            'model_type',
            'model_id',
            [
                'attribute' => 'type_id',
                'filter' => isset($searchModel) ? $searchModel->getTypeOptions() : null,
                'value' => function ($data) {
                    return $data->getType();
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
                'template' => '{view}{delete}',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>

