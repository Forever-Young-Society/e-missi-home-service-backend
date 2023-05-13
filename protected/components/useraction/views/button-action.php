<?php
use app\components\TActiveForm;
use yii\helpers\Html;
?>
<div class="clearfix"></div>
<div class="card">
	<div class="card-header">
		<h3>User Actions</h3>
	</div>
	<div class="user-actions-view card-body">
		<div class="form">

    <?php $form = TActiveForm::begin(['id' => 'user-actions-form',]); ?>
		<?= $title?>
		<div class="btn-group pull-right">


	<?php

foreach ($allowed as $id => $act) {

    if ($id != $model->{$attribute}) {
        $button = $buttons[$id];
        echo '';
        echo Html::submitButton($button, array(
            'name' => 'workflow',
            'value' => $id,
            'class' => 'btn ' . $this->context->getButtonColor($button)
        ));
        echo '';
    }
}

?>
	
	</div>
<?php TActiveForm::end(); ?>
</div>
	</div>
</div>