<?php
use app\components\TDashBox;
use app\modules\logger\models\Log;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use app\modules\contact\models\search\Information;
use app\components\TDashboard;
use app\controllers\FeedController;
use app\modules\contact\models\Address;
use app\modules\contact\models\Phone;
?>

<div class="wrapper">
	<div class="card">

		<div class="card-body">
        
        
<?php
echo TDashBox::widget([
    'items' => [

        [
            'url' => Url::toRoute([
                '/contact/information/index'
            ]),

            'data' => Information::find()->count(),
            'header' => 'Logs',
            'icon' => 'fa fa-list'
        ],
        [
            'url' => Url::toRoute([
                '/contact/address'
            ]),

            'data' => Address::find()->count(),
            'header' => 'Adresses',
            'icon' => 'fa fa-home'
        ],
        [
            'url' => Url::toRoute([
                '/contact/phone'
            ]),

            'data' => Phone::find()->count(),
            'header' => 'Phones',
            'icon' => 'fa fa-phone'
        ]
    ]
]);
?>

</div>
	</div>

	<div class="card">

		<div class="card-body">
			<div class="panel-heading">
				<span class="tools pull-right"> </span>
			</div>
                        <?php
                        $data = Information::monthly();
                        echo Highcharts::widget([
                            'options' => [
                                'credits' => array(
                                    'enabled' => false
                                ),

                                'title' => [
                                    'text' => 'Leads Reports'
                                ],
                                'chart' => [
                                    'type' => 'spline'
                                ],
                                'xAxis' => [
                                    'categories' => array_keys($data)
                                ],
                                'yAxis' => [
                                    'title' => [
                                        'text' => 'Count'
                                    ]
                                ],
                                'series' => [
                                    [
                                        'name' => 'Leads',
                                        'data' => array_values($data)
                                    ]
                                ]
                            ]
                        ]);
                        ?>

        </div>
	</div>
	<div class="card">

		<div class="card-body">
			<div class="panel-heading">
				<span class="tools pull-right"> </span>
			</div>
                    
    <?php
    $searchModel = new Information();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    echo $this->render('/information/_grid', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel
    ]);
    ?>

        </div>
	</div>
	<?php if ( method_exists(FeedController::class, 'actionModule')){?>
		<div class="card">
		<div class="card-body">
 <?php

    echo TDashboard::widget([
        'items' => [
            [
                'label' => 'Recent Activities',
                'url' => Url::toRoute([
                    '/feed/module',
                    'id' => $this->context->module->id
                ])
            ]
        ]
    ]);
    ?>

      </div>
	</div>
	<?php }?>
</div>