<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\availability\models\search\Slot */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Availabilities'), 'url' => ['/availability']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Slots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>
<div class="wrapper">
		<div class="card">
			<div class="slot-index">
				<?=  \app\components\PageHeader::widget(); ?>
			</div>
			
		</div>
		<div class="card">
			<header class="card-header"> 
			  <?php echo strtoupper(Yii::$app->controller->action->id); ?> 
			</header>
			<div class="card-body">
				<div class="content-section clearfix">
					<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
				</div>
			</div>
		</div>
</div>

