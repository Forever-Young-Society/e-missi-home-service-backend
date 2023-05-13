<?php
use app\components\useraction\UserAction;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\notification\models\Notification */

/* $this->title = $model->label() .' : ' . $model->title; */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Notifications'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="card">
        <div class="notification-view">
			<?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
        </div>
	</div>

	<div class="card">
		<div class="card-body">
    <?php
    
    echo \app\components\TDetailView::widget([
        'id' => 'notification-detail-view',
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered'
        ],
        'attributes' => [
            'id',
            'title',
            /*'description:html',*/
            /* 'model_id',
            'model_type', */
            [
                'attribute' => 'is_read',
                'format' => 'raw',
                'value' => $model->getIsReadBadge()
            ],
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'value' => $model->getStateBadge()
            ],
            // [
            // 'attribute' => 'type_id',
            // 'value' => $model->getType()
            // ],
            'created_on:datetime',
            [
                'attribute' => 'to_user_id',
                'value' => function ($model) {
                $model->getRelatedDataLink('to_user_id');
                }
            ],
            [
                'attribute' => 'created_by_id',
                'value' => function ($model) {
                $model->getRelatedDataLink('created_by_id');
                }
            ]
        ]
    ])?>


<?php  echo $model->description;?>


		<?php
echo UserAction::widget([
    'model' => $model,
    'attribute' => 'state_id',
    'states' => $model->getStateOptions(),
    'visible'=> User::isAdmin()
]);
?>

		</div>
	</div>
</div>
