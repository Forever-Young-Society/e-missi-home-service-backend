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
            'created_on:datetime',
            [
                'attribute' => 'to_user_id',
                'value' => function ($model) {
                    if (empty($model->to_user_id)) {
                        return "Not Set";
                    } else {
                        return isset($model->toUser) ? $model->toUser->full_name : "Not Set";
                    }
                }
            ],
            [
                'attribute' => 'created_by_id',
                'value' => function ($model) {
                    if (empty($model->created_by_id)) {
                        return "Not Set";
                    } else {
                        return isset($model->createdBy) ? $model->createdBy->full_name : "Not Set";
                    }
                }
            ]
        ]
    ])?>


<?php  echo $model->description;?>

		</div>
	</div>
</div>
