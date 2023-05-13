<?php

/* @var $this yii\web\View */
/* @var $model app\models\UserWorkzone */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'User Workzones'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="user-workzone-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


