<?php
use app\components\TActiveForm;
use yii\helpers\Html;
use app\components\PageHeader;

// $this->title = 'Change Password';
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Users'),
    'url' => [
        '/user'
    ]
];
$this->params['breadcrumbs'][] = \yii\helpers\Inflector::camel2words(Yii::$app->controller->action->id);
?>

<div class="wrapper">
	<div class="user-create">
		<div class="card">
    	<?=  PageHeader::widget(); ?>
    </div>
	</div>
	<div class="content-section clearfix card">
		<header class="card-header">
                            <?php echo strtoupper(Yii::$app->controller->action->id); ?>
                        </header>
		<div class="card-body">

    <?php
    $form = TActiveForm::begin([
        'layout' => 'horizontal',
        'id' => 'user-form'
    ]);
    ?>

	<div class="form-group">
				&nbsp;

				<div class="card-body interview-div">
					<div class="alert alert-warning text-center text-danger">
						<h3>Category & Its all Related data will deleted.</h3>
					</div>

				</div>
				<div class="col-md-12 mt-2 text-center">
        <?= Html::submitButton(Yii::t('app', 'Delete'), ['id'=> 'profile-form-submit','class' => 'btn btn-success']) ?>
    </div>
			</div>

    <?php TActiveForm::end(); ?>

</div>
	</div>
</div>