<?php
use app\components\useraction\UserAction;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Slider */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Sliders'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="slider-view">
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-2">
            <?php

            if (! empty($model->image_file)) {
                ?>
            <?php
                echo Html::img($model->getImageUrl(150), [
                    'class' => 'img-responsive',
                    'alt' => $model,
                    'width' => '150',
                    'height' => '150'
                ])?><br /> <br />
            <?php }?>
         </div>
				<div class="col-md-10">     
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'slider-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
            /*'title',*/
            /*'description:html',*/
            'image_file',
            /*[
			'attribute' => 'state_id',
			'format'=>'raw',
			'value' => $model->getStateBadge(),],*/
            'created_on:datetime',
                [
                    'attribute' => 'type_id',
                    'value' => $model->getType()
                ],
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
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
	</div>
</div>
