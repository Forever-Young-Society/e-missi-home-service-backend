<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use app\modules\workzone\models\Postcode;
use app\modules\workzone\models\Location;
/* @var $this yii\web\View */
/* @var $model app\modules\workzone\models\Location */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Workzones'),
    'url' => [
        '/workzone'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Locations'),
    'url' => [

        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="location-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'location-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'primary_location',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('primary_location')
                ],
                [
                    'attribute' => 'primary_zipcode',
                    'format' => 'raw',
                    'value' => $model->getLocationZipcodes(Location::PRIMARY_ZIPCODE)
                ],
                [
                    'attribute' => 'secondary_location',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('secondary_location')
                ],
                [
                    'attribute' => 'secondary_zipcode',
                    'format' => 'raw',
                    'value' => $model->getLocationZipcodes(Location::SECONDARY_ZIPCODE)
                ],
                [
                    'attribute' => 'second_secondary_location',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('second_secondary_location')
                ],
                [
                    'attribute' => 'second_secondary_zipcode',
                    'format' => 'raw',
                    'value' => $model->getLocationZipcodes(Location::SECOND_SECONDARY_ZIPCODE)
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
	<div class=" card ">
		<div class=" card-body ">
			<div class="rating-panel">
<?php
$this->context->startPanel();
$this->context->addPanel('Selected Providers', 'userWorkzones', 'UserWorkzone', $model /* ,null,true */);
$this->context->endPanel();
?>
				</div>
		</div>
	</div>
</div>
