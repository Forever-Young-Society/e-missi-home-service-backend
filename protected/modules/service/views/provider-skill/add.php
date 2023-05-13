<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\service\models\ProviderSkill */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Services'), 'url' => ['/service']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Provider Skills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="provider-skill-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


