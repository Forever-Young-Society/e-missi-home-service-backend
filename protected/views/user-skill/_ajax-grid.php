<?php
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\UserSkill $searchModel
 */

?>

<?php Pjax::begin(['id'=>'user-skill-pjax-ajax-grid','enablePushState'=>false,'enableReplaceState'=>false]); ?>
    <?php

    echo TGridView::widget([
        'id' => 'user-skill-ajax-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            // 'title',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('category_id');
                }
            ],
            [
                'attribute' => 'skill_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('skill_id');
                }
            ],
            [
                'attribute' => 'parent_skill_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('parent_skill_id');
                }
            ],
            
            /* '',*/
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

