<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\smtp\models\Account */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SMTP'), 'url' => ['/smtp']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="account-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


