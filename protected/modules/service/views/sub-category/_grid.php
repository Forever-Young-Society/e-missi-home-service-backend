<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
use app\models\User;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\service\models\search\SubCategory $searchModel
 */

?>

<?php Pjax::begin(['id'=>'sub-category-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'sub-category-grid-view',
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
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getCategoryOptions() : null,
                'value' => function ($data) {
                    return $data->getRelatedDataLink('category_id');
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