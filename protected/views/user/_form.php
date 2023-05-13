<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use lesha724\documentviewer\ViewerJsDocumentViewer;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $images = $model->getUserFiles();?>
			<?php if (!$model->isNewRecord) { ?>
				<?php
    $configArray = [];
    $image = [];
    foreach ($images->each() as $img) {
        $configArray[] = [
            'url' => Url::toRoute([
                'user/delete-qualification-image',
                'id' => $img->id
            ])
        ];
        $extenssion = pathinfo($img->key, PATHINFO_EXTENSION);
        if ($extenssion == 'pdf') {
            $image[] = ! empty($img->name) ? $img->name : '';
        } else {
            $image[] = Html::img($img->getImageUrl(), [
                'class' => 'custom-file-uploads',
                'width' => 200,
                'height' => 200
            ]);
        }
    }
} else {
    $image = '';
    $configArray = [];
    $image = [];
}
?>
<header class="card-header">
    <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php
    $action = \Yii::$app->controller->action->id;
    $form = TActiveForm::begin([
        'id' => 'user-form',
        'options' => [
            'class' => 'row align-items-end '
        ]
    ]);
    ?>

<div class="col-lg-10">
		<div class="row">
			<div class="col-lg-6">
			
		 <?php echo $form->field($model, 'full_name')->textInput(['maxlength' => 128]) ?>

		 <?php echo $form->field($model, 'email')->textInput(['maxlength' => 64]) ?>

		 <?php if($action == 'add'){?>
		 <?php echo $form->field($model, 'password')->passwordInput(['maxlength' => 128]) ?>
		 <?php }?>
		 <?php if ($action == 'update-user'){?>
		  <?php echo $form->field($model, 'identity_number')->textInput(['maxlength' => 14]) ?>
		 <?php
    echo $form->field($model, 'date_of_birth')->widget(yii\jui\DatePicker::class, [
        // 'dateFormat' => 'php:Y-m-d',
        'options' => [
            'class' => 'form-control'
        ],
        'clientOptions' => [
            'maxDate' => date("Y-m-d"),
            'changeMonth' => true,
            'changeYear' => true
        ]
    ]);
    ?>
		 <?php }?>
</div>
			<div class="col-lg-6">
 		<?php

echo $form->field($model, 'profile_file')->fileInput([
    'onchange' => 'ValidateSingleInput(this)'
])?>
		 
		 <?php if ($action == 'update-user'){?>
	 		<?php echo $form->field($model, 'contact_no')->textInput(['maxlength' => 32,'readOnly'=>true]) ?>
	 		<?php echo $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>
		<?php echo $form->field($model, 'zipcode')->textInput(['maxlength' => 8]) ?>
		    
		<?php }?>
	 			</div>
			<div class="col-lg-12">
	 			
	 			<?php

    if ($action == 'update-user') {
        echo $form->field($model, 'about_me')->textarea([
            'rows' => 6
        ]);
        ?>
		<div class="form-group upload-img-wrapper">
					<label>Upload Document</label>

								<?php
        echo $form->field($fileModel, 'key[]', [

            'enableClientValidation' => false
        ])
            ->widget(FileInput::classname(), [
            'options' => [
                'multiple' => true,
                'accept' => 'image/*',
                'accept' => "image/*,.pdf",
                'id' => 'chooseFile' . $model->id
            ],
            'pluginOptions' => [
                'maxFileCount' => 10,
                'initialPreview' => $image,
                'allowedFileExtensions' => [
                    'jpg',
                    'png',
                    'jpeg',
                    'pdf'
                ],
                'showUpload' => false,
                'showCancel' => false,
                'showRemove' => false,
                'initialPreviewConfig' => $configArray,
                'validateInitialCount' => true,
                'overwriteInitial' => false
            ]
        ])
            ->label(false);
    }
    ?>
						</div>
			</div>
		</div>
	</div>
	<div class="form-group col-lg-2 text-right">
	
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'user-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	

    <?php TActiveForm::end(); ?>

</div>
<script>
$("input[name='User[identity_number]']").keyup(function() {
    $(this).val($(this).val().replace(/^(\d{6})(\d{2})(\d)+$/, "$1-$2-$3"));
});


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

