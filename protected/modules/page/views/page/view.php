<?php
use app\components\useraction\UserAction;
use app\modules\page\models\Page;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model Page */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Pages'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card ">

		<div class="page-view card-body">
			<?php

echo \app\components\PageHeader::widget([
    'model' => $model
]);
?>



		</div>
	</div>

	<div class="card ">
		<div class="card-body">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'page-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'title',
            /*'description:html',*/
            [
                'attribute' => 'type_id',
                'value' => $model->getType()
            ],
            [
                'attribute' => 'application_type',
                'value' => $model->getApplicationType()
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
				<?=HtmlPurifier::process($model->description);?>
 			<div></div>

		</div>
	</div>
						<?php

    echo UserAction::widget([
        'model' => $model,
        'attribute' => 'state_id',
        'states' => $model->getStateOptions()
    ]);
    ?>
</div>
