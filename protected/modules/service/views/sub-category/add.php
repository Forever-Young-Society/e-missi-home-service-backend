<?php
use app\modules\service\models\SubCategory;

/* @var $this yii\web\View */
/* @var $model app\modules\service\models\SubCategory */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Sub Categories'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
$form = ($service_type == SubCategory::SERVICE_TYPE_DIRECT_BOOKING) ? '_direct-form' : '_form';
?>

<div class="wrapper">
	<div class="card">
		<div class="sub-category-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( $form, [ 'model' => $model ] )?>
	</div>
</div>


