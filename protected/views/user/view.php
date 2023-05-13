<?php
// use app\components\useraction\UserAction;
use app\models\User;
use yii\helpers\Html;
use app\components\useraction\UserAction;
use lesha724\documentviewer\ViewerJsDocumentViewer;
use yii\web\View;
use yii\helpers\Url;
use app\models\File;
use app\components\TActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */

/* $this->title = $model->label() .' : ' . $model->id; */

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
?>
<div class="wrapper">
	<div class="card mb-4">
        <?php
        echo \app\components\PageHeader::widget([
            'model' => $model
        ]);
        ?>
    </div>
	<div class="content-section clearfix">
		<div class="widget light-widget">
			<div class="user-view">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-2">
                                <?php

                                if (! empty($model->profile_file) && is_file(UPLOAD_PATH . $model->profile_file)) {
                                    echo Html::img($model->getImageUrl(), [
                                        'class' => 'img-fluid mt-5',
                                        'width' => '150',
                                        'height' => '150'
                                    ]);
                                } elseif ($model->gender == 0) {
                                    echo Html::img($this->theme->getUrl('img/default.jpg'), [
                                        'class' => 'img-fluid mt-5'
                                    ]);
                                } else {
                                    echo Html::img($this->theme->getUrl('img/female.png'), [
                                        'class' => 'img-fluid mt-5'
                                    ]);
                                }
                                ?><br /> <br />
							</div>
							<div class="col-md-10">
                                <?php
                                echo \app\components\TDetailView::widget([
                                    'model' => $model,

                                    'options' => [
                                        'class' => 'table table-bordered'
                                    ],
                                    'attributes' => [
                                        'id',
                                        'full_name',
                                        'email:email',
                                        [
                                            'attribute' => 'contact_no',
                                            'visible' => ($model->role_id != User::ROLE_ADMIN)
                                        ],
                                        [
                                            'attribute' => 'gender',
                                            'value' => $model->getGender(),
                                            'visible' => ($model->role_id != User::ROLE_SERVICE_PROVIDER)
                                        ],
                                        [
                                            'label' => 'Experience(in years)',
                                            'attribute' => 'experience',
                                            'visible' => ($model->role_id == User::ROLE_SERVICE_PROVIDER)
                                        ],
                                        [
                                            'attribute' => 'date_of_birth',
                                            'visible' => ($model->role_id != User::ROLE_ADMIN)
                                        ],
                                        [
                                            'attribute' => 'identity_number',
                                            'visible' => ($model->role_id != User::ROLE_ADMIN)
                                        ],
                                        [
                                            'attribute' => 'address',
                                            'visible' => ($model->role_id != User::ROLE_ADMIN)
                                        ],
                                        // 'latitude',
                                        // 'longitude',
                                        // 'city',
                                        // 'country',
                                        [
                                            'attribute' => 'zipcode',
                                            'visible' => ($model->role_id == User::ROLE_USER)
                                        ],
                                        // 'language',

                                        [
                                            'attribute' => 'state_id',
                                            'format' => 'raw',
                                            'value' => $model->getStateBadge(),
                                            'visible' => ($model->role_id != User::ROLE_ADMIN)
                                        ],

                                        [
                                            'attribute' => 'is_approve',
                                            'label' => 'Verification',
                                            'format' => 'raw',
                                            'value' => $model->getApproveBadge(),
                                            'visible' => ($model->role_id == User::ROLE_SERVICE_PROVIDER)
                                        ],

                                        [
                                            'attribute' => 'working_zone',
                                            'label' => 'Working Zone',
                                            'format' => 'raw',
                                            'value' => $model->getProviderWorkingZone(),
                                            'visible' => ($model->role_id == User::ROLE_SERVICE_PROVIDER)
                                        ],

                                        /* [
                                    'attribute' => 'type_id',
                                    'value' => $model->getType ()
                            ], */
                                        //'last_visit_time:datetime',
                                        //'last_action_time:datetime',
                                        //'last_password_change:datetime',
                                        // 'login_error_count',
                                        /* 'activation_key', */
                                        // 'timezone',
                                        [
                                            'attribute' => 'created_on',
                                            'visible' => ($model->role_id != User::ROLE_ADMIN),
                                            'value' => date('Y-m-d h:i a', strtotime($model->created_on))
                                        ],

                                        [
                                            'label' => 'Medical Condition',
                                            'attribute' => 'about_me',
                                            'visible' => ($model->role_id == User::ROLE_USER)
                                        ]
                                    ]
                                ])?>

                            </div>
						</div>
					</div>
				</div>
                <?php if ($model->role_id != User::ROLE_ADMIN) { ?>
                    <div class="card">
					<div class="card-body">
						<div class="card-title mb-4">
                                <?php
                    $files = $model->getProviderFiles();
                    $title = 'Qualification Document';
                    if ($model->role_id == User::ROLE_USER) {
                        $files = $model->getUserFiles();
                        $title = 'Medical Document';
                    }
                    ?>
                                <h5>
                                    <?= Yii::t('app', $title) ?>
                                </h5>
						</div>
						<div class="row">

                                <?php
                    foreach ($files->each() as $file) {
                        ?>
                                    <div class="col-md-3">

								<div class="card-doc-wrapper">
                                            <?php
                        $extenssion = pathinfo($file->key, PATHINFO_EXTENSION);
                        ?>
                                            <a data-fancybox
										data-src="#modal-content<?= $file->id ?>" href="javascript:;">
                                                <?php
                        if ($extenssion == 'pdf') {

                            echo ViewerJsDocumentViewer::widget([
                                'url' => $file->getImageUrl(),
                                'height' => '300px',
                                'width' => '300px'
                            ]);
                        } else {
                            echo Html::img($file->getImageUrl(), [
                                'class' => 'img-responsive',
                                'alt' => $model,
                                'width' => '250',
                                'height' => '200'
                            ]);
                        }
                        ?>
                                            </a>


                                            <?php if ($model->role_id == User::ROLE_SERVICE_PROVIDER) { ?>
                                                <div
										class="accept-reject-btn mb-4">
                                                    <?php if ($file->is_approve == File::DOCUMENT_PENDING) { ?>
                                                        <a
											href="<?php

                                echo Url::toRoute([
                                    'user/approve-document',
                                    'id' => $model->id,
                                    'file_id' => $file->id
                                ]);
                                ?>"
											class="img-accept-btn btn btn-success">Approve</a>
										<button type="button" class="img-reject-btn btn btn-danger"
											data-toggle="modal"
											data-target="#reject-document<?= $file->id ?>">Reject</button>

                                                    <?php
                            }
                            if ($file->is_approve == File::DOCUMENT_APPROVED) {
                                ?>

                                                        <span
											class="img-reject-btn btn btn-success">Approved</span>
                                                    <?php

}
                            if ($file->is_approve == File::DOCUMENT_REJECTED) {
                                ?>
                                                        <span
											class="img-reject-btn btn btn-danger">Rejected</span>
                                                    <?php } ?>
                                                </div>





									<div class="modal fade bd-example-modal-lg custom-modal"
										tabindex="-1" id="reject-document<?= $file->id ?>"
										role="dialog">
										<div class="modal-dialog modal-lg" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title">
                                                                    <?php echo strtoupper('Reject Document'); ?>
                                                                </h5>
													<button type="button" class="close" data-dismiss="modal"
														aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">

                                                                <?php
                            $form = TActiveForm::begin([
                                'id' => 'document-reject-form',
                                'options' => [
                                    'class' => 'form-box form-title-wrap'
                                ],
                                'action' => Url::toRoute([
                                    'user/reject-document',
                                    'id' => $model->id,
                                    'file_id' => $file->id
                                ])
                            ]);
                            ?>

                                                                <div
														class="row">
														<div class="col-md-12">
															<div class="form-group">
                                                                            <?php echo $form->field($file, 'reason')->textarea(['rows' => 6, 'maxlength' => 255]);  ?>
                                                                        </div>
														</div>
														<div class="col-md-12 text-right">
                                                                        <?php

                            echo Html::submitButton('Confirm', [
                                'id' => 'document-form-submit',
                                'class' => 'btn btn-success btn-lg'
                            ])?>
                                                                    </div>

													</div>
                                                                <?php TActiveForm::end(); ?>
                                                            </div>
											</div>
										</div>
									</div>

                                            <?php } ?>


                                        </div>
							</div>


                                <?php
                    }

                    ?>
                            </div>
					</div>
				</div>

                    <?php
                    echo UserAction::widget([
                        'model' => $model,
                        'attribute' => 'state_id',
                        'states' => $model->getStateOptions(),
                        'visible' => ($model->role_id != User::ROLE_ADMIN)
                    ]);
                    ?>
                    <div class="card">
					<div class="card-body">
                            <?php
                    if ($model->role_id != User::ROLE_ADMIN) {
                        $this->context->startPanel();
                        if ($model->role_id == User::ROLE_SERVICE_PROVIDER) {
                            $this->context->addPanel('Qualifications', 'qualifications', 'UserCategory', $model);
                            $this->context->addPanel('Skills', 'skills', 'UserSkill', $model);
                            $this->context->addPanel('Languages', 'languages', 'UserLanguage', $model);
                        }
                        if ($model->role_id == User::ROLE_USER && $model->type_id == User::TYPE_USER) {
                            $this->context->addPanel('Dependent Users', 'dependentUsers', 'User', $model);
                        }
                        $this->context->addPanel('Login Histories', 'loginHistories', 'LoginHistory', $model);
                        $this->context->addPanel('Activities', 'feeds', 'Feed', $model);
                        $this->context->endPanel();
                    }
                    ?>
                        </div>
				</div>
                <?php } ?>
            </div>
		</div>
	</div>
