<?php
use app\components\TActiveForm;
use app\models\User;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use app\modules\service\models\ProviderSkill;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $images = $model->getProviderFiles();?>
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

$skillList = [];
$skillList = ArrayHelper::Map(ProviderSkill::find()->where([
    'category_id' => ! empty($model->qualificationDetail) ? $model->qualificationDetail->category_id : ProviderSkill::STATE_ACTIVE,
    'type_id' => ProviderSkill::TYPE_SUB_SKILL_YES
])->each(), 'id', 'title');
?>
<header class="card-header">
    <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
<div class="card-body">

    <?php
    $form = TActiveForm::begin([
        'id' => 'user-form',
        'options' => [
            'class' => 'row'
        ]
    ]);
    ?>

<div class="col-lg-6">
			
		 <?php echo $form->field($model, 'full_name')->textInput(['maxlength' => 256]) ?>
		 <?php echo $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
		 <?php echo $form->field($model, 'contact_no')->textInput(['maxlength' => 32,'readOnly'=>true]) ?>
		 <?php echo $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>
		 <?php echo $form->field($model, 'identity_number')->textInput(['maxlength' => 14]) ?>
		 <?php
echo $form->field($model, 'date_of_birth')->widget(yii\jui\DatePicker::class, [
    // 'dateFormat' => 'php:Y-m-d',
    'options' => [
        'class' => 'form-control',
        'maxlength' => 10
    ],
    'clientOptions' => [
        'maxDate' => date("Y-m-d"),
        'changeMonth' => true,
        'changeYear' => true,
        'yearRange' => '1940:' . date("Y")
    ]
]);
?>
		 <?php echo $form->field($model, 'experience')->textInput(['maxlength' => 32]) ?>
		 
</div>
	<div class="col-lg-6">
 		<?php

echo $form->field($model, 'profile_file')->fileInput([
    'onchange' => 'ValidateSingleInput(this)'
])?>
 		<?php echo $form->field($model, 'gender')->dropDownList($model->getGenderOptions()) ?>
		<?php
$model->qualification = $qualifications;
echo $form->field($model, 'qualification')
    ->widget(Select2::class, [
    'data' => $model->getQlaificationOptions(),
    'size' => Select2::MEDIUM,
    'maintainOrder' => true,
    'theme' => Select2::THEME_BOOTSTRAP,
    'options' => [
        'id' => 'user-qualification_id',
        'placeholder' => 'Select...',
        'multiple' => false
    ],
    'pluginOptions' => [
        'tags' => true,
        'maximumInputLength' => 10
    ]
])
    ->label('Choose Qualifications ');
$model->skill = $skills;
echo $form->field($model, 'skill')
    ->widget(Select2::class, [
    'data' => $skillList,
    'size' => Select2::MEDIUM,
    'theme' => Select2::THEME_BOOTSTRAP,
    'maintainOrder' => true,
    'options' => [
        'id' => 'user-skill_id',
        'placeholder' => 'Select...',
        'multiple' => true
    ],
    'pluginOptions' => [
        'tags' => true,
        'maximumInputLength' => 10
    ]
])
    ->label('Choose Skills');
$model->service_language = $languages;
echo $form->field($model, 'service_language')
    ->widget(Select2::class, [
    'data' => $model->getLanguageOptions(),
    'size' => Select2::MEDIUM,
    'theme' => Select2::THEME_BOOTSTRAP,
    'maintainOrder' => true,
    'options' => [
        'placeholder' => 'Select...',
        'multiple' => true
    ],
    'pluginOptions' => [
        'tags' => true,
        'maximumInputLength' => 10
    ]
])
    ->label('Choose Languages');
$model->work_zone = $workzones;
echo $form->field($model, 'work_zone')
    ->widget(Select2::class, [
    'data' => $model->getWorkzoneOptions(),
    'size' => Select2::MEDIUM,
    'theme' => Select2::THEME_BOOTSTRAP,
    'maintainOrder' => true,
    'options' => [
        'placeholder' => 'Select...',
        'multiple' => false
    ],
    'pluginOptions' => [
        'tags' => true,
        'maximumInputLength' => 10
    ]
])
    ->label('Choose Workzone');

?>
	 			</div>

	<div class="col-md-12">
		<div class="form-group upload-img-wrapper">
			<label>Upload Document</label>

								<?php
        echo $form->field($fileModel, 'key[]', [

            'enableClientValidation' => false
        ])
            ->widget(FileInput::classname(), [
            'options' => [
                'multiple' => true,
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
        ?>
						</div>
	</div>

	<div class="form-group col-lg-12 text-right">
	
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['id' => 'user-form-submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	

    <?php TActiveForm::end(); ?>

</div>
<script>
$("input[name='User[identity_number]']").keyup(function() {
    $(this).val($(this).val().replace(/^(\d{6})(\d{2})(\d)+$/, "$1-$2-$3"));
});
$("input[name='User[date_of_birth]']").keyup(function() {
    $(this).val($(this).val().replace(/^(\d{4})(\d{2})(\d)+$/, "$1-$2-$3"));
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

$("#user-qualification_id").change(function(){
	 var qualification_id = $(this).val();
      $.ajax({
          url: "<?=Url::toRoute(['/user/qualification-skills'])?>"+'/'+qualification_id,
          async: false,
           data:{
               id:qualification_id
               },
           success: function(result){
              
           	var $el = 	$('#user-skill_id');
           	var prevValue = $el.val();
           	$el.empty();
          if(result.status == 'OK'){
           
           	$.each(result.data, function(key, value) {
           	   $el.append($('<option></option>').attr('value', key).text(value));
           	   if (value === prevValue){
           	       $el.val(value);
           	   }
           	});
        		}
           }
      })
    
	});

</script>
