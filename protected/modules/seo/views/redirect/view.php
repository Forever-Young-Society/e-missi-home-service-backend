<?php
use app\modules\comment\widgets\CommentsWidget;
use app\components\useraction\UserAction;
/* @var $this yii\web\View */
/* @var $model app\modules\seo\models\Redirect */

/* $this->title = $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Redirects'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">
		<div class="redirect-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php
    
    echo \app\components\TDetailView::widget([
        'id' => 'redirect-detail-view',
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('id')
            ],
            'old_url:url',
            'new_url:url',
            'created_on:datetime',
            'updated_on:datetime',
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

<?php echo CommentsWidget::widget(['model'=>$model]); ?>
			</div>


