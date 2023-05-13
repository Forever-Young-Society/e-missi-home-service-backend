<?php
use app\components\TActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\modules\service\models\SubCategory */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Services'),
    'url' => [
        '/service'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Sub Categories'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="sub-category-view card-body">
			<h4 class="text-danger">Are you sure you want to delete this item?
				All related data is deleted</h4>
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'sub-category-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
            'attributes' => [
                'id',
            /*'title',*/
            'image_file',
                'category_id',
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
            'id' => 'sub-category-form',
            'options' => [
                'class' => 'row'
            ]
        ]);
        ?>
                  <div class="col-md-12 text-right">
            <?= Html::submitButton('Confirm', ['id'=> 'sub-category-form-submit','class' =>'btn btn-success']) ?>
         </div>
         <?php TActiveForm::end(); ?>
      </div>
	</div>
</div>
