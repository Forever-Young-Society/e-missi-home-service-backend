<?php


/* @var $this yii\web\View */
/* @var $searchModel app\modules\seo\models\Seo */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Seo'),
    'url' => [
        '/seo'
    ]
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Managers'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');
?>
<div class="wrapper">
	<div class="card">
		<div class="seo-index"><?=  \app\components\PageHeader::widget(); ?></div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="content-section clearfix">
				<?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
					
				</div>
				
			</div>
			
		</div>
</div>

