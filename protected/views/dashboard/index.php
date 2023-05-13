<?php
use app\components\TDashBox;
use app\models\EmailQueue;
use app\models\User;
use app\modules\availability\models\SlotBooking;
use app\modules\logger\models\Log;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use app\modules\availability\models\ProviderSlot;
/**
 *
 * @copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
/* @var $this yii\web\View */
// $this->title = Yii::t ( 'app', 'Dashboard' );

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Dashboard')
];
?>


<div class="wrapper">
	<div class="main-das-hd ">
		<h5>Hi, Welcome back!</h5>
	</div>
	<!--state overview start-->
         <?php

        echo TDashBox::widget([
            'items' => [
                [
                    'url' => Url::toRoute([
                        '/availability/slot-booking',
                        'SlotBooking[state_id]' => SlotBooking::STATE_COMPLETED
                    ]),
                    'color' => 'bg-success-gradient',
                    'data' => User::DEFAULT_CURRENCY . ' ' . SlotBooking::getRevenue(),
                    'header' => 'Revenue',
                    'icon' => 'fa fa-money'
                ],
                [
                    'url' => Url::toRoute([
                        '/availability/slot-booking',
                        'SlotBooking[payment_status]' => SlotBooking::PAYMENT_SUCCESS
                    ]),
                    'color' => 'bg-success-gradient',
                    'data' => SlotBooking::getBookingCount(),
                    'header' => 'Bookings',
                    'icon' => 'fa fa-calendar'
                ],
                [
                    'url' => Url::toRoute([
                        '/user'
                    ]),
                    'color' => 'bg-primary-gradient',
                    'data' => User::getUsersCount(User::TYPE_USER),
                    'header' => 'Users'
                ],
                [
                    'url' => Url::toRoute([
                        '/user/provider'
                    ]),
                    'color' => 'bg-success-gradient',
                    'data' => User::getProvidersCount(User::IS_APPROVE),
                    'header' => 'Service Providers',
                    'icon' => 'fa fa-user-secret'
                ],

                [
                    'url' => Url::toRoute([
                        '/availability/slot-booking',
                        'SlotBooking[state_id]' => SlotBooking::STATE_CANCELED
                    ]),
                    'color' => 'bg-danger-gradient',
                    'data' => SlotBooking::getBookingCount(SlotBooking::STATE_CANCELED),
                    'header' => 'Cancelled Bookings',
                    'icon' => 'fa fa-times'
                ],

                [
                    'url' => Url::toRoute([
                        '/availability/slot-booking',
                        'SlotBooking[start_time]' => date('Y-m-d')
                    ]),
                    'color' => 'bg-success-gradient',
                    'data' => SlotBooking::getTodayBookingCount(date('Y-m-d')),
                    'header' => "Today's Bookings",
                    'icon' => 'fa fa-calendar'
                ],

                [

                    'url' => Url::toRoute([
                        '/availability/provider-slot'
                    ]),

                    'color' => 'bg-danger-gradient',
                    'data' => ProviderSlot::getProviderSlotsCount(),
                    'header' => 'Provider Slots',
                    'icon' => 'fa fa-calendar'
                ],
                [
                    'url' => Url::toRoute([
                        '/logger/log'
                    ]),
                    'color' => 'bg-warning-gradient',
                    'data' => User::getLogCount(),
                    'header' => 'Logs',
                    'icon' => 'fa fa-history'
                ],
                [
                    'url' => Url::toRoute([
                        '/user/provider-approval'
                    ]),
                    'color' => 'bg-success-gradient',
                    'data' => User::getProvidersCount(User::APPROVAL_PENDING),
                    'header' => 'Provider Approvals',
                    'icon' => 'fa fa-user-secret'
                ],

                [
                    'url' => Url::toRoute([
                        '/user/dependent-user'
                    ]),
                    'color' => 'bg-warning-gradient',
                    'data' => User::getUsersCount(User::TYPE_DEPENDENT, false),
                    'header' => 'Dependent Users',
                    'icon' => 'fa fa-users'
                ],

                [
                    'url' => Url::toRoute([
                        '/user/incomplete'
                    ]),
                    'color' => 'bg-primary-gradient',
                    'data' => User::getIncompleteUsersCount(),
                    'header' => 'Incomplete Users',
                    'icon' => 'fa fa-times'
                ],
                [
                    'url' => Url::toRoute([
                        '/user/rejected'
                    ]),
                    'color' => 'bg-danger-gradient',
                    'data' => User::getProvidersCount(User::IS_REJECT, false),
                    'header' => 'Rejected Providers',
                    'icon' => 'fa fa-times'
                ]
            ]
        ]);
        ?>
   
