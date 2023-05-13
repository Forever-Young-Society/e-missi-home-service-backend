<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use unclead\multipleinput\MultipleInput;
/* @var $this yii\web\View */
/* @var $model app\modules\service\models\SubCategory */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'sub-category-form',
    'options' => [
        'class' => 'row'
    ]
]);
$model->json_data = $model->getSelectedSubServices();
?>
                  <div class="col-md-6">
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                  </div>
                              <?php /*echo $form->field($model, 'image_file')->fileInput() */ ?>
                              <div class="col-md-6">
                              <?php echo $form->field($model, 'category_id')->dropDownList($model->getCategoryOptions(), ['prompt' => '']) ?>
</div>
	<div class="col-md-6">
                  <?php echo $form->field($model, 'price')->textInput(['maxlength' => 16]) ?>
                  </div>
	<div class="col-md-6">
                  <?php echo $form->field($model, 'provider_price')->textInput(['maxlength' => 16]) ?>
                  </div>
	<div class="col-md-6">
                  <?php echo $form->field($model, 'combination_count')->textInput(['maxlength' => 16]) ?>
                  </div>
	<div class="col-md-6">
                            <?=$form->field($model, 'json_data')->widget(MultipleInput::className(), ['max' => 5,'columns' => [['name' => 'sub_service','title' => 'Sub Category','enableError' => true,'options' => ['placeholder' => 'Title....','type' => 'text']]]])->label(false);?>
							</div>
                              <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
                        <?php if(User::isAdmin()){?>      <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
      <?php }?>            <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'sub-category-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>