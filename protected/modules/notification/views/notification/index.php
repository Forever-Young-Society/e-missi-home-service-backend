<?php


/* @var $this yii\web\View */
/* @var $searchModel app\modules\notification\models\search\Notification */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index');*/
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');;
?>
<div class="wrapper">
		<div class="card">
			<div class="notification-index">
				<?=  \app\components\PageHeader::widget(); ?>
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


