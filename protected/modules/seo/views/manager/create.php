<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\seo\manager\models\Seomanager */

$this->title = Yii::t('app', 'Create Seomanager');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Seomanagers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seomanager-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
