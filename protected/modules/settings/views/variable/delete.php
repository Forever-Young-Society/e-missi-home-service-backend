<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
use app\components\TActiveForm;
/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\Variable */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Settings'),
    'url' => [
        '/settings'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Variables'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="variable-view card-body">
			<h4 class="text-danger">Are you sure you want to delete this item?
				All related data is deleted</h4>
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'setting-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
            'attributes' => [
                'id',
                'key',
                'value:html',
                'module',
                [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
                'created_by_id'
            ]
        ])?>
         <?php
        $form = TActiveForm::begin([
            'id' => 'setting-form',
            'options' => [
                'class' => 'row'
            ]
        ]);
        ?>
         <?php  ?>
         <?php

        $form = TActiveForm::begin([
            'id' => 'settings-form',
            'options' => [
                'class' => 'row'
            ]
        ]);
        ?>
         echo $form->errorSummary($model);	
         ?> <div class="col-md-12 text-right">
            <?= Html::submitButton('Confirm', ['id'=> 'variable-form-submit','class' =>'btn btn-success']) ?>
         </div>
         <?php TActiveForm::end(); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="variable-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Activities', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
      <?php echo CommentsWidget::widget(['model'=>$model]); ?>
</div>