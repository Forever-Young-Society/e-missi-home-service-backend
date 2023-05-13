<?php
use app\components\TActiveForm;
use app\components\grid\TGridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\modules\settings\models\search\Variable $searchModel
 */

?>
<div class="card">
<?php
$form = TActiveForm::begin([
    'id' => 'setting-form'
]);
?>
<div class="row">
		<div class="col-md-3 mb-4">

        	<?=$form->field($model, 'module')->dropDownList($model->getModuleList());?>
        	
   
    </div>
		<div class="col-md-3">
        <?php
        echo $form->field($model, 'key')->textInput();
        ?>
    </div>
		<div class="col-md-3">
        <?php
        echo $form->field($model, 'value')->textInput();
        ?>
	</div>
		<div class="col-md-3 mt-4 text-center">
    	<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'setting-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>
<?php TActiveForm::end()?>
</div>
<?php Pjax::begin(['id'=>'variable-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'student-grid-view',
        'enableRowClick' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // 'id',
            [
                'attribute' => 'module',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getModuleList() : null,
                'value' => function ($data) {
                    return $data->module;
                }
            ],
            'key',
            'value',
            'updated_on:datetime',

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>',
                'template' => '{delete} {update}'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>