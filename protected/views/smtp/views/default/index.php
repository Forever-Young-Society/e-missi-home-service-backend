<?php
use app\components\TDashBox;
use app\models\User;
use app\modules\smtp\models\Unsubscribe;
use app\modules\smtp\models\Account;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use app\modules\smtp\models\EmailQueue;
?>
<div class="wrapper">
	
         <?php

        echo TDashBox::widget([
            'items' => [
               
                [
                    'url' => Url::toRoute([
                        '/smtp/account/',
                        'EmailQueue[state_id]' => 0
                    ]),
                    'data' => Account::find()->count(),
                    'header' => 'Accounts',
                    'icon' => 'fa fa-envelope',
                    'visible' => User::isAdmin()
                ],
                [
                    'url' => Url::toRoute([
                        '/smtp/email-queue/index',
                        'EmailQueue[state_id]' => 0
                    ]),
                    'data' => EmailQueue::getPendingEmails()->count(),
                    'header' => 'Pending Emails',
                    'icon' => 'fa fa-inbox',
                    'visible' => User::isAdmin()
                ],
                [
                    'url' => Url::toRoute([
                        '/smtp/unsubscribe/',
                        'EmailQueue[state_id]' => 0
                    ]),
                    'data' =>Unsubscribe::find()->count(),
                    'header' => 'Unsubscribes',
                    'icon' => 'fa fa-remove',
                    'visible' => User::isAdmin()
                ]
            ]
        ]);
        ?>
     
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
               <?php

            $data = EmailQueue::monthly(EmailQueue::STATE_SENT);
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
                            'name' => 'Emails Send',
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