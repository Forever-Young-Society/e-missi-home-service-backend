<?php
   use yii\helpers\Html;
   use app\components\TActiveForm;
   use app\models\User;
   /* @var $this yii\web\View */
   /* @var $model app\models\Feed */
   /* @var $form yii\widgets\ActiveForm */
   ?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php    $form = TActiveForm::begin([
      'id' => 'feed-form',
      'options'=>[
      'class'=>'row'
      ]
      ]);?>
   <div class="col-md-12">
      <?php
         echo $form->errorSummary($model);    
         ?>
   </div>
   <div class="col-md-6 offset-md-3">
      <?php /*echo  $form->field($model, 'content')->widget ( app\components\TRichTextEditor::className (), [ 'options' => [ 'rows' => 6 ],'preset' => 'basic' ] ); //$form->field($model, 'content')->textarea(['rows' => 6]); */ ?>
      <?php if(User::isAdmin()){?>      <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) ?>
      <?php }?>                        <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
      <?php echo $form->field($model, 'model_type')->textInput(['maxlength' => 128]) ?>
      <?php echo $form->field($model, 'model_id')->dropDownList($model->getModelOptions(), ['prompt' => '']) ?>
      <div class="text-center">
         <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'feed-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      </div>
   </div>
   <?php TActiveForm::end(); ?>
</div>