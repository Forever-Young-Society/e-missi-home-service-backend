<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\seo\models\Redirect */

/* $this->title = Yii::t('app', 'Add'); */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Redirects'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
	<div class="redirect-create">
		<?=  \app\components\PageHeader::widget(); ?>
	</div>

	</div>

	<div class="content-section clearfix card">

		<?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>


