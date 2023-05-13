<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\Information */

/* $this->title = Yii::t('app', 'Add');*/
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Informations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="information-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


