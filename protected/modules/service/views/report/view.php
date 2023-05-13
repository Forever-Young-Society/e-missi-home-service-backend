<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\modules\service\models\Report;
/* @var $this yii\web\View */
/* @var $model app\modules\service\models\Report */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Reports'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="report-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'report-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                'zipcode',
                'address',
                'service_provided',
                'age',
                [
                    'attribute' => 'dependant_id',
                    'format' => 'raw',
                    'visible' => ($model->type_id == Report::TYPE_DEPENDANT),
                    'value' => $model->getRelatedDataLink('dependant_id')
                ],
                [
                    'attribute' => 'state_id',
                    'format' => 'raw',
                    'value' => $model->getStateBadge()
                ],
                [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
                'created_on:datetime',
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
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
	<div class="card">
		<div class="card-body">
			<div class="service-report-panel">
            <?php
            $this->context->startPanel();
            $this->context->addPanel('Provider Detail', 'createdBy', 'User', $model /* ,null,true */);
            $this->context->addPanel('User Detail', 'userDetail', 'User', $model /* ,null,true */);
            if ($model->type_id == Report::TYPE_DEPENDANT) {
                $this->context->addPanel('Dependant Detail', 'dependantDetail', 'User', $model /* ,null,true */);
            }
            $this->context->addPanel('Booking Detail', 'bookingDetail', 'SlotBooking', $model /* ,null,true */);
            $this->context->endPanel();
            ?>
         </div>
		</div>
	</div>
</div>