</div>
<?php
$files = $model->getProviderFiles();
if ($model->role_id == User::ROLE_USER) {
    $files = $model->getUserFiles();
}
foreach ($files->each() as $file) {
    if (! empty($file)) {
        ?>
<div id='modal-content<?= $file->id ?>' style="display: none;">

            <?php
        $extenssion = pathinfo($file->key, PATHINFO_EXTENSION);
        if ($extenssion == 'pdf') {

            echo ViewerJsDocumentViewer::widget([
                'url' => $file->getImageUrl(),
                'height' => '650',
                'width' => '650'
            ]);
        } else {
            echo Html::img($file->getImageUrl(), [
                'class' => 'img-responsive',
                'alt' => $model,
                'width' => '650',
                'height' => '650'
            ]);
        }
        ?>
        </div>
<?php
    }
}
?>
<?php

$this->registerJs("$(document).ready(function(){
	$('iframe').ready(function() {
    setTimeout(function(){
      	$('iframe').contents().find('.innerCenter').hide();
      	$('iframe').contents().find('#toolbarRight').hide();
      	$('iframe').contents().find('#documentName').hide();
    },100);
	});
});", View::POS_READY);
?>
<?php if ($model->role_id != User::ROLE_ADMIN) { ?>
<script type="text/javascript"
	src="<?php
    echo $this->theme->getUrl('js/jquery.fancybox.js')?>"></script>
<?php } ?>