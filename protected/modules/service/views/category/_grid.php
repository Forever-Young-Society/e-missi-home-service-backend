<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\service\models\search\Category $searchModel
 */

?>

<?php Pjax::begin(['id'=>'category-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'category-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '<a>S.No.<a/>'
            ],

            //'id',
            'title',
            [
                'attribute' => 'image_file',
                'value' => function ($model) {
                    return $model->getImageUrl(90);
                },
                'format' => [
                    'image',
                    [
                        'class' => 'img-responsive',
                        'width' => '90',
                        'height' => '50',
                        'alt' => ' '
                    ]
                ]
            ],

            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            /* ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],*/
            [
                'attribute' => 'created_on',
                'filter' => \yii\jui\DatePicker::widget([
                    'language' => 'en-US',
                    'inline' => false,
                    'clientOptions' => [
                        'autoclose' => true
                    ],
                    'model' => $searchModel,
                    'attribute' => 'created_on',

                    'options' => [
                        'id' => 'created_on',
                        'class' => 'form-control',
                        'autoComplete' => 'off'
                    ]
                ]),
                'value' => function ($data) {
                    return $data->created_on;
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