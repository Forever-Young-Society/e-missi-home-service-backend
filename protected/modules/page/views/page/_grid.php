<?php
use app\components\grid\TGridView;
use app\models\User;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\modules\page\models\Page;
use app\components\MassAction;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Page $searchModel
 */
/* @var $model Page */

?>
<div class="table table-responsive">

<?php

Pjax::begin([
    'id' => 'page-pjax-grid'
]);
echo TGridView::widget([
    'id' => 'page-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'tableOptions' => [
        'class' => 'table table-bordered'
    ],
    'columns' => [
        'id',
        'title',
            /* 'description:html',*/
            [
            'attribute' => 'state_id',
            'format' => 'raw',
            'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
            'value' => function ($data) {
                return $data->getStateBadge();
            }
        ],
        [
            'attribute' => 'type_id',
            'filter' => isset($searchModel) ? $searchModel->getTypeOptions() : null,
            'value' => function ($data) {
                return $data->getType();
            }
        ],
        [
            'attribute' => 'application_type',
            'filter' => isset($searchModel) ? $searchModel->getApplicationTypeOptions() : null,
            'value' => function ($data) {
                return $data->getApplicationType();
            }
        ],
        'created_on:datetime',
            /* 'updated_on:datetime',*/
            [
            'attribute' => 'created_by_id',
            'format' => 'raw',
            'value' => function ($data) {
                return $data->getRelatedDataLink('created_by_id');
            }
        ],

        [
            'class' => 'app\components\TActionColumn',
            'header' => "<a>" . Yii::t("app", 'Actions') . "</a>",
            'template' => '{view} {update} {delete} {gotopage}',
            'buttons' => [
                'gotopage' => function ($url, $model, $key) {
                    $url = $model->getGoTopage();
                    if (! empty($url)) {
                        return Html::a('Goto Page', $url, [
                            'class' => 'btn btn-success btn-green'
                        ]);
                    }
                }
            ]
        ]
    ]
]);
?>
<?php

Pjax::end();
?>
</div>