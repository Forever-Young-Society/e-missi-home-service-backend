<?php
use app\components\useraction\UserAction;
/* @var $this yii\web\View */
/* @var $model app\modules\sms\models\History */

/* $this->title = $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Histories'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">

		<div class="history-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php
    
    echo \app\components\TDetailView::widget([
        'id' => 'history-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'to',
            'text',
            [
                'attribute' => 'gateway_id',
                'format' => 'raw',
                'value' => $model->getGateway()
            ],
            'created_on:datetime',
            'updated_on:datetime',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('created_by_id')
            ]
        ]
    ])?>


<?php echo $model->sms_detail?>


		<?php

echo UserAction::widget([
    'model' => $model,
    'attribute' => 'state_id',
    'states' => $model->getStateOptions()
]);
?>
		</div>
	</div>
</div>