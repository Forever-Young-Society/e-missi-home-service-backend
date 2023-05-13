<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\scheduler\models\Cronjob */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Scheduler'),
    'url' => [
        '/scheduler'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Cronjobs'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="cronjob-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'cronjob-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
            /*'title',*/
            'when',
                'command:html',
                [
                    'attribute' => 'type_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('type_id')
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
	<div class="card">
		<div class="card-body">
			<div class="cronjob-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Logs', 'logs', 'Log', $model /* ,null,true */);
            $this->context->addPanel('Activities', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
        
  <?php echo CommentsWidget::widget(['model'=>$model]); ?>
  </div>