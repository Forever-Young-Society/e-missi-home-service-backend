<?php
use app\components\grid\TGridView;
use yii\widgets\Pjax;
/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\User $searchModel
 */
$controller = Yii::$app->controller;
$action = $controller->action->id;
$toolbar = ($controller->id == 'dashboard') ? false : true;

$exportColumns = [
    [
        'class' => 'yii\grid\SerialColumn',
        'header' => '<a>S.No.<a/>'
    ],
    'id',
    'full_name',
    'email:email',
    'date_of_birth',
    [
        'attribute' => 'date_of_birth',
        'format' => 'raw',
        'value' => function ($data) {
            return ! empty($data->date_of_birth) ? $data->date_of_birth : '';
        }
    ],
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
        },
        'visible' => ($action == 'incomplete')
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
        },
        'visible' => ($action != 'dependent-user')
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
        'showToolbar' => $toolbar,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header' => '<a>S.No.<a/>'
            ],
            // 'id',
            'full_name',
            'email:email',
            /* 'password',*/
            /* 'date_of_birth:date',*/
            /* 'gender',*/
            /* 'about_me',*/
             'contact_no',
            'identity_number',
            /* 'address',*/
            /* 'latitude',*/
            /* 'longitude',*/
            /* 'city',*/
            /* 'country',*/
            /* 'zipcode',*/
            /* 'language',*/
            [
                'attribute' => 'role_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getFilterRoleOptions() : null,
                'value' => function ($data) {
                    return $data->getRole();
                },
                'visible' => ($action == 'incomplete')
            ],
            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getStateOptions() : null,
                'value' => function ($data) {
                    return $data->getStateBadge();
                }
            ],
            [
                'attribute' => 'otp_verified',
                'format' => 'raw',
                'filter' => isset($searchModel) ? $searchModel->getOtpOptions() : null,
                'value' => function ($data) {
                    return $data->getOtpBadge();
                },
                'visible' => ($action != 'dependent-user')
            ],
            /* ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],*/
            /* 'timezone',*/
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
            [
                'attribute' => 'created_by_id',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->getRelatedDataLink('created_by_id');
                },
                'visible' => (Yii::$app->controller->action->id == 'dependent-user')
            ],
            [
                'class' => 'app\components\TActionColumn',
                'template' => '{view} {delete}',
                'header' => '<a>Actions</a>'
            ]
        ]
    ]);
    ?>
<?php Pjax::end(); ?>

