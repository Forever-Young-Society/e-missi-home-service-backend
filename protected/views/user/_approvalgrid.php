<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\User $searchModel
 */

$exportColumns = [
    [
        'class' => 'yii\grid\SerialColumn',
        'header' => '<a>S.No.<a/>'
    ],
    'id',
    'full_name',
    'email:email',
    'date_of_birth:date',
    [
        'attribute' => 'gender',
        'format' => 'raw',
        'value' => function ($data) {
            return $data->getGender();
        }
    ],
    'contact_no',
    'identity_number',

    [
        'attribute' => 'experience',
        'label' => 'Experience(in years)',
        'format' => 'raw'
    ],

    'zipcode',

    [
        'attribute' => 'working_zone',
        'label' => 'Working Zone',
        'value' => function ($data) {
            return $data->getProviderWorkingZone();
        }
    ],

    [
        'attribute' => 'about_me',
        'label' => 'Medical Condition'
    ],

    [
        'attribute' => 'role_id',
        'format' => 'raw',
        'value' => function ($data) {
            return $data->getRole();
        }
    ],
    [
        'attribute' => 'state_id',
        'format' => 'raw',
        'value' => function ($data) {
            return $data->getStateBadge();
        }
    ],
    [
        'attribute' => 'otp_verified',
        'format' => 'raw',
        'value' => function ($data) {
            return $data->getOtpBadge();
        }
    ],

    'created_on:datetime',
    [
        'attribute' => 'created_by_id',
        'format' => 'raw',
        'value' => function ($data) {
            return $data->getRelatedDataLink('created_by_id');
        },
        'visible' => (Yii::$app->controller->action->id == 'dependent-user')
    ]
];

?>
<?php Pjax::begin(["enablePushState"=>false,"enableReplaceState"=>false,'id' => 'user-pjax-grid']); ?>

    <?php

    echo TGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'exportable' => true,
        'exportColumns' => $exportColumns,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
            'id',
            'full_name',
            'email:email',

            'contact_no',

            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],

            [
                'attribute' => 'created_on',
                'filter' => \yii\jui\DatePicker::widget([
                    'language' => 'en-US',
                    'inline' => false,
                    'clientOptions' => [
                        'autoclose' => true
                    ],
                    'model' => $searchModel,
                    'attribute' => 'created_on',

                    'options' => [
                        'id' => 'created_on',
                        'class' => 'form-control',
                        'autoComplete' => 'off'
                    ]
                ]),
                'value' => function ($data) {
                    return $data->created_on;
                }
            ],
                              /*   [ 
										'attribute' => 'created_by_id',
										'format' => 'raw',
						                 'value' => function ($data) {
											return $data->getRelatedDataLink ( 'created_by_id' );
										} 
								], */
								
            [
                'class' => 'app\components\TActionColumn',
                'header' => '<a>ACTIONS</a>',
                'template' => '{view}', // here will be all posible buttons
                'buttons' => [
                    'Approve' => function ($url, $model, $key) {
                        return Html::a('<span class="fa fa-check ">Approve</span>', [
                            'user/approve-provider',
                            'id' => $model->id
                        ], [
                            'class' => 'btn btn-success'
                        ]);
                    },
                    'Reject' => function ($url, $model, $key) {
                        return Html::a('<span class="fa fa-ban">Reject</span>', [
                            'user/reject-provider',
                            'id' => $model->id
                        ], [
                            'class' => 'btn btn-danger'
                        ]);
                    }
                ]
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>

