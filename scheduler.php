<?php

if (php_sapi_name() != "cli") {
    echo 'Please run this file from command line interface !!!';
    exit();
}

require 'common.php';

$config = require (DB_CONFIG_PATH. '/console.php');

require (VENDOR_PATH . 'autoload.php');
require (VENDOR_PATH . 'yiisoft/yii2/Yii.php');

$application = new yii\console\Application ( $config );

try {
	$application->runAction('scheduler/cronjob');
} catch ( \Exception $ex ) {
	echo $ex->getMessage();
	echo $ex->getTraceAsString();
}
