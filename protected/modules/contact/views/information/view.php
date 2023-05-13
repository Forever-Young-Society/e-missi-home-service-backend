<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\Information */

/* $this->title = $model->label() .' : ' . $model->id; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Contact'),
    'url' => [
        '/contact'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Information'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="information-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php
        echo \app\components\TDetailView::widget([
            'id' => 'information-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
                'full_name',
                'email:email',
                [
                    'attribute' => 'budget_type_id',
                    'value' => $model->getBudgetType()
                ],
                // 'address',
                'mobile',

                'referrer_url',
                'ip_address',
                'website',
                'country',
                'user_agent',
                // [
                // 'attribute' => 'state_id',
                // 'format' => 'raw',
                // 'value' => $model->getStateBadge()
                // ],
                'subject',
                'created_on:datetime'
            ]
        ])?>
         <?php  echo $model->description;?>
         <?php
        echo UserAction::widget([
            'model' => $model,
            'attribute' => 'state_id',
            'states' => $model->getStateOptions(),
            'visible' => User::isManager()
        ]);
        ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="information-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Previous', 'previous', 'Information', $model /* ,null,true */);
            $this->context->addPanel('Activities', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
   
         <?php echo CommentsWidget::widget(['model'=>$model]); ?>
     
</div>