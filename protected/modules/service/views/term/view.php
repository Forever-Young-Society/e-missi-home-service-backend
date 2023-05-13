<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\service\models\Term */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Terms'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="term-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'term-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
            /*'title',*/
            /*'description:html',*/
                [
                    'attribute' => 'category_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('category_id')
                ],
                
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            'created_on:datetime'
            ]
        ])?>
         <?php  echo $model->description;?>
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
