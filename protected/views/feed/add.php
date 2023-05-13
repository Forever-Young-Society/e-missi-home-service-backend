<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Feed */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="feed-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


