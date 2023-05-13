<?php
$modules = [];

if (! is_file(DB_CONFIG_FILE_PATH)) {
    $modules['installer'] = [
        'class' => 'app\modules\installer\Module',
        'sqlfile' => [
            DB_BACKUP_FILE_PATH . '/install.sql'
        ]
    ];
}

// Add extra if you have to

$modules['backup'] = [
    'class' => 'app\modules\backup\Module',
    'viewPath' => '@app/views/backup/views'
];
$modules['smtp'] = [
    'class' => 'app\modules\smtp\Module',
    'viewPath' => '@app/views/smtp/views'
];

$modules['settings'] = [
    'class' => 'app\modules\settings\Module',
    'viewPath' => '@app/views/settings/views'
];

$modules['scheduler'] = [
    'class' => 'app\modules\scheduler\Module',
    'viewPath' => '@app/views/scheduler/views'
];

$modules['contact'] = [
    'class' => 'app\modules\contact\Module',
    'viewPath' => '@app/views/contact/views'
];

$modules['notification'] = [
    'class' => 'app\modules\notification\Module'
];

foreach (glob(__DIR__ . "/common/*.php") as $file) {

    $modules = array_merge($modules, require $file);
}

return $modules;