<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\scheduler\models\Log */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Scheduler'),
    'url' => [
        '/scheduler'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Logs'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="log-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'log-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            /* [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ], */
                [
                    'attribute' => 'cronjob_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('cronjob_id')
                ],
                'scheduled_on:datetime',
                'executed_on:datetime',
                'created_on:datetime',
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
            ]
        ])?>
         <?php echo !empty($model->result)?nl2br($model->result):''; ?>
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
			<div class="log-panel">
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