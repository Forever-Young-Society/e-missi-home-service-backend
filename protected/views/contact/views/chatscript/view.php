<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\Chatscript */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Contacts'),
    'url' => [
        '/contact'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Chatscripts'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="chatscript-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'chatscript-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
            /*'title',*/
            'domain',
                'script_code',
                'contact_link:boolean',
                'show_bubble:boolean',
                'popup_delay',
                'chat_server',
                'role',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            /*[
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],*/
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
			<div class="chatscript-panel">
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