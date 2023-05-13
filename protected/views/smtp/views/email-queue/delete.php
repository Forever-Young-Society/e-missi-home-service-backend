<?php
use app\components\TActiveForm;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
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
		<div class="email-queue-view card-body">
			<h4 class="text-danger">Are you sure you want to delete this item?
				All related data is deleted</h4>
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'email-queue-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
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
                'model_id',
                'model_type',
                'smtp_account_id',
                'message_id',
                're_message_id'
            ]
        ])?>
         <?php  echo $model->content;?>
         <?php

        $form = TActiveForm::begin([
            'id' => 'email-queue-form',
            'options' => [
                'class' => 'row'
            ]
        ]);
        ?>
         echo $form->errorSummary($model);	
         ?>         <div class="col-md-12 text-right">
            <?= Html::submitButton('Confirm', ['id'=> 'email-queue-form-submit','class' =>'btn btn-success']) ?>
         </div>
         <?php TActiveForm::end(); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="email-queue-panel">
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