<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\modules\logger\models\Log;
/* @var $this yii\web\View */
/* @var $model app\modules\logger\models\Log */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Loggers'),
    'url' => [
        '/logger'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Logs'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="log-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'log-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'error',
                    'format' => 'raw',
                    'value' => $model->error,
                    'visible' => $model->type_id == Log::TYPE_WEB || $model->type_id == Log::TYPE_API
                ],
                [
                    'label' => 'Package Name',
                    'attribute' => 'error',
                    'format' => 'raw',
                    'value' => $model->error,
                    'visible' => $model->type_id == Log::TYPE_APP
                ],/*'description:html',*/
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
                [
                    'attribute' => 'link',
                    'value' => $model->link,
                    'visible' => $model->type_id == Log::TYPE_WEB || $model->type_id == Log::TYPE_API
                ],
                [
                    'label' => 'Package Version',
                    'attribute' => 'link',
                    'format' => 'raw',
                    'value' => $model->link,
                    'visible' => $model->type_id == Log::TYPE_APP
                ],
                [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
                [
                    'attribute' => 'referer_link',
                    'format' => 'raw',
                    'value' => $model->referer_link,
                    'visible' => $model->type_id == Log::TYPE_WEB || $model->type_id == Log::TYPE_API
                ],
                [
                    'label' => 'Phone Model',
                    'attribute' => 'referer_link',
                    'format' => 'raw',
                    'value' => $model->referer_link,
                    'visible' => $model->type_id == Log::TYPE_APP
                ],
                [
                    'attribute' => 'user_ip',
                    'format' => 'raw',
                    'value' => $model->user_ip,
                    'visible' => $model->type_id == Log::TYPE_WEB || $model->type_id == Log::TYPE_API
                ],
                [
                    'label' => 'Android Version',
                    'attribute' => 'user_ip',
                    'format' => 'raw',
                    'value' => $model->user_ip,
                    'visible' => $model->type_id == Log::TYPE_APP
                ],
                [
                    'attribute' => 'user_id',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->getRelatedDataLink('user_id');
                    }
                ],
                'created_on:datetime'
            ]
        ])?>
         <?php  echo nl2br($model->description);?>
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