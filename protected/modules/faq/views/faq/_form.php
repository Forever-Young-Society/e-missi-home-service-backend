<?php
use yii\helpers\Html;
use app\components\TActiveForm;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\faq\models\Faq */
/* @var $form yii\widgets\ActiveForm */
?>
<header class="card-header">
   <?php

echo strtoupper(Yii::$app->controller->action->id);
?>
</header>
<div class="card-body">
   <?php

$form = TActiveForm::begin([

    'id' => 'faq-form',
    'options' => [
        'class' => 'row'
    ]
]);
?>	<div class="col-md-12">

                  <?php

                echo $form->field($model, 'question')->textInput([
                    'maxlength' => 256
                ])?>
            </div>
	

	<div class="col-md-6">

                    <?php

                    //echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions())?>
                    </div>

                    <div class="col-md-12">
                             <?php

                            echo $form->field($model, 'answer')->widget(app\components\TRichTextEditor::className(), [
                                'options' => [
                                    'rows' => 6
                                ],
                                'preset' => 'basic'
                            ]); // $form->field($model, 'description')->textarea(['rows' => 6]); */ ?>
                    </div>
                      <?php

                    if (User::isAdmin()) {
                        ?>      <?php
                        /* echo $form->field($model, 'state_id')->dropDownList($model->getStateOptions(), ['prompt' => '']) */
                        ?>
      <?php
                    }
                    ?>                        <?php
                    /* echo $form->field($model, 'type_id')->dropDownList($model->getTypeOptions(), ['prompt' => '']) */
                    ?>
                  <div class="col-md-12 text-right">
      <?=Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'faq-form-submit','class' => $model->isNewRecord ? 'btn btn-success main-btn' : 'btn btn-primary'])?>
   </div>
   <?php

TActiveForm::end();
?>
</div>