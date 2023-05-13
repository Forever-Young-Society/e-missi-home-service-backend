<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use app\components\TActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Cronjobs'),
    'url' => [
        'index'
    ]
];

$this->params['breadcrumbs'][] = Yii::t('app', 'Import');
?>

<div class="wrapper">
	<div class="email-account-import card">
        <?=  \app\components\PageHeader::widget(['title' => 'Import Cronjobs']); ?>
    </div>
	<div class="content-section card">
		<div class="card-body">
			<?php
$form = TActiveForm::begin([
    'id' => 'import-form',
    'options' => [
        'class' => 'import-form row',
        'enctype' => 'multipart/form-data'
    ]
]);
?>
                <div class="col-md-6 offset-md-3">
                    <?php echo $form->field($import, 'file',['enableAjaxValidation'=>false,'enableClientValidation'=>true])->fileInput()?>
                        <?=Html::submitButton ( 'Import', [ 'class' => 'btn btn-success','name' =>'Import button'] )?>
                </div>
            <?php TActiveForm::end ()?>
		</div>

	</div>

</div>

