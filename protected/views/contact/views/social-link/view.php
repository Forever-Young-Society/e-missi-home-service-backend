<?php
use app\components\useraction\UserAction;
/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\SocialLink */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Contacts'),
    'url' => [
        '/contact'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Social Links'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="social-link-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'social-link-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                'ext_url:url',
                [
                    'attribute' => 'state_id',
                    'format' => 'raw',
                    'value' => $model->getStateBadge()
                ],
                'created_on:datetime',
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->createdBy
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
        	<div class="social-link-panel">
<?php

$this->context->startPanel();
$this->context->addPanel('Activities', 'feeds', 'Feed', $model);
$this->context->endPanel();

?>
</div>
		</div>
	</div>
</div>