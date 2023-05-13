<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\Phone */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contacts'), 'url' => ['/contact']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="phone-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


