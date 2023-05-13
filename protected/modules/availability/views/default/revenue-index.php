<?php
use app\components\TDashBox;
use yii\helpers\Url;
use app\modules\availability\models\SlotBooking;
use app\models\User;
use miloschuman\highcharts\Highcharts;
?>
<div class="wrapper">
	<div class="card">
		<div class="card-body">
         <?php

        echo TDashBox::widget([
            'items' => [
                [
                    'url' => Url::toRoute([
                        '/availability/slot-booking',
                        'SlotBooking[state_id]' => SlotBooking::STATE_COMPLETED
                    ]),
                    'color' => 'bg-success-gradient',
                    'data' => SlotBooking::getBookingRevenue(),
                    'header' => 'Total Revenue',
                    'icon' => 'fa fa-money'
                ],

                [
                    'url' => Url::toRoute([
                        '/availability/slot-booking',
                        'SlotBooking[state_id]' => SlotBooking::STATE_COMPLETED
                    ]),
                    'color' => 'bg-success-gradient',
                    'data' => SlotBooking::getBookingRevenue(date('Y-m-d')),
                    'header' => "Today's Revenue",
                    'icon' => 'fa fa-money'
                ],

                [
                    'url' => Url::toRoute([
                        '/availability/slot-booking',
                        'SlotBooking[state_id]' => SlotBooking::STATE_COMPLETED
                    ]),
                    'color' => 'bg-success-gradient',
                    'data' => SlotBooking::getBookingCount(SlotBooking::STATE_COMPLETED),
                    'header' => 'Completed Bookings',
                    'icon' => 'fa fa-check'
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
                ]
            ]
        ]);
        ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="col-md-12">
				<div class="row mt-12">
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
                    $data = SlotBooking::daily();
                    // $provider = User::daily(User::STATE_ACTIVE, User::ROLE_SERVICE_PROVIDER);
                    echo Highcharts::widget([
                        'options' => [
                            'credits' => array(
                                'enabled' => false
                            ),

                            'title' => [
                                'text' => 'Daily Revenue'
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
                                    'name' => 'Revenue',
                                    'data' => array_values($data)
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
$data = SlotBooking::monthly();
// $provider = User::monthly(User::STATE_ACTIVE, User::ROLE_SERVICE_PROVIDER);
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Monthly Revenue'
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
                'name' => 'Revenue',
                'data' => array_values($data)
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
$data = SlotBooking::weekly();
// $provider = User::weekly(User::STATE_ACTIVE, User::ROLE_SERVICE_PROVIDER);
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Weekly Revenue'
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
                'name' => 'Revenue',
                'data' => array_values($data)
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
$data = SlotBooking::yearly();
// $provider = User::yearly(User::ROLE_SERVICE_PROVIDER, User::STATE_ACTIVE);
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Yearly Revenue'
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
                'name' => 'Revenue',
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
