<?php
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

/*
 * $this->title = Yii::t('app', 'Update {modelClass}: ', [
 * 'modelClass' => 'User',
 * ]) . ' ' . $model->id;
 */
if ($model->role_id == User::ROLE_SERVICE_PROVIDER) {
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'Service Provider'),
        'url' => [
            'provider'
        ]
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $model->id,
        'url' => [
            'view',
            'id' => $model->id
        ]
    ];
} else {
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'User'),
        'url' => [
            'index'
        ]
    ];
    $this->params['breadcrumbs'][] = [
        'label' => $model->id,
        'url' => [
            'view',
            'id' => $model->id
        ]
    ];
}
if (empty($qualifications)) {
    $qualifications = '';
}
if (empty($skills)) {
    $skills = '';
}
if (empty($languages)) {
    $languages = '';
}
if (empty($workzones)) {
    $workzones = '';
}
if (empty($fileModel)) {
    $fileModel = '';
}

$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$action = Yii::$app->controller->action->id;
$form = '_form';
if ($action == 'update-provider') {
    $form = '_providerForm';
}
?>
<div class="wrapper">
	<div class="user-update card">
        <?=  \app\components\PageHeader::widget(['model' => $model]); ?>
    </div>

	<div class="content-section card">
      <?= $this->render ( $form, [ 'model' => $model,'qualifications'=>$qualifications,'skills'=>$skills,'languages'=>$languages,'workzones'=>$workzones,'fileModel'=>$fileModel] )?></div>
</div>

