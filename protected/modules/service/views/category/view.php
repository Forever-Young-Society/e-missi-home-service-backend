<?php
use app\components\useraction\UserAction;
use app\modules\comment\widgets\CommentsWidget;
use yii\helpers\Html;
use app\modules\service\models\Category;
/* @var $this yii\web\View */
/* @var $model app\modules\service\models\Category */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Qualifications'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="card">
		<div class="category-view">
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
                    'class' => 'img-responsive mt-5',
                    'alt' => $model,
                    'width' => '150',
                    'height' => '150'
                ])?><br /> <br />

            <?php }?>
         </div>
				<div class="col-md-10">     
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'category-detail-view',
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                // 'image_file',
                [
                    'attribute' => 'state_id',
                    'format' => 'raw',
                    'value' => $model->getStateBadge()
                ],

                'created_on:datetime',
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
            ]
        ])?>
        </div>
			</div>
		</div>
	</div>
		
         <?php

        echo UserAction::widget([
            'model' => $model,
            'attribute' => 'state_id',
            'states' => $model->getStateOptions()
        ]);
        ?>
       

	<div class=" card ">
		<div class=" card-body ">
			<div class="rating-panel">
<?php
$this->context->startPanel();
$this->context->addPanel('Services', 'subCategories', 'SubCategory', $model);
$this->context->addPanel('Provider Skills', 'providerSkills', 'ProviderSkill', $model /* ,null,true */);
if ($model->type_id == Category::TYPE_NURSING_SERVICE) {
    $this->context->addPanel('Direct Booking Service', 'directBookService', 'SubCategory', $model /* ,null,true */);
}
$this->context->endPanel();
?>
				</div>
		</div>
	</div>

</div>
