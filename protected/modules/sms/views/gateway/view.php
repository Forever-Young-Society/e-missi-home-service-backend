<?php
use app\components\useraction\UserAction;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model app\models\SmsGateway */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Payment Gateways'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">
        <div class="payment-gateway-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php
    
    echo \app\components\TDetailView::widget([
        'id' => 'payment-gateway-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            [
                'attribute' => 'type_id',
                'value' => $model->getType()
            ],
            'created_on:datetime',
            'updated_on:datetime',
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => $model->getRelatedDataLink('created_by_id')
            ]
        ]
    ])?>
				
				<div class="clearfix"></div>
				<hr>
				<h3> <?= \Yii::t('app', 'Details') ?> </h3>
				<?php
    $gateWaySettings = $model->getGatewaySettings();
    if ($gateWaySettings) {
        foreach ($gateWaySettings as $key => $setting) {
            ?>
						<div class="col-md-12 m-t-10">
					<div class="col-md-2">
						<strong><?= Inflector::titleize ( $key ) ?> - </strong>
					</div>
					<div class="col-md-10"><?= $setting ?></div>
				</div>
					<?php
        }
    }
    ?>
				<div class="clearfix"></div>
				<hr>
			
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