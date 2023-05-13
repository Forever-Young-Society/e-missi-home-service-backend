<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\seo\models\search\Redirect */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index'); */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Seo'),
    'url' => [
        '/seo'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Redirects'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
$title = "Redirect : only not found pages";
?>
<div class="wrapper">
<div class="card">
	<div class="redirect-index">
	<?=  \app\components\PageHeader::widget(['title' => $title]); ?>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	  </div>

		</div>
		<div class="card">
			<header class="card-header head-border">   <?php echo strtoupper(Yii::$app->controller->action->id); ?> </header>
			<div class="card-body">
				<div class="content-section clearfix">
		<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
</div>
			</div>
		</div>

</div>

