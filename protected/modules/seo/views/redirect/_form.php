<?php
   use yii\helpers\Html;
   use app\components\TActiveForm;
   
   /* @var $this yii\web\View */
   /* @var $model app\modules\seo\models\Redirect */
   /* @var $form yii\widgets\ActiveForm */
   ?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php
      $form = TActiveForm::begin([
        
          'id' => 'redirect-form',
          'options'=>[
            'class'=>'row'
          ]
      ]);
      
      //echo $form->errorSummary($model);
      ?>
      <div class="col-md-6 offset-md-3">
   <?php echo $form->field($model, 'old_url')->textInput(['maxlength' => 255, 'placeholder' => "/blog/category/14/life-at-toxsl"]) ?>
   <?php echo $form->field($model, 'new_url')->textInput(['maxlength' => 255, 'placeholder' => "/blog/"]) ?>
   <div class="form-group text-center">
     <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'redirect-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      </div>
  </div>
 
   <?php TActiveForm::end(); ?>
</div>