<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\rating\models\Rating */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Ratings'),
    'url' => [
        '/rating'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Ratings'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class=" card ">

		<div
			class="rating-view card-body">
			<?=\app\components\PageHeader::widget(['model' => $model]);?>



		</div>
	</div>

	<div class=" card ">
		<div class=" card-body ">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'rating-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'model_id',
            'model_type',
            'rating',
            'comment:html',
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'value' => $model->getStateBadge()
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


<?php

?>


		<?php

echo UserAction::widget([
    'model' => $model,
    'attribute' => 'state_id',
    'states' => $model->getStateOptions()
]);
?>

		</div>
</div>
 


	<div class=" card ">
				<div class=" card-body ">
					<div
						class="rating-panel">

<?php
$this->context->startPanel();
$this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */
);

$this->context->endPanel();
?>
				</div>
				</div>
			</div>


</div>
