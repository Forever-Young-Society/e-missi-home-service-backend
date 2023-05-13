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
                    echo $form->field($model, 'enableScheduler')
                        ->checkbox([
                            'id' => 'customSwitchScheduler',
                            'class' => 'custom-control-input',
                            'onchange' => 'this.form.submit()'
                        ])
                        ->label(true, [
                            'class' => 'custom-control-label',
                            'for' => 'customSwitchScheduler'
                        ]);
                    ?>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-2 custom-control custom-switch">

                    <?php
                    echo $form->field($model, 'runAsap')
                        ->checkbox([
                            'id' => 'customSwitchScheduler1',
                            'class' => 'custom-control-input',
                            'onchange' => 'this.form.submit()'
                        ])
                        ->label(true, [
                            'class' => 'custom-control-label',
                            'for' => 'customSwitchScheduler1'
                        ]);
                    ?>
                </div>
            </div>

            <?php TActiveForm::end(); ?>

            <!-- settings -->

        </div>
    </div>
</div>