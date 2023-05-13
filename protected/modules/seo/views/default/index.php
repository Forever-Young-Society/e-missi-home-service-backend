<?php
use app\components\TDashBox;
use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;
use app\components\TDashboard;
use app\modules\seo\models\Analytics;
use app\modules\seo\models\Log;
use app\modules\seo\models\Seo;
?>

<div class="wrapper">
	
<?php
echo TDashBox::widget([
    'items' => [
        [
            'url' => Url::toRoute([
                '/seo/log'
            ]),

            'data' => Log::findActive(Log::STATE_ALLOWED)->count(),
            'header' => 'Logs',
            'icon' => 'fa fa-key'
        ],
        [
            'url' => Url::toRoute([
                '/seo/analytics'
            ]),

            'data' => Analytics::findActive()->count(),
            'header' => 'Analytics',
            'icon' => 'fa fa-list'
            
        ],
        [
            'url' => Url::toRoute([
                '/seo/manager'
            ]),

            'data' => Seo::findActive()->count(),
            'header' => 'Seo',
            'icon' => 'fa fa-file'
        ]
    ]
]);
?>
	<div class="card">
		<div class="card-body">
			<div class="col-md-11">
<?php
$allowed = Log::monthly(Log::STATE_ALLOWED);
$banned = Log::monthly(Log::STATE_BANNED);
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Monthly Logs'
        ],
        'chart' => [
            'type' => 'spline'
        ],
        'xAxis' => [
            'categories' => array_keys($allowed)
        ],
        'yAxis' => [
            'title' => [
                'text' => 'log'
            ]
        ],
        'series' => [
            [
                'name' => 'Allowed',
                'data' => array_values($allowed),
                'color' => 'green'
            ],
            [
                'name' => 'Banned',
                'data' => array_values($banned),
                'color' => 'red'
            ]
        ]
    ]
]);
?>
	</div>
		</div>
	</div>
	<div class="clearfix"></div>
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
</div>