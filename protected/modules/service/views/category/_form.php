<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\service\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php echo strtoupper(Yii::$app->controller->action->id); ?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'category-form'
]);
?>
                 <div class="row align-items-center">
		<div class="col-lg-5">
                    <?php echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => ''])->label('Title')  ?>
                    </div>
		<div class="col-lg-5">
                  <?php

                echo $form->field($model, 'image_file', [
                    'enableAjaxValidation' => false
                ])->fileInput([
                    'onchange' => 'ValidateSingleInput(this)'
                ])?>
                        <?php if(User::isAdmin()){?>      <?php /*echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */ ?>
      <?php }?>   
      </div>
                                   <?php /*echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */ ?>
                  <div class="col-lg-2 text-left">

      <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id'=> 'category-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
   </div>
   <?php TActiveForm::end(); ?>
</div>
</div>
<script>

var _validFileExtensions = [".jpg", ".jpeg", ".gif", ".png"];
function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }

            if (!blnValid) {
              alert("<?=Yii::t('app', 'Sorry,')?> " + sFileName + "<?=Yii::t('app', 'is invalid, allowed extensions are:')?>  " + _validFileExtensions.join(", "));
                oInput.value = "";
                 $('#user-form').yiiActiveForm('validateAttribute', 'user-profile_file')
                return false;
            }
        }
    }
    return true;
}

</script>
