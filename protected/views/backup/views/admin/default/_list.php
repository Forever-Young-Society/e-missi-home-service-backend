<?php
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\helpers\Url;

echo TGridView::widget([
    'id' => 'install-grid',
    'dataProvider' => $dataProvider,
    'columns' => array(
        'name',
        'size:size',
        'create_time:datetime',
        array(
            'header' => 'Delete DB',
            'class' => 'yii\grid\ActionColumn',
            'template' => '{restore}{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('<span class="fa fa-remove"></span>', $url, [
                        'title' => Yii::t('app', 'Delete this backup'),
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'class' => 'btn btn-warning'
                    ]);
                },

                'restore' => function ($url, $model) {
                    return Html::a('<span class="fa fa-save"></span>', $url, [
                        'title' => Yii::t('app', 'Restore this backup'),
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('yii', 'Restore will DELETE your live database and upload this file. Are you sure ?'),
                        'class' => 'btn btn-warning'
                    ]);
                }
            ],
            'urlCreator' => function ($action, $model, $key, $index) {

                $url = Url::toRoute([
                    'default/' . $action,
                    'file' => $model['name']
                ]);
                return $url;
            }
        )
    )
]);
?>