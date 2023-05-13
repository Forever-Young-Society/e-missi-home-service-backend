<?php
use app\components\TDashBox;
use app\modules\comment\models\Comment;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use app\components\TDashboard;
use app\controllers\FeedController;
?>

<div class="wrapper">
	<div class="card">

		<div class="card-body">
<?php
echo TDashBox::widget([
    'items' => [
        [
            'url' => Url::toRoute([
                '/index'
            ]),

            'data' => 0,
            'header' => 'Comments'
        ]
    ]
]);
?>

</div>
	</div>


	<div class="card">

		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
<?php

$data = Comment::monthly();
echo Highcharts::widget([
    'options' => [
        'credits' => array(
            'enabled' => false
        ),

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
                'name' => 'Comments',
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



	<div class="card">
		<div class="card-body">
<?php
$searchModel = new \app\modules\comment\models\search\Comment();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
// $dataProvider->pagination->pageSize = 5;

echo $this->render('/comment/_grid', [
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
<?php } ?>
</div>