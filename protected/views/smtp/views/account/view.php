<?php
use app\components\useraction\UserAction;
use app\models\User;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\smtp\models\Account */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'SMTP'),
    'url' => [
        '/smtp'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Accounts'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="account-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'account-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                'email:email',
                /*'password',*/
                [
                    'attribute' => 'password',
                    'visible' => User::isAdmin(),
                    'value' => function ($data) {
                        return $data->getDecryptedPassword();
                    }
                ],
                'server',
                'port',
                [
                    'attribute' => 'encryption_type',
                    'value' => $model->getEncryption()
                ],
                'limit_per_email:email',
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
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
            ]
        ])?>
         <?php  ?>

      </div>
	</div>
         <?php

        echo UserAction::widget([
            'model' => $model,
            'attribute' => 'state_id',
            'states' => $model->getStateOptions()
        ]);
        ?>
	<div class="card">
		<div class="card-body">
			<div class="account-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Emails', 'emails', 'EmailQueue', $model, null, false);
            $this->context->addPanel('Activities', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
      <?php echo CommentsWidget::widget(['model'=>$model]); ?>
</div>