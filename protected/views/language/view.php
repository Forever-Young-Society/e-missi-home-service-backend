<?php

use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\models\Language */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Languages'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
    <div class="card">
        <div class="language-view">
            <?php echo  \app\components\PageHeader::widget(['model' => $model]); ?>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php

            echo \app\components\TDetailView::widget([
                'id' => 'language-detail-view',
                'model' => $model,
                'attributes' => [
                    'id',
                    'title',
                    //'code',
                    /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
                    'created_on:datetime',

                    [
                        'attribute' => 'created_by_id',
                        'format' => 'raw',
                        'value' => $model->getRelatedDataLink('created_by_id')
                    ]
                ]
            ]) ?>

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
        <header class="card-header">
            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
        </header>
        <div class="card-body">
            <div class="language-panel">
                <?php
                $this->context->startPanel();
                $this->context->addPanel('Feeds', 'feeds', 'Feed', $model /* ,null,true */);
                $this->context->endPanel();
                ?>
            </div>
        </div>
    </div>

</div>