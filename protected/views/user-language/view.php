<?php
use app\components\useraction\UserAction;
/* @var $this yii\web\View */
/* @var $model app\models\UserLanguage */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'User Languages'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="user-language-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'user-language-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
            /*'title',*/
            'code',
                'language_id',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            'created_on:datetime',
                [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
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