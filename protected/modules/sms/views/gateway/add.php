<?php



/* @var $this yii\web\View */
/* @var $model app\modules\sms\models\Gateway */

/* $this->title = Yii::t('app', 'Add');*/
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Gateways'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">

		<div
			class="gateway-create">
	<?=  \app\components\PageHeader::widget(); ?>
</div>

	</div>

	<div class="content-section clearfix card">

		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>


