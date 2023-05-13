<?php
use app\components\useraction\UserAction;
/* @var $this yii\web\View */
/* @var $model app\modules\workzone\models\Zone */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Zones'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="zone-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'zone-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                [
                    'attribute' => 'zipcode',
                    'format' => 'raw',
                    'value' => $model->getLocationZipcodes()
                ],
                [
                    'attribute' => 'state_id',
                    'format' => 'raw',
                    'value' => $model->getStateBadge()
                ],
                'created_on:datetime'
            ]
        ])?>
         <?php

        echo UserAction::widget([
            'model' => $model,
            'attribute' => 'state_id',
            'states' => $model->getStateOptions()
        ]);
        ?>
      </div>
	</div>
</div>
