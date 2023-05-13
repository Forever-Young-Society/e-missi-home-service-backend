# logger

#### About Brief
* It is exception logger.

## INSTALLATION 

## If you need to install single module 

Steps-
1. Go to ***htdocs*** or ***html*** folder.
2. Then, Go to your project->protected->modules and Open your terminal by ***ctrl+alt+t*** . 
3. Type ***git clone  http://192.168.10.21/yii2/modules/logger.git*** in your terminal. Wait till installation complete.
4. After clone run (project->protected->modules) this command ***php console.php installer/install/module -m=logger*** in termianl. Open your terminal by ***ctrl+alt+t*** .

> add below code in your .gitmodule file.

        [submodule "protected/modules/logger"]
        path = protected/modules/logger
        url = http://192.168.10.21/yii2/modules/logger.git

> add below code in web.php file, located at protected/config/web.php

    $config['modules']['logger'] = [
        'class' => 'app\modules\logger\Module',
        'sendLogEmailsTo' => 'support@jiwebtech.com'
    ];
    $config['components']['errorHandler'] = [
        'class' => 'app\modules\logger\components\TErrorHandler'
    ];
        

> add module in side nav bar, located at protected/base/TBaseController.php

         if (yii::$app->hasModule('logger'))
                   $this->nav_left[] = \app\modules\logger\Module::subNav();

## During setup new project > you need to use these steps

Steps- 
1. Go to ***htdocs*** or ***html*** > you project .
2. 1st check  .gitmodule file blog module lines ( exists or not)

        [submodule "protected/modules/logger"]
        path = protected/modules/logger
        url = http://192.168.10.21/yii2/modules/logger.git

3. Then, run (in your project root)  ***bash ../scripts/clone-submodules.sh*** in your terminal.

## Enable app side error logging > you need to use these steps

Steps- 
1. Go to ***api module -> module.php***
	
> add below code in init function.

		$this->controllerMap = [
            'log' => [
                'class' => 'app\modules\logger\controllers\LogController'
            ],
        ];

2. Go to ***api module -> DefaultController*** 
	
> add below code in api-json action.

		'dirs' => [
                    Yii::getAlias('@app/modules/logger/controllers')
				   ]

