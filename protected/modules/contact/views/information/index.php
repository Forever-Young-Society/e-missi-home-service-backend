<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\contact\models\search\Information */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index'); */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Contact'),
    'url' => [
        '/contact'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Information'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>
<div class="wrapper">
	<div class="card">
		<div class="information-index">
				<?=  \app\components\PageHeader::widget(); ?>
			</div>

	</div>
	<div class="card">
		<header class="card-header">   <?php echo strtoupper(Yii::$app->controller->action->id); ?> </header>
		<div class="card-body">
			<div class="content-section">
					<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
				</div>
		</div>
	</div>
</div>


