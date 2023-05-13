<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\modules\service\models\ProviderSkill;
/* @var $this yii\web\View */
/* @var $model app\modules\service\models\ProviderSkill */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Services'),
    'url' => [
        '/service'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Provider Skills'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="provider-skill-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'provider-skill-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                [
                    'attribute' => 'category_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('category_id')
                ],
                [
                    'attribute' => 'parent_id',
                    'format' => 'raw',
                    'visible' => ($model->type_id == ProviderSkill::TYPE_SUB_SKILL_NO),
                    'value' => $model->getRelatedDataLink('parent_id')
                ],
                [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
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
	<?php if($model->type_id == ProviderSkill::TYPE_SUB_SKILL_YES){?>
	<div class="card">
		<div class="card-body">
			<div class="provider-skill-panel">
            <?php
    $this->context->startPanel();
    $this->context->addPanel('Sub Skills', 'subSkills', 'ProviderSkill', $model /* ,null,true */);
    $this->context->endPanel();
    ?>
         </div>
		</div>
	</div>
	<?php }?>
</div>
