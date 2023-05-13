<?php
use yii\helpers\Html;
use app\components\useraction\UserAction;

/* @var $this yii\web\View */
/* @var $model app\models\Seo */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Meta'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">
		<div class="seo-view">
                <?php echo  \app\components\PageHeader::widget(['model' => $model]); ?>
     </div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php

echo \app\components\TDetailView::widget([
        'id' => 'seo-detail-view',
        'model' => $model,
        'attributes' => [
            'id',
            'route',
            'title',
            'keywords',
            /*'description:html',*/
            'data'
            /*
         * 'created_on:datetime',
         * 'updated_on:datetime',
         */
        ]
    ])?>


<?php  echo $model->description;?>
   <?php
echo UserAction::widget([
    'model' => $model,
    'attribute' => 'state_id',
    'states' => $model->getStateOptions()
]);
?>
    </div>
	</div>

</div>