<?php
use app\models\User;
use app\modules\service\models\Category;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use app\modules\availability\models\SlotBooking;
?>
<div class="wrapper">
	<div class="card">
		<div class="card-body">

			<div class="row state-overview">
				<div class="col-md-4 col-lg-4 col-xl-3 col-sm-6">
					<a href="<?php echo Url::toRoute(['/service/category']) ?>">
						<section class=<?php echo 'bg-success-gradient' ?>>
							<div class="symbol">
								<i class="<?php echo 'fa fa-female' ?>"></i>

							</div>
							<div class="value white">
								<h3 data-speed="1000" data-to="320" data-from="0" class="timer"><?php echo Category::getTypeTitle(Category::TYPE_NURSING_SERVICE) ?></h3>
								<div class="d-flex align-items-center justify-content-between">
									<p><?= Yii::t('app', 'No of Sp:') ?> <?= Category::getServiceProviderCount(Category::TYPE_NURSING_SERVICE) ?></p>
									<p><?= Yii::t('app', 'Total Treatment:') ?> <?= Category::getServiceBookingCount(Category::TYPE_NURSING_SERVICE) ?></p>
								</div>
							</div>
						</section>
					</a>
				</div>
				<div class="col-md-4 col-lg-4 col-xl-3 col-sm-6">
					<a href="<?php echo Url::toRoute(['/service/category']) ?>">
						<section class=<?php echo 'bg-success-gradient' ?>>
							<div class="symbol">
								<i class="<?php echo 'fa fa-user-md' ?>"></i>

							</div>
							<div class="value white">
								<h3 data-speed="1000" data-to="320" data-from="0" class="timer"><?php echo Category::getTypeTitle(Category::TYPE_CAREGIVER_SERVICE) ?></h3>
								<div class="d-flex align-items-center justify-content-between">
									<p><?= Yii::t('app', 'No of Sp:') ?> <?= Category::getServiceProviderCount(Category::TYPE_CAREGIVER_SERVICE) ?></p>
									<p><?= Yii::t('app', 'Total Treatment:') ?> <?= Category::getServiceBookingCount(Category::TYPE_CAREGIVER_SERVICE) ?></p>
								</div>
							</div>
						</section>
					</a>
				</div>
				<div class="col-md-4 col-lg-4 col-xl-3 col-sm-6">
					<a href="<?php echo Url::toRoute(['/service/category']) ?>">
						<section class=<?php echo 'bg-success-gradient' ?>>
							<div class="symbol">
								<i class="<?php echo 'fa fa-users' ?>"></i>

							</div>
							<div class="value white">
								<h3 data-speed="1000" data-to="320" data-from="0" class="timer"><?php echo Category::getTypeTitle(Category::TYPE_PHYSIOTHERAPIST_SERVICE) ?></h3>
								<div class="d-flex align-items-center justify-content-between">
									<p><?= Yii::t('app', 'No of Sp:') ?> <?= Category::getServiceProviderCount(Category::TYPE_PHYSIOTHERAPIST_SERVICE) ?></p>
									<p><?= Yii::t('app', 'Total Treatment:') ?> <?= Category::getServiceBookingCount(Category::TYPE_PHYSIOTHERAPIST_SERVICE) ?></p>
								</div>
							</div>
						</section>
					</a>
				</div>
				<div class="col-md-4 col-lg-4 col-xl-3 col-sm-6">
					<a href="<?php echo Url::toRoute(['/service/category']) ?>">
						<section class=<?php echo 'bg-success-gradient' ?>>
							<div class="symbol">
								<i class="<?php echo 'fa fa-list' ?>"></i>
							</div>
							<div class="value white">
								<h3 data-speed="1000" data-to="320" data-from="0" class="timer"><?php echo Category::getTypeTitle(Category::TYPE_ACUPUNCTURE_SERVICE) ?></h3>
								<div class="d-flex align-items-center justify-content-between">
									<p><?= Yii::t('app', 'No of Sp:') ?> <?= Category::getServiceProviderCount(Category::TYPE_ACUPUNCTURE_SERVICE) ?></p>
									<p><?= Yii::t('app', 'Total Treatment:') ?> <?= Category::getServiceBookingCount(Category::TYPE_ACUPUNCTURE_SERVICE) ?></p>
								</div>
							</div>
						</section>
					</a>
				</div>
			</div>
		</div>
	</div>

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
                    $data = SlotBooking::dailyBooking();
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
                                'categories' => array_keys($data)
                            ],
                            'yAxis' => [
                                'title' => [
                                    'text' => 'Count'
                                ]
                            ],
                            'series' => [
                                [
                                    'name' => 'Bookings',
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
$data = SlotBooking::monthlyBooking();
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Monthly'
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
                'name' => 'Bookings',
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
$data = SlotBooking::weeklyBooking();
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Weekly'
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
                'name' => 'Bookings',
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
$data = SlotBooking::yearlyBooking();
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

        'title' => [
            'text' => 'Yearly'
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
                'name' => 'Bookings',
                'data' => array_values($data)
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
                        'text' => Yii::t('app', 'Service Provider Data')
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
                                    'name' => 'Nurse',
                                    'y' => Category::getServiceProviderCount(Category::TYPE_NURSING_SERVICE),
                                    'sliced' => true,
                                    'selected' => true
                                ],
                                [
                                    'name' => 'Caregiver',
                                    'y' => Category::getServiceProviderCount(Category::TYPE_CAREGIVER_SERVICE),
                                    'sliced' => true,
                                    'selected' => true
                                ],
                                [
                                    'name' => 'TCM Physician',
                                    'y' => Category::getServiceProviderCount(Category::TYPE_ACUPUNCTURE_SERVICE),
                                    'sliced' => true,
                                    'selected' => true
                                ],
                                [
                                    'name' => 'Physiotherapist',
                                    'y' => Category::getServiceProviderCount(Category::TYPE_PHYSIOTHERAPIST_SERVICE),
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
