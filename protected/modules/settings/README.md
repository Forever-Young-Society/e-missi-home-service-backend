# SETTINGS

#### About Brief
* Settings module allows to manage custom settings to store key and value of modules.
* Varible to add and update value & key of modules.
* We can directly use components functions using this module as shown in uses.
* The keys are the property names, and the values are the corresponding initial values.

## INSTALLATION 

## If you need to install single module 

Steps-
1. Go to ***htdocs*** or ***html*** folder.
2. Then, Go to your project->protected->modules and Open your terminal by ***ctrl+alt+t*** . 
3. Type ***git clone  http://192.168.10.21/yii2/modules/settings.git*** in your terminal. Wait till installation complete.
4. After clone run (project->protected->modules) this command ***php console.php installer/install/module -m=settings*** in termianl. Open your terminal by ***ctrl+alt+t*** .

> add below code in your .gitmodule file.

        [submodule "protected/modules/settings"]
        path = protected/modules/settings
        url = http://192.168.10.21/yii2/modules/settings.git

> add below code in web.php file, located at protected/config/web.php

        $config['modules']['settings'] = [
         'class' => 'app\modules\settings\Module'
        ];
  
  Inside your components ->

 'components' => [
 'settings' => [
            'class' => 'app\modules\settings\components\Keys'
        ]
        ];
        
> add below code in console.php file, located at protected/config/console.php
 
 Inside your components ->
 'components' => [
 'settings' => [
            'class' => 'app\modules\settings\components\Keys'
        ]
        ];
 
> add module in side nav bar, located at protected/base/TBaseController.php

         if (yii::$app->hasModule('settings'))
                   $this->nav_left[] = \app\modules\settings\Module::subNav();

## During setup new project > you need to use these steps

Steps- 
1. Go to ***htdocs*** or ***html*** > you project .
2. 1st check  .gitmodule file blog module lines ( exists or not)

        [submodule "protected/modules/settings"]
        path = protected/modules/settings
        url = http://192.168.10.21/yii2/modules/settings.git

3. Then, run (in your project root)  ***bash ../scripts/clone-submodules.sh*** in your terminal.

#### Uses
* Uses of settings module components functions

       \Yii::$app->settings->functionName(arguments)

#### Enable/Disable any key using command line

* Get any key value using php console.php settings/config/get key_name

* Set any Key value using php console.php settings/config/set key_name
  default value set true




