<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\modules\seo\models\Analytics */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php
    $form = TActiveForm::begin([
        'layout' => TActiveForm::LAYOUT_HORIZONTAL,
        'id' => 'analytics-form',
        'options' => [
            'class' => 'row'
        ]
    ]);

    // echo $form->errorSummary($model);
    ?>
    <div class="col-md-6 offset-md-3">
         <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) ?>
            <?php echo $form->field($model, 'account')->textInput(['maxlength' => 512]) ?>
         <?php echo $form->field($model, 'domain_name')->textInput(['maxlength' => 512]) ?>
         <?php echo $form->field($model, 'role_id')->dropDownList(User::getRoleOptions()) ?>
         <div class="form-group text-center">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'analytics-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	</div>


       
  

    <?php TActiveForm::end(); ?>

</div>
