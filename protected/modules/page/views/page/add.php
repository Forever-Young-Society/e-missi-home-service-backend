<?php
use app\modules\page\models\Page;

/* @var $this yii\web\View */
/* @var $model Page */

/* $this->title = Yii::t('app', 'Add'); */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Pages'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">

		<div
			class="page-create">
	<?=\app\components\PageHeader::widget();?>
</div>

	</div>

	<div class="content-section clearfix card">

		<?=$this->render('_form', ['model' => $model])?></div>
</div>


