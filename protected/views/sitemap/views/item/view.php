<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\sitemap\models\Item */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Sitemaps'),
    'url' => [
        '/sitemap'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Items'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="item-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'item-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'location',
                'module',
                'priority_id',
                'change_frequency_id',
                'model_type',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
                'created_on:datetime',
                'updated_on:datetime',
                'created_by_id'
            ]
        ])?>
        <?php if(User::isManager()){?>
         <?php

            echo UserAction::widget([
                'model' => $model,
                'attribute' => 'state_id',
                'states' => $model->getStateOptions()
            ]);
            ?>
        <?php }?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="item-panel">
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