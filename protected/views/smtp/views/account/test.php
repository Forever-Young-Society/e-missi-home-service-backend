<?php
use app\components\TActiveForm;

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Test'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = (string) $model;
?>

<div class="wrapper">
	<div class="email-import card"> 
<?php

echo \app\components\PageHeader::widget([
    'model' => $model
]);
?>
</div>


	<div class="content-section card ">
        <div class="card-body">
			<?php
$form = TActiveForm::begin([
    'id' => 'reply-form'
]);
?>
			<div class="col-md-6">
                     <?php echo $form->field ( $test, 'email' )->textInput ()?>
                    <?php
                    
                    echo \yii\helpers\Html::submitButton('Send', [
                        'id' => 'reply-form-submit',
                        'class' => 'btn btn-danger submit-btn',
                        'name' => 'submit-button'
                    ])?>
 </div>
                    <?php TActiveForm::end (); ?>
		</div>
        <?php echo $form->errorSummary($model);?>
    </div>
</div>
