<?php
use app\components\useraction\UserAction;

/* @var $this yii\web\View */
/* @var $model use app\modules\comment\models\Comment */

/* $this->title = $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Comments'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">
		<div class="comment-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'comment-detail-view',
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'model_id',
                'format' => 'raw',
                'value' => $model->getModel() ? $model->getModel()->linkify() : ''
            ],
            'model_type',
            'comment:html',
            'created_on:datetime',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('created_by_id')
            ]
        ]
    ])?>
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
