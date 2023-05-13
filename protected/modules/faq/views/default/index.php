<?php
use app\components\TDashBox;
use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;
?>
<div class="wrapper">
   <div class="card">
      <div class="card-body">
         <?php         echo TDashBox::widget([
         'items' => [
         [
         'url' => Url::toRoute(['/index']),
         'data' => 0,
         'header' => 'Tasks'
         ],
         [
         'url' => Url::toRoute([
         '/invoice/index'
         ]),
         'data' => 0,
         'header' => 'Invoice'
         ],
         [
         'url' => Url::toRoute([
         '/invoice/index'
         ]),
         'data' => 0,
         'header' => 'Invoice'
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
               <?php               $data = Post::monthly();
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
               'name' => 'Polls',
               'data' => array_values($data)
               ]
               ]
               ]
               ]);
               ?>
            </div>
            <div class="col-md-6">
               <?php               $data = Type::monthly();
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
               'text' => 'Statistics'
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
               // 'format' => '<b>{point.name}</b>: {point.percentage:.1f} %'
               'showInLegend' => true
               ]
               ],
               'htmlOptions' => [
               'style' => 'min-width: 100%; height: 400px; margin: 0 auto'
               ],
               'series' => [
               [
               'name' => 'Total Count',
               'colorByPoint' => true,
               'data' => [
               [
               'name' => 'Employees',
               'color' => '#0096ff',
               'y' => $data,
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
   <div class="card">
      <div class="card-body">
         <?php         $searchModel = new Post();
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         // $dataProvider->pagination->pageSize = 5;
         echo $this->render('/post/_grid', [
         'dataProvider' => $dataProvider,
         'searchModel' => $searchModel
         ]);
         ?>
      </div>
   </div>
</div>