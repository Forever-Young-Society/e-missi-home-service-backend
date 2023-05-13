<?php
use app\components\TActiveForm;
use yii\helpers\Html;
use app\components\World;

/* @var $this yii\web\View */
/* @var $model app\modules\contact\models\Phone */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php
$form = TActiveForm::begin([
    'id' => 'contact-form',
    'layout' => TActiveForm::LAYOUT_HORIZONTAL,
    'options' => [
        'class' => 'row'
    ]
]);
?>
      <div class="col-md-6">
         <?php echo $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
   <?php echo $form->field($model, 'contact_no')->textInput(['maxlength' => 255]) ?>
   <?php echo $form->field($model, 'country')->dropDownList(World::countries()) ?>
   <?php echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions()) ?>
   <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions()) ?>
      </div>
	<div class="col-md-6">
	<?php echo $form->field($model, 'type_chat')->textInput(['maxlength' => 255]) ?>
   <?php echo $form->field($model, 'skype_chat')->textInput(['maxlength' => 255]) ?>
    <?php echo $form->field($model, 'gtalk_chat')->textInput(['maxlength' => 255]) ?>
    <?php echo $form->field($model, 'whatsapp_enable')->checkbox() ?>
    <?php echo $form->field($model, 'telegram_enable')->checkbox() ?>
    <?php echo $form->field($model, 'toll_free_enable')->checkbox() ?>
      </div>

	<div class="col-md-12 text-right">
         <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'contact-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      </div>
 
   <?php TActiveForm::end(); ?>
</div>
