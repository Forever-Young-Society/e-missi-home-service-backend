<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\smtp\models\search\EmailQueue $searchModel
 */

?>

<?php Pjax::begin(['id'=>'email-queue-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'email-queue-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'subject',
            'from',
            'to',
            /* 'cc',*/
            /* 'bcc',*/
            /* 'content:html',*/
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
            /* 'attempts',*/
             'sent_on:datetime',
            'created_on:datetime',
            /* 'model_id',*/
            /* 'model_type',*/
            /* 'smtp_account_id',*/
            // 'message_id',
           // 're_message_id',

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>',
                'template' => '{view} {delete}'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>