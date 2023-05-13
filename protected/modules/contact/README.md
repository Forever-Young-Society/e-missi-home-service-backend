# CONTACT

#### About Brief


## INSTALLATION 

## If you need to install single module 

Steps-
1. Go to ***htdocs*** or ***html*** folder.
2. Then, Go to your project->protected->modules and Open your terminal by ***ctrl+alt+t*** . 
3. Type ***git clone  http://192.168.10.21/yii2/modules/contact.git*** in your terminal. Wait till installation complete.
4. After clone run (project->protected->modules) this command ***php console.php installer/install/module -m=contact*** in termianl. Open your terminal by ***ctrl+alt+t*** .

> add below code in your .gitmodule file.

        [submodule "protected/modules/contact"]
        path = protected/modules/contact
        url = http://192.168.10.21/yii2/modules/contact.git

> add below code in web.php file, located at protected/config/web.php

        $config['modules']['contact'] = [
         'class' => 'app\modules\contact\Module'
        ];

> add module in side nav bar, located at protected/base/TBaseController.php

         if (yii::$app->hasModule('contact'))
                   $this->nav_left[] = \app\modules\contact\Module::subNav();

> add extension kartik-v/yii2-widget-datetimepicker": "dev-master" in composer.json and composer update

## During setup new project > you need to use these steps

Steps- 
1. Go to ***htdocs*** or ***html*** > you project .
2. 1st check  .gitmodule file blog module lines ( exists or not)

        [submodule "protected/modules/contact"]
        path = protected/modules/contact
        url = http://192.168.10.21/yii2/modules/contact.git

3. Then, run (in your project root)  ***bash ../scripts/clone-submodules.sh*** in your terminal.




