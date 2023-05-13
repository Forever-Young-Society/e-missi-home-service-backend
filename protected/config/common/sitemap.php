<?php
return [
    'sitemap' => [
        'class' => 'app\modules\sitemap\Module',
        'viewPath' => '@app/views/sitemap/views',
        'models' => [ // your models
        ],

        'urls' => [
            [
                'loc' => '/',
                'priority' => '1.0'
            ],

            [
                'loc' => '/site/about'
            ],

            [
                'loc' => '/site/contact'
            ],

            [
                'loc' => '/site/privacy'
            ],
            [
                'loc' => '/site/terms'
            ]
        ],
        'enableGzip' => true
    ]
];

