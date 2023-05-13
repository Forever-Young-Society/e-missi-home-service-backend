<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\SocialLink */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Contacts'),
    'url' => [
        '/contact'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Social Links'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
	<div class="card">
		<div class="social-link-create">
			<?=  \app\components\PageHeader::widget(); ?>
		</div>
	</div>

	<div class="content-section clearfix card">
		<?= $this->render ( '_form', [ 'model' => $model ] )?>
	</div>
</div>


