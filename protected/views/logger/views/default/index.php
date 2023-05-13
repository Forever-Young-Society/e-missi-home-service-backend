<?php
use app\components\TDashBox;
use app\modules\logger\models\Log;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use app\components\TDashboard;
use app\controllers\FeedController;
?>

<div class="wrapper">
	
        
        
<?php
echo TDashBox::widget([
    'items' => [

        [
            'url' => Url::toRoute([
                '/logger/log/index'
            ]),

            'data' => Log::find()->count(),
            'header' => 'Logs'
        ]
    ]
]);
?>



	<div class="card">

		<div class="card-body">
			<div class="panel-heading">
				<span class="tools pull-right"> </span>
			</div>
                        <?php
                        $data = Log::monthly();
                        echo Highcharts::widget([
                            'options' => [
                                'credits' => array(
                                    'enabled' => false
                                ),

                                'title' => [
                                    'text' => 'Error Reports'
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
                                        'name' => 'Emails',
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
    $searchModel = new \app\modules\logger\models\search\Log();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    ?>
    <?php echo $this->render('/log/_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>

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
	<?php } ?>
</div>