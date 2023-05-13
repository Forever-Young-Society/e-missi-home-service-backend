<?php

/* @var $this yii\web\View */
/* @var $model app\modules\seo\models\Log */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Seo'),
    'url' => [
        '/seo'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Logs'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="log-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


