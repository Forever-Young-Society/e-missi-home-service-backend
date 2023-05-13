<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Seo */

/* $this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Seo',
]) . ' ' . $model->title; */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Seos'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class="card">
		<div
			class="seo-update">
	<?=  \app\components\PageHeader::widget(); ?>
	</div>
	</div>


	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

