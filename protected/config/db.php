<?php
			return [
    			'class' => 'yii\db\Connection',
    			'dsn' => 'mysql:host=127.0.0.1;dbname=e_missi_home_service_yii2_1843',
    			'emulatePrepare' => true,
    			'username' => 'admin',
    			'password' => 'admin@123',
    			'charset' => 'utf8mb4',
    			'tablePrefix' => 'tbl_',
    			'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],
                'enableSchemaCache' => 1 ,
                'schemaCacheDuration' => 3600,
             // 'queryCacheDuration' => 10,
                'schemaCache' => 'cache',
			];