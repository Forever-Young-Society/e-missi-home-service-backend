<?php
use app\components\useraction\UserAction;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\modules\faq\models\Faq */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', "FAQ's"),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>
<div class="wrapper">
	<div class="container-fluid">
		<div class="block-header">
			<div class="row">
				<div class="col-lg-12 col-md-8 col-sm-12">
<?php

echo \app\components\PageHeader::widget([
    'model' => $model
]);
?>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a
							href="<?=Url::toRoute(['/faq/faq/index'])?>"><i
								class="icon-home"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
         <?php

        echo \app\components\TDetailView::widget([
            'id' => 'faq-detail-view',
            'model' => $model,
            'options' => [
                'class' => 'table table-bordered'
            ],
            'attributes' => [
                'id',
                [
                    'attribute' => 'question',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('question')
                ],

                [
                    'attribute' => 'answer',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('answer')
                ],
                [
                    'attribute' => 'state_id',
                    'format' => 'raw',
                    'value' => $model->getStateBadge()
                ],
         
                [
                    'attribute' => 'created_on',
                    'format' => 'raw',
                    'value' => $model->created_on
                ],
                [
                    'attribute' => 'created_by_id',
                    'format' => 'raw',
                    'value' => $model->getRelatedDataLink('created_by_id')
                ]
            ]
        ])?>

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