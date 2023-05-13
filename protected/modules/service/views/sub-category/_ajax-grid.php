<?php
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\service\models\search\SubCategory $searchModel
 */

?>

<?php Pjax::begin(['id'=>'sub-category-pjax-ajax-grid','enablePushState'=>false,'enableReplaceState'=>false]); ?>
    <?php

    echo TGridView::widget([
        'id' => 'sub-category-ajax-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '<a>S.No.<a/>'
            ],

           // 'id',
            'title',
            [
                'attribute' => 'price',
                'format' => 'raw',
                'value' => function ($data) {
                    return User::DEFAULT_CURRENCY . ' ' . $data->price;
                }
            ],
            [
                'attribute' => 'provider_price',
                'format' => 'raw',
                'value' => function ($data) {
                    return User::DEFAULT_CURRENCY . ' ' . $data->provider_price;
                }
            ],
            /* ['attribute' => 'image_file','filter'=>$searchModel->getFileOptions(),
			'value' => function ($data) { return $data->getFileOptions($data->image_file);  },],*/
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('category_id');
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
            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>

