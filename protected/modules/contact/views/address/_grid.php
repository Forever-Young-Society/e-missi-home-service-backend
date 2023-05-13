<?php
use app\components\World;
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\contact\models\search\Address $searchModel
 */

?>

<?php Pjax::begin(['id'=>'address-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'address-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'title',
            'address',
            'email:email',
            /* 'tel',*/
            /* 'mobile',*/
           // 'latitude',
           // 'longitude',
            [
                'attribute' => 'country',
                'value' => function ($data) {
                    return ($data->country) ? World::findCountryByCode($data->country) : '';
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
            'image_file:boolean',
          /*   [
                'attribute' => 'type_id',
                'filter' => isset($searchModel) ? $searchModel->getTypeOptions() : null,
                'value' => function ($data) {
                    return $data->getType();
                }
            ],
           'created_on:datetime',*/
            /* 'updated_on:datetime',*/
            /*[
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                }
                ],*/
            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>',
                'buttons' => [
                    'select' => function ($url, $model, $key) {
                        return Html::a('<span class="fa fa-check"></span>', [
                            '/signature/contact/select',
                            'id' => $model->id
                        ], [
                            'title' => \Yii::t('yii', 'Signature'),
                            'class' => 'btn btn-success'
                        ]);
                    }
                ],
             //   'template' => $button
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>
