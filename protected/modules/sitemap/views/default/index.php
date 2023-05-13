<?php
use app\components\TDashBox;
use app\models\User;
use app\modules\smtp\models\Unsubscribe;
use app\modules\smtp\models\Account;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use app\modules\smtp\models\EmailQueue;
use app\modules\sitemap\models\search\Item;
?>
<div class="wrapper">
	<div class="card">
		<div class="card-body">
         <?php

        echo TDashBox::widget([
            'items' => [

                [
                    'url' => Url::toRoute([
                        '/sitemap/item/'
                    ]),
                    'data' => Item::find()->count(),
                    'header' => 'URLs',
                    'icon' => 'fa fa-envelope'
                ],
                [
                    'url' => Url::toRoute([
                        '/sitemap/item/',
                        'Item[state_id]' => 0
                    ]),
                    'data' => Item::findActive(0)->count(),
                    'header' => 'Pending URLs',
                    'icon' => 'fa fa-inbox'
                ]
            ]
        ]);
        ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
               <?php

            $data = Item::monthly(Item::STATE_ACTIVE);
            echo Highcharts::widget([
                'options' => [
                    'credits' => [
                        'enabled' => false
                    ],
                    'title' => [
                        'text' => 'Monthly  '
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
                            'name' => 'URLs',
                            'data' => array_values($data)
                        ]
                    ]
                ]
            ]);
            ?>
            </div>

			</div>
		</div>
	</div>

</div>