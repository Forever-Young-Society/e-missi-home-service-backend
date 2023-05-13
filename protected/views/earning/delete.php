<?php
use app\components\TActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Earning */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Earnings'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="earning-view card-body">
			<h4 class="text-danger">Are you sure you want to delete this item?
				All related data is deleted</h4>
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'earning-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
            'attributes' => [
                'id',
                'booking_id',
                'provider_id',
                'provider_amount',
                'booking_amount',
                'admin_amount',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
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
            'id' => 'earning-form',
            'options' => [
                'class' => 'row'
            ]
        ]);
        echo $form->errorSummary($model);
        ?>         <div class="col-md-12 text-right">
            <?= Html::submitButton('Confirm', ['id'=> 'earning-form-submit','class' =>'btn btn-success']) ?>
         </div>
         <?php TActiveForm::end(); ?>
      </div>
	</div>
</div>
