<?php
use yii\helpers\Html;
use yii\helpers\Url;

use app\models\User;

use yii\widgets\Pjax;
use app\components\grid\TGridView;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\sms\models\search\History $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_history-grid"])?>
<?php Pjax::begin(['id'=>'history-pjax-grid']); ?>
<div class="table-responsive">
    <?php

    echo TGridView::widget([
        'id' => 'history-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
            [
                'name' => 'check',
                'class' => 'yii\grid\CheckboxColumn',
                'visible' => User::isAdmin()
            ],

            'id',
            'from',
            'to',
            'text',
            [
                'attribute' => 'gateway_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getGateway();
                }
            ],
            /* 'sms_detail',*/
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            [
                'attribute' => 'created_on',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->created_on ? Yii::$app->formatter->asDate($data->created_on) : '';
                }
            ],
            /* 'updated_on:datetime',*/
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                }
            ],

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
    </div>
<?php Pjax::end(); ?>
<script> 
$('#bulk_delete_history-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#history-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['history/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#history-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

