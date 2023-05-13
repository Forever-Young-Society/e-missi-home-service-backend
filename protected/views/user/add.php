<?php

/* @var $this yii\web\View */
/* @var $model app\models\User */

/* $this->title = Yii::t('app', 'Add'); */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Users'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>
<div class="wrapper">
	<div class="user-create card">
	<?=  \app\components\PageHeader::widget(); ?>
	</div>

	<div class="content-section card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

