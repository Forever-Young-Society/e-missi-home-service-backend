<?php

use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\Variable */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Variables'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
    <div class="card">
        <div class="variable-view">
            <?php echo  \app\components\PageHeader::widget(['model' => $model]); ?>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?php

            echo \app\components\TDetailView::widget([
                'id' => 'setting-detail-view',
                'model' => $model,
                'attributes' => [
                    'id',
                    'key',
                    'value:html',
                    'module',
                    //                 [
                    //                     'attribute' => 'type_id',
                    //                     'value' => $model->getType()
                    //                 ],
                    [
                        'attribute' => 'created_by_id',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->getRelatedDataLink('created_by_id');
                        }
                    ]
                ]
            ]) ?>
            <?php
            echo UserAction::widget([
                'model' => $model,
                'attribute' => 'state_id',
                'states' => $model->getStateOptions()
            ]);
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="variable-panel">
                <?php
                $this->context->startPanel();
                $this->context->addPanel('Activities', 'feeds', 'Feed', $model /* ,null,true */);
                $this->context->endPanel();
                ?>
            </div>
        </div>
    </div>
    <?php echo CommentsWidget::widget(['model' => $model]); ?>
</div>