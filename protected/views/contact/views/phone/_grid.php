<?php
use app\components\World;
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

?>

<?php

Pjax::begin([
    'id' => 'contact-pjax-grid'
]);
?>
    <?php

    echo TGridView::widget([
        'id' => 'contact-grid-view',
        'enableRowClick' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'title',
            'contact_no',
            // ['attribute' => 'type_chat','filter'=>isset($searchModel)?$searchModel->getTypeChatOptions():null,
            // 'value' => function ($data) { return $data->getTypeChat(); },],
            // 'skype_chat',
            // 'gtalk_chat',
            'whatsapp_enable:boolean',
            'telegram_enable:boolean',
            'toll_free_enable:boolean',
            // [
            // 'attribute' => 'type_id',
            // 'filter' => isset($searchModel) ? $searchModel->getTypeOptions() : null,
            // 'value' => function ($data) {
            // return $data->getType();
            // }
            // ],
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
<?php

Pjax::end();
?>

