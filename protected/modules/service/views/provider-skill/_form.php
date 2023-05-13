<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
use unclead\multipleinput\MultipleInput;
use app\modules\service\models\ProviderSkill;
/* @var $this yii\web\View */
/* @var $model app\modules\service\models\ProviderSkill */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'provider-skill-form'
]);
$model->json_data = $model->getSelectedSubSkills();
?>
<div class="row">
		<div class="col-md-6">
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                  </div>
		<div class="col-md-6">
                              <?php echo $form->field($model, 'category_id')->dropDownList($model->getCategoryOptions(), ['prompt' => '']) ?>
</div>
		<div class="col-md-12">
                            <?=$form->field($model, 'json_data')->widget(MultipleInput::className(), ['max' => 20,'columns' => [['name' => 'sub_skill','title' => 'Sub Skills','enableError' => true,'options' => ['placeholder' => 'Title....','type' => 'text']]]])->label(false);?>
							</div>

	</div>
                        <?php if(User::isAdmin()){?>      <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
      <?php }?>            <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'sub-category-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>