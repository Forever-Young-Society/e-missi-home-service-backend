<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\modules\smtp\widgets\UnsubscribeSelect;
/* @var $this yii\web\View */
/* @var $model app\modules\smtp\models\EmailQueue */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'SMTP'),
    'url' => [
        '/smtp'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Email Queues'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="email-queue-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-8">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'email-queue-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'subject',
                'from',
                'to',
                'cc',
                'bcc',
            /*'content:html',*/
            /*[
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],*/
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            'attempts',
                'sent_on:datetime',
                'created_on:datetime',
                [
                    'attribute' => 'model_id',
                    'value' => $model->getModel()
                ],
                'model_type',
                'smtp_account_id',
                'message_id',
                're_message_id'
            ]
        ])?>
        </div>
				<div class="col-md-4">
        <?php echo UnsubscribeSelect::widget(['model'=>$model->getUnsubscribeEmail()])?>
        </div>
			</div>
		</div>
		<div class="card-body">
			<iframe frameBorder="0" src="<?php echo $model->getUrl('show')?>"
				width="80%" height="500px"></iframe>
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
			<div class="email-queue-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Files', 'files', 'File', $model /* ,null,true */);
            $this->context->addPanel('Activities', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
      <?php echo CommentsWidget::widget(['model'=>$model]); ?>
</div>