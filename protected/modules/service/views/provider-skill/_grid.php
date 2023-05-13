<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\service\models\search\ProviderSkill $searchModel
 */

?>

<?php Pjax::begin(['id'=>'provider-skill-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'provider-skill-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '<a>S.No.<a/>'
            ],

            // 'id',
            'title',
            /* ['attribute' => 'image_file','filter'=>$searchModel->getFileOptions(),
			'value' => function ($data) { return $data->getFileOptions($data->image_file);  },],*/
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getCategoryOptions() : null,
                'value' => function ($data) {
                    return $data->getRelatedDataLink('category_id');
                }
            ],
            /* 'parent_id',*/
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