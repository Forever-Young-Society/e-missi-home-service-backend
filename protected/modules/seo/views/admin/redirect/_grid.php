<?php
use app\components\grid\TGridView;
use app\components\MassAction;
use yii\helpers\Url;
use app\models\User;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\seo\models\search\Redirect $searchModel
 */

?>
<?php
if (User::isAdmin()) {
    echo MassAction::widget([
        'url' => Url::toRoute([
            'admin/redirect/mass'
        ]),
        'grid_id' => 'redirect-grid-data',
        'pjax_grid_id' => 'redirect-pjax-grid'
    ]);
}
?>
<?php Pjax::begin(['id'=>'redirect-pjax-grid']); ?>
    <?php
    
echo TGridView::widget([
        'id' => 'redirect-grid-data',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
            [
                'name' => 'check',
                'class' => 'yii\grid\CheckboxColumn',
                'visible' => User::isAdmin()
            ],
            
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('id');
                }
            ],
            'old_url:url',
            'new_url:url',
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            'created_on:datetime',
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
<?php Pjax::end(); ?>
<script> 
$('#bulk_delete_redirect-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#redirect-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['admin/redirect/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#redirect-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