<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-7">
					<div class="row mt-3">
						<div
							class="col-lg-12 col-xl-12 d-xl-flex mb-4 justify-content-xl-start">
							<button
								class="users-data-btn btn btn-custom active mr-2 chart_type"
								data-id="dailyData" type="button">Daily</button>
							<button class="users-data-btn btn btn-custom mr-2 chart_type"
								data-id="monthlyData" type="button">weekly</button>
							<button class="users-data-btn btn btn-custom mr-2 chart_type"
								data-id="weeklyData" type="button">Monthly</button>
							<button class="users-data-btn btn btn-custom mr-2 chart_type"
								data-id="yearlyData" type="button">Yearly</button>

						</div>
					</div>
					<div class="card1 data-row active" id="dailyData">
						<div class="card">
							<div class="card-heading">
								<span class="tools pull-right"> </span>
							</div>
							<br>
							<div class="card-body">
                    <?php
                    $user = User::daily(User::STATE_ACTIVE, User::ROLE_USER);
                    $provider = User::daily(User::STATE_ACTIVE, User::ROLE_SERVICE_PROVIDER);
                    echo Highcharts::widget([
                        'options' => [
                            'credits' => array(
                                'enabled' => false
                            ),

                            'title' => [
                                'text' => 'Daily'
                            ],
                            'chart' => [
                                'type' => 'spline'
                            ],
                            'xAxis' => [
                                'categories' => array_keys($provider)
                            ],
                            'yAxis' => [
                                'title' => [
                                    'text' => 'Count'
                                ]
                            ],
                            'series' => [
                                [
                                    'name' => 'User',
                                    'data' => array_values($user)
                                ],
                                [
                                    'name' => 'Service Provider',
                                    'data' => array_values($provider)
                                ]
                            ]
                        ]
                    ]);
                    ?>
       </div>
						</div>
					</div>
					<div class="card2 data-row d-none" id="weeklyData">
						<div class="card">
							<div class="card-heading">
								<span class="tools pull-right"> </span>
							</div>
							<div class="card-body">
<?php
$user = User::monthly(User::STATE_ACTIVE, User::ROLE_USER);
$provider = User::monthly(User::STATE_ACTIVE, User::ROLE_SERVICE_PROVIDER);
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Monthly Service Provider'
        ],
        'chart' => [
            'type' => 'spline'
        ],
        'xAxis' => [
            'categories' => array_keys($provider)
        ],
        'yAxis' => [
            'title' => [
                'text' => 'Count'
            ]
        ],
        'series' => [
            [
                'name' => 'User',
                'data' => array_values($user)
            ],
            [
                'name' => 'Service Provider',
                'data' => array_values($provider)
            ]
        ]
    ]
]);
?>
</div>
						</div>
					</div>
					<div class="card5 data-row d-none" id="monthlyData">
						<div class="card">
							<div class="card-heading">
								<span class="tools pull-right"> </span>
							</div>
							<div class="card-body">
<?php
$user = User::weekly(User::STATE_ACTIVE, User::ROLE_USER);
$provider = User::weekly(User::STATE_ACTIVE, User::ROLE_SERVICE_PROVIDER);
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Weekly Service Provider'
        ],
        'chart' => [
            'type' => 'spline'
        ],
        'xAxis' => [
            'categories' => array_keys($provider)
        ],
        'yAxis' => [
            'title' => [
                'text' => 'Count'
            ]
        ],
        'series' => [
            [
                'name' => 'User',
                'data' => array_values($user)
            ],
            [
                'name' => 'Service Provider',
                'data' => array_values($provider)
            ]
        ]
    ]
]);
?>
</div>
						</div>
					</div>
					<div class="card3 data-row d-none" id="yearlyData">
						<div class="card">
							<div class="card-heading">
								<span class="tools pull-right"> </span>
							</div>
							<div class="card-body">

