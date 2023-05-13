<?php
use app\modules\comment\widgets\CommentsWidget;
use app\components\useraction\UserAction;
/* @var $this yii\web\View */
/* @var $model app\modules\seo\models\Analytics */

/* $this->title = $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Analytics'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">
		<div class="analytics-view">
                    <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
         </div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'analytics-detail-view',
        'model' => $model,
        'attributes' => [
            'id',
            'account',
            'domain_name',
            'additional_information',
           /*  [
                'attribute' => 'state_id',
                'format' => 'raw',
                'value' => $model->getStateBadge()
            ], */
            [
                'attribute' => 'role_id',
                'value' => $model->getRole()
            ],
            [
                'attribute' => 'type_id',
                'value' => $model->getType()
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


<?php  ?>


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
