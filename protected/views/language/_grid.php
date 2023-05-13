<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
use app\components\TActiveForm;
use app\models\Language;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\Language $searchModel
 */

?>
<?php
$model = new Language();
$form = TActiveForm::begin([
    'id' => 'language-form',
    'action' => Url::to([
        'language/add'
    ])
]);
?>
<div class="row">
	<div class="col-md-4">
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 128]) ?>                 
                  </div>
	<div class="col-md-1 text-md-left text-right mt-4">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'language-form-submit ','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>
<?php Pjax::begin(['id'=>'language-pjax-grid']); ?>
    <?php

    echo TGridView::widget([
        'id' => 'language-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '<a>S.No.<a/>'
            ],

            // 'id',
            'title',
            // 'code',
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            'created_on:datetime',
            /* ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],*/

            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>