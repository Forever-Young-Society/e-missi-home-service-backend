<?php
use app\components\grid\TGridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;

echo TGridView::widget([
    'id' => 'install-grid',
    'enableRowClick' => false,
    'dataProvider' => $dataProvider,
    'columns' => array(
        'name',
        // 'size:size',
        // 'create_time:datetime',
        array(
            'header' => 'Delete DB',
            'class' => ActionColumn::class,
            'template' => '{create}{list}',
            'buttons' => [
                'create' => function ($url, $model) {
                    return Html::a('<span class="fa fa-plus"></span>', $url, [
                        'title' => Yii::t('app', 'backup'),
                        'class' => 'btn btn-warning'
                    ]);
                },

                'list' => function ($url, $model) {
                    return Html::a('<span class="fa fa-list"></span>', $url, [
                        'title' => Yii::t('app', 'List'),
                        'class' => 'btn btn-warning'
                    ]);
                }
            ],
            'urlCreator' => function ($action, $model, $key, $index) {

                $url = Url::toRoute([
                    'module/' . $action,
                    'm' => $model['name']
                ]);
                return $url;
            }
        )
    )
]);
?>