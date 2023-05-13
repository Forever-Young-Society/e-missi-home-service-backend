<?php
use app\components\PageHeader;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index'); */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Driver'),
    'url' => [
        'user/provider-approval'
    ]
];
$title = 'Provider Approvals';
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>
<div class="wrapper">
	<div class="card">
		<?php  PageHeader::widget(['title' => $title]);?>
	</div>
	<div class="card">
		<header class="card-header">   <?=$title?> </header>
		<div class="card-body">
			<?php
echo $this->render('_approvalgrid', [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel
]);
?>
		</div>
	</div>
</div>


