<?php
use app\components\TActiveForm;
use yii\helpers\Html;
?>
<div class="bg-bottom">
	<div >
		<p class="line_h mb-10">
		<?= $description?></p>
	    <?php
    $form = TActiveForm::begin([
        'id' => 'cookies-actions-form'
    ]);
    echo Html::submitButton('Agree', array(
        'name' => 'accept',
        'value' => 'Accept',
        'class' => 'btn btn-success btn-sm ml-0 ml-sm-3 p-1 px-sm-3 py-sm-1',
        'id' => 'information-form-submit'
    ));
    TActiveForm::end();
    ?>
   </div>
</div>
