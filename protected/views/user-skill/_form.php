<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\UserSkill */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'user-skill-form'
]);
echo $form->errorSummary($model);
?>
                  <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
                              <?php echo $form->field($model, 'category_id')->dropDownList($model->getCategoryOptions(), ['prompt' => '']) ?>
                              <?php echo $form->field($model, 'skill_id')->dropDownList($model->getSkillOptions(), ['prompt' => '']) ?>
                              <?php /*echo $form->field($model, 'parent_skill_id')->dropDownList($model->getParentSkillOptions(), ['prompt' => '']) */ ?>
                              <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
                        <?php if(User::isAdmin()){?>      <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
      <?php }?>            <div class="col-md-12 text-right">
      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'user-skill-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>