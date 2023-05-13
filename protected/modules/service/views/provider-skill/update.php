<?php


/* @var $this yii\web\View */
/* @var $model app\modules\service\models\ProviderSkill */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Services'), 'url' => ['/service']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Provider Skills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
	<div class="card">
		<div class="provider-skill-update">
			<?=  \app\components\PageHeader::widget(['model' => $model]); ?>
		</div>
	</div>
	<div class="card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>

