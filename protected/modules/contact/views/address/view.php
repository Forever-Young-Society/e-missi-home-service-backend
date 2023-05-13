<?php
use app\components\World;
use app\components\useraction\UserAction;

/* @var $this yii\web\View */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contacts'), 'url' => ['/contact']];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Addresses'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">
		<div class="address-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
        </div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'address-detail-view',
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'address',
            'email:email',
            'tel',
            'mobile',
            'latitude',
            'longitude',
            'image_file:boolean',
            [
                'attribute' => 'country',
                'value' => ($model->country) ? World::findCountryByCode($model->country) : ''
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
