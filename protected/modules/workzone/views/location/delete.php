<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
use app\components\TActiveForm;
/* @var $this yii\web\View */
/* @var $model app\modules\workzone\models\Location */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Workzones'),
    'url' => [
        '/workzone'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Locations'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="location-view card-body">
			<h4 class="text-danger">Are you sure you want to delete this item?
				All related data is deleted</h4>
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

echo \app\components\TDetailView::widget([
            'id' => 'location-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
            'attributes' => [
                'id',
            /*'title',*/
            'secondary_location',
                [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            'created_on:datetime',
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
            ]
        ])?>
         <?php

$form = TActiveForm::begin([
            'id' => 'location-form',
            'options' => [
                'class' => 'row'
            ]
        ]);
        ?>
                 <div class="col-md-12 text-right">
            <?= Html::submitButton('Confirm', ['id'=> 'location-form-submit','class' =>'btn btn-success']) ?>
         </div>
         <?php TActiveForm::end(); ?>
      </div>
	</div>
	<div class="card"></div>