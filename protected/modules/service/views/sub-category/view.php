<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\service\models\SubCategory */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Sub Categories'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="sub-category-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'sub-category-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                [
                    'attribute' => 'price',
                    'format' => 'raw',
                    'value' => User::DEFAULT_CURRENCY . ' ' . $model->price
                ],
                [
                    'attribute' => 'provider_price',
                    'format' => 'raw',
                    'value' => User::DEFAULT_CURRENCY . ' ' . $model->provider_price
                ],
                [
                    'attribute' => 'category_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('category_id')
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
