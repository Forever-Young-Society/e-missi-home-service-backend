<?php
use app\components\grid\TGridView;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\StringHelper;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\comment\models\search\Comment $searchModel
 */

?>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_comment-grid"])?>
<?php Pjax::begin(['id'=>'comment-pjax-grid','enablePushState'=>false,'enableReplaceState'=>false]); ?>
    <?php

    echo TGridView::widget([
        'id' => 'comment-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            [
                'attribute' => 'model_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getModel() ? $data->getModel()->linkify() : '';
                }
            ],
            [
                'attribute' => 'model_type',
                'format' => 'raw',
                'value' => function ($data) {
                    return StringHelper::basename($data->model_type);
                }
            ],

            'comment:html',
            'created_on:datetime',
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
<?php Pjax::end(); ?>
<script> 
$('#bulk_delete_comment-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#comment-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['comment/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#comment-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

