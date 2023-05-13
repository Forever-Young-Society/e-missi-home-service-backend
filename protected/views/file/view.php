<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;

/* @var $this yii\web\View */
/* @var $model app\models\File */

/* $this->title = $model->label() .' : ' . $model->name; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Files'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">
        <div class="file-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
		</div>
	</div>

	<div class="card mb-4">
		<div class="card-body">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'file-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'name',
            'size',
            'key',
            'model_type',
            'model_id',
            [
                'attribute' => 'type_id',
                'value' => $model->getType()
            ],
            'created_on:datetime',
            'created_by_id'
        ]
    ])?>


<?php  ?>

 			<div>
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
	

<?php echo CommentsWidget::widget(['model'=>$model]); ?>

</div>