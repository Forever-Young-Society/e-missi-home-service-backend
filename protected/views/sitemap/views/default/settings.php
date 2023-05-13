<?php

use app\components\TActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\scheduler\models\SettingsForm $model */
/** @var TActiveForm $form */
?>
<div class="wrapper">

    <div class="card">
        <header class="card-header">
            <?php echo ucfirst(Yii::$app->controller->module->id); ?>
        </header>
        <div class="card-body">
            <?php

            $form = TActiveForm::begin([
                'id' => 'setting-form',
                // 'layout' => TActiveForm::LAYOUT_HORIZONTAL,
                'enableAjaxValidation' => false,
                'enableClientValidation' => false
            ]);
            ?>
            <div class="row justify-content-center">
                <div class="col-md-2 custom-control custom-switch">
                    <?php
                    echo $form->field($model, 'enable')
                        ->checkbox([
                            'id' => $model->getFormAttributeId('enable'),
                            'class' => 'custom-control-input',
                            'onchange' => 'this.form.submit()'
                        ])
                        ->label(true, [
                            'class' => 'custom-control-label'
                        ]);
                    ?>
                </div>
            </div>

        </div>
        <?php TActiveForm::end(); ?>

        <!-- settings -->

    </div>
</div>