<?php
$user = User::yearly(User::STATE_ACTIVE, User::ROLE_USER);
$provider = User::yearly(User::ROLE_SERVICE_PROVIDER, User::STATE_ACTIVE);
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Yearly Service Provider'
        ],
        'chart' => [
            'type' => 'spline'
        ],
        'xAxis' => [
            'categories' => array_keys($provider)
        ],
        'yAxis' => [
            'title' => [
                'text' => 'Count'
            ]
        ],
        'series' => [
            [
                'name' => 'User',
                'data' => array_values($user)
            ],
            [
                'name' => 'Service Provider',
                'data' => array_values($provider)
            ]
        ]
    ]
]);

?>
     </div>
						</div>
					</div>
					<div class="card4 data-row d-none" id="customData">
						<div class="card" style="width: 100%">
							<div class="card-heading">
								<span class="tools pull-right"> </span>
							</div>


						</div>
					</div>
				</div>
				<div class="col-md-5 pt-4">
					<div class="card mt-5" style="width: 100%">
						<div class="card-heading">
							<span class="tools pull-right"> </span>
						</div>
						<div class="card-body">

                            

              <?php
            echo Highcharts::widget([
                'scripts' => [
                    'highcharts-3d',
                    'modules/exporting'
                ],

                'options' => [
                    'credits' => array(
                        'enabled' => false
                    ),
                    'chart' => [
                        'plotBackgroundColor' => null,
                        'plotBorderWidth' => null,
                        'plotShadow' => false,
                        'type' => 'pie'
                    ],
                    'title' => [
                        'text' => Yii::t('app', 'User Data')
                    ],
                    'tooltip' => [
                        'valueSuffix' => ''
                    ],
                    'plotOptions' => [
                        'pie' => [
                            'allowPointSelect' => true,
                            'cursor' => 'pointer',
                            'dataLabels' => [
                                'enabled' => true
                            ],
                            'showInLegend' => true
                        ]
                    ],

                    'htmlOptions' => [
                        'style' => 'min-width: 100%;
                height: 400px;
                margin: 0 auto'
                    ],
                    'series' => [
                        [
                            'name' => 'Total Count',
                            'colorByPoint' => true,

                            'data' => [
                                [
                                    'name' => 'User',
                                    'y' => (int) User::getUsersCount(User::TYPE_USER),
                                    'sliced' => true,
                                    'selected' => true
                                ],
                                [
                                    'name' => 'Service Provider',
                                    'y' => (int) User::getProvidersCount(User::IS_APPROVE),
                                    'sliced' => true,
                                    'selected' => true
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
            ?>
            </div>
					</div>
				</div>
				<div class="col-md-4 pt-4">
					<div class="card mt-5" style="width: 100%">
						<div class="card-heading">
							<span class="tools pull-right"> </span>
						</div>
						<div class="card-body">

                            

              <?php
            echo Highcharts::widget([
                'scripts' => [
                    'highcharts-3d',
                    'modules/exporting'
                ],

                'options' => [
                    'credits' => array(
                        'enabled' => false
                    ),
                    'chart' => [
                        'plotBackgroundColor' => null,
                        'plotBorderWidth' => null,
                        'plotShadow' => false,
                        'type' => 'pie'
                    ],
                    'title' => [
                        'text' => Yii::t('app', 'User Gender')
                    ],
                    'tooltip' => [
                        'valueSuffix' => ''
                    ],
                    'plotOptions' => [
                        'pie' => [
                            'allowPointSelect' => true,
                            'cursor' => 'pointer',
                            'dataLabels' => [
                                'enabled' => true
                            ],
                            'showInLegend' => true
                        ]
                    ],

                    'htmlOptions' => [
                        'style' => 'min-width: 100%;
                height: 400px;
                margin: 0 auto'
                    ],
                    'series' => [
                        [
                            'name' => 'Total Count',
                            'colorByPoint' => true,

                            'data' => [
                                [
                                    'name' => 'Male',
                                    'y' => (int) User::getUsersCount(User::TYPE_USER, true, User::MALE),
                                    'sliced' => true,
                                    'selected' => true
                                ],
                                [
                                    'name' => 'Female',
                                    'y' => (int) User::getUsersCount(User::TYPE_USER, true, User::FEMALE),
                                    'sliced' => true,
                                    'selected' => true
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
            ?>
            </div>
					</div>
				</div>
				<div class="col-md-4 pt-4">
					<div class="card mt-5" style="width: 100%">
						<div class="card-heading">
							<span class="tools pull-right"> </span>
						</div>
						<div class="card-body">

                            

              <?php
            echo Highcharts::widget([
                'scripts' => [
                    'highcharts-3d',
                    'modules/exporting'
                ],

                'options' => [
                    'credits' => array(
                        'enabled' => false
                    ),
                    'chart' => [
                        'plotBackgroundColor' => null,
                        'plotBorderWidth' => null,
                        'plotShadow' => false,
                        'type' => 'pie'
                    ],
                    'title' => [
                        'text' => Yii::t('app', 'Dependent User Gender')
                    ],
                    'tooltip' => [
                        'valueSuffix' => ''
                    ],
                    'plotOptions' => [
                        'pie' => [
                            'allowPointSelect' => true,
                            'cursor' => 'pointer',
                            'dataLabels' => [
                                'enabled' => true
                            ],
                            'showInLegend' => true
                        ]
                    ],

                    'htmlOptions' => [
                        'style' => 'min-width: 100%;
                height: 400px;
                margin: 0 auto'
                    ],
                    'series' => [
                        [
                            'name' => 'Total Count',
                            'colorByPoint' => true,

                            'data' => [
                                [
                                    'name' => 'Male',
                                    'y' => (int) User::getUsersCount(User::TYPE_DEPENDENT, false, User::MALE),
                                    'sliced' => true,
                                    'selected' => true
                                ],
                                [
                                    'name' => 'Female',
                                    'y' => (int) User::getUsersCount(User::TYPE_DEPENDENT, false, User::FEMALE),
                                    'sliced' => true,
                                    'selected' => true
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
            ?>
            </div>
					</div>
				</div>
				<div class="col-md-4 pt-4">
					<div class="card mt-5" style="width: 100%">
						<div class="card-heading">
							<span class="tools pull-right"> </span>
						</div>
						<div class="card-body">

                            

              <?php
            echo Highcharts::widget([
                'scripts' => [
                    'highcharts-3d',
                    'modules/exporting'
                ],

                'options' => [
                    'credits' => array(
                        'enabled' => false
                    ),
                    'chart' => [
                        'plotBackgroundColor' => null,
                        'plotBorderWidth' => null,
                        'plotShadow' => false,
                        'type' => 'pie'
                    ],
                    'title' => [
                        'text' => Yii::t('app', 'Service Provider Gender')
                    ],
                    'tooltip' => [
                        'valueSuffix' => ''
                    ],
                    'plotOptions' => [
                        'pie' => [
                            'allowPointSelect' => true,
                            'cursor' => 'pointer',
                            'dataLabels' => [
                                'enabled' => true
                            ],
                            'showInLegend' => true
                        ]
                    ],

                    'htmlOptions' => [
                        'style' => 'min-width: 100%;
                height: 400px;
                margin: 0 auto'
                    ],
                    'series' => [
                        [
                            'name' => 'Total Count',
                            'colorByPoint' => true,

                            'data' => [
                                [
                                    'name' => 'Male',
                                    'y' => (int) User::getProvidersCount(User::IS_APPROVE, true, User::MALE),
                                    'sliced' => true,
                                    'selected' => true
                                ],
                                [
                                    'name' => 'Female',
                                    'y' => (int) (int) User::getProvidersCount(User::IS_APPROVE, true, User::FEMALE),
                                    'sliced' => true,
                                    'selected' => true
                                ]
                            ]
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

	</div>
</div>

<script>
   $(document).ready(function(){
   $('.kv-clear').hide();
   var len=0;
   $('#w4').change(function()
{
    if( $(this).val().length === len ) {
  	 $('.kv-clear').hide();
    }
    else {
      	 $('.kv-clear').show();
    }
});
  $('.chart_type').on('click',function(){
  $('.chart_type').removeClass('active');
  $(this).addClass('active');
  $('.data-row').removeClass('d-flex').addClass('d-none');

  var data_id = $(this).data('id');

  $('#'+data_id).addClass('d-flex');
   });
    });
</script>