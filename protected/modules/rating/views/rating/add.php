<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\rating\models\Rating */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ratings'), 'url' => ['/rating']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ratings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">

		<div
			class="rating-create">
	<?=  \app\components\PageHeader::widget(); ?>
</div>

	</div>

	<div class="content-section clearfix card">

		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>


