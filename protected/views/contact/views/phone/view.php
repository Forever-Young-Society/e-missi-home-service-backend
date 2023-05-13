<?php
use app\components\World;
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
/* @var $this yii\web\View */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Contacts'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="contact-view">
         <?php
        echo \app\components\PageHeader::widget([
            'model' => $model
        ]);
        ?>
    </div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php

    echo \app\components\TDetailView::widget([
        'id' => 'contact-detail-view',
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'contact_no',
            [
                'attribute' => 'type_chat',
                // 'value' => $model->getTypeChat(),
                'value' => $model->type_chat
            ],
            'skype_chat',
            'gtalk_chat',
            'whatsapp_enable:boolean',
            'telegram_enable:boolean',
            'toll_free_enable:boolean',
            [
                'attribute' => 'type_id',
                'value' => $model->getType()
            ],
            [
                'attribute' => 'country',
                'value' => ($model->country) ? World::findCountryByCode($model->country) : ''
            ],
            'created_on:datetime',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('created_by_id')
            ]
        ]
    ])?>



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
			<div class="chatscript-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Activities', 'feeds', 'Feed', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
<?php echo CommentsWidget::widget(['model'=>$model]); ?>
</div>
