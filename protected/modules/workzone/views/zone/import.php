<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Location Import'),
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Import');
?>

<div class="wrapper">
	<div class="card">
		<div class="resume-import page-head">
			<h1>Upload file</h1>
		</div>
	</div>
	<div class="content-section clearfix card">
		<div class="card-body">
			<?php
$form = TActiveForm::begin([
    'id' => 'import-form',
    'options' => [
        'class' => 'row',
        'enctype' => 'multipart/form-data'
    ]
]);
?>
            <div class="col-md-6 offset-md-3">
                <?=$form->field($model, 'name')->fileInput()->label('File')?>
                <?=Html::submitButton ( 'Import', [ 'class' => 'btn  btn-success','name' =>'Import button'] )?>
            </div>
			<?php TActiveForm::end ()?>
		</div>
	</div>
</div>
