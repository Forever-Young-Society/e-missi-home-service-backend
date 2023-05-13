# Notification

#### About Brief


## INSTALLATION 

## If you need to install single module 

Steps-
1. Go to ***htdocs*** or ***html*** folder.
2. Then, Go to your project->protected->modules and Open your terminal by ***ctrl+alt+t*** . 
3. Type ***git clone  http://192.168.10.21/yii2/modules/notification.git*** in your terminal. Wait till installation complete.
4. After clone run (project->protected->modules) this command ***php console.php installer/install/module -m=notification*** in termianl. Open your terminal by ***ctrl+alt+t*** .

> add below code in your .gitmodule file.

        [submodule "protected/modules/notification"]
        path = protected/modules/notification
        url = http://192.168.10.21/yii2/modules/notification.git

> add below code in web.php file, located at protected/config/web.php

        $config['modules']['notification'] = [
         'class' => 'app\modules\notification\Module',
         'isnotificationOnApp' => false //set true if send notification on app
        ];

> add module in side nav bar, located at protected/base/TBaseController.php

         if (yii::$app->hasModule('notification'))
                   $this->nav_left[] = \app\modules\notification\Module::subNav();

## During setup new project > you need to use these steps

Steps- 
1. Go to ***htdocs*** or ***html*** > you project .
2. 1st check  .gitmodule file blog module lines ( exists or not)

        [submodule "protected/modules/notification"]
        path = protected/modules/notification
        url = http://192.168.10.21/yii2/modules/notification.git

3. Then, run (in your project root)  ***bash ../scripts/clone-submodules.sh*** in your terminal.

## How to add the notification widget 

Steps- 
1. Include NotificationWidget::widget where you want to show the notification icon.

2. Use create() function present in Notification model to add notifications.

