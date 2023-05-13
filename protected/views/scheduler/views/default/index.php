<?php
use app\components\TDashBox;
use app\modules\scheduler\models\Cronjob;
use app\modules\scheduler\models\Log;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
?>
<div class="wrapper">
	
         <?php

        echo TDashBox::widget([
            'items' => [
                [
                    'url' => Url::toRoute([
                        '/scheduler/cronjob/',
                        'Cronjob[state_id]' => Cronjob::STATE_ACTIVE
                    ]),
                    'color' => 'bg-primary',
                    'data' => Cronjob::findActive()->count(),
                    'header' => 'Active Cronjobs'
                ],
                [
                    'url' => Url::toRoute([
                        '/scheduler/cronjob/',
                        'Cronjob[state_id]' => Cronjob::STATE_INACTIVE
                    ]),
                    'color' => 'bg-info',
                    'data' => Cronjob::findActive(Cronjob::STATE_INACTIVE)->count(),
                    'header' => 'Disabled Cronjobs'
                ],
                [
                    'url' => Url::toRoute([
                        '/scheduler/log/',
                        'Log[state_id]' => Log::STATE_PENDING
                    ]),
                    'color' => 'orange',
                    'data' => Log::findActive(Log::STATE_PENDING)->count(),
                    'header' => 'Pending'
                ],
                [
                    'url' => Url::toRoute([
                        '/scheduler/log/',
                        'Log[state_id]' => Log::STATE_FAILED
                    ]),
                    'color' => 'red',
                    'data' => Log::findActive(Log::STATE_FAILED)->count(),
                    'header' => 'Failed'
                ]
            ]
        ]);
        ?>
    
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
               <?php
            $crons = Cronjob::monthly();
            $active_crons = Cronjob::monthly(Cronjob::STATE_ACTIVE);
            $disabled_crons = Cronjob::monthly(Cronjob::STATE_INACTIVE);
            echo Highcharts::widget([
                'options' => [
                    'credits' => [
                        'enabled' => false
                    ],
                    'title' => [
                        'text' => 'Cronjobs'
                    ],
                    'chart' => [
                        'type' => 'spline'
                    ],
                    'xAxis' => [
                        'categories' => array_keys($crons)
                    ],
                    'yAxis' => [
                        'title' => [
                            'text' => 'Count'
                        ]
                    ],
                    'series' => [
                        [
                            'name' => 'Active Cronjobs',
                            'data' => array_values($active_crons)
                        ],
                        [
                            'name' => 'Disabled Cronjobs',
                            'data' => array_values($disabled_crons)
                        ]
                    ]
                ]
            ]);
            ?>
            </div>
				<div class="col-md-6">
               <?php
            $logs = Log::monthly();
            $pending_logs = Log::monthly(Log::STATE_PENDING);
            $failed_logs = Log::monthly(Log::STATE_FAILED);
            $completed_logs = Log::monthly(Log::STATE_COMPLETED);
            echo Highcharts::widget([
                'options' => [
                    'credits' => [
                        'enabled' => false
                    ],
                    'title' => [
                        'text' => 'Jobs'
                    ],
                    'chart' => [
                        'type' => 'spline'
                    ],
                    'xAxis' => [
                        'categories' => array_keys($logs)
                    ],
                    'yAxis' => [
                        'title' => [
                            'text' => 'Count'
                        ]
                    ],
                    'series' => [
                        [
                            'name' => 'Completed',
                            'data' => array_values($completed_logs),
                            'color' => 'green'
                        ],
                        [
                            'name' => 'Failed',
                            'data' => array_values($failed_logs),
                            'color' => 'red'
                        ],
                        [
                            'name' => 'Pending',
                            'data' => array_values($pending_logs),
                            'color' => 'orange'
                        ]
                    ]
                ]
            ]);
            ?>
            </div>
			</div>
		</div>
	</div>
	<div class="card">
		<div class="card-body">
         <?php

        $searchModel = new \app\modules\scheduler\models\search\Cronjob();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $dataProvider->pagination->pageSize = 5;
        echo $this->render('/cronjob/_grid', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
        ?>
      </div>
	</div>
</div>