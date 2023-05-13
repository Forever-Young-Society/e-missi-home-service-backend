# smtp

#### About Brief
* SMTP is an internet standard communication protocol for mail transmission. 
* Mail servers use SMTP to send mail messages.

#### Features
* SMTP module allow senidng emails using given SMTP server details.
* Multiple SMTP accounts
* EMail Queue to resend email if not sent in first attempt
* Unsubscribe to check email unsubscribed and so email Discarded
* Send emails through SMTP Account with Encryption protocol SSL and TLS.
* Have functionality to add automatic server according to the account suffix.
* We can Export and Import SMTP Account.
* SMTP uses port number 587 with TLS, 465 with SSL and 25 without SSL & TLS
* SMTP uses persistent TCP connections, so it can send multiple emails at once

## INSTALLATION 

## If you need to install single module 

Steps-
1. Go to ***htdocs*** or ***html*** folder.
2. Then, Go to your project->protected->modules and Open your terminal by ***ctrl+alt+t*** . 
3. Type ***git clone  http://192.168.10.21/yii2/modules/smtp.git*** in your terminal. Wait till installation complete.
4. After clone run (project->protected->modules) this command ***php console.php installer/install/module -m=smtp*** in termianl. Open your terminal by ***ctrl+alt+t*** .

> add below code in your .gitmodule file.

        [submodule "protected/modules/smtp"]
        path = protected/modules/smtp
        url = http://192.168.10.21/yii2/modules/smtp.git

> add below code in web.php file, located at protected/config/web.php

        $config['modules']['smtp'] = [
         'class' => 'app\modules\smtp\Module'
        ];
        
> add below code in console.php file, located at protected/config/console.php

        $config['modules']['smtp'] = [
         'class' => 'app\modules\smtp\Module'
        ];
        
> add module in side nav bar, located at protected/base/TBaseController.php

         if (yii::$app->hasModule('smtp'))
                   $this->nav_left[] = \app\modules\smtp\Module::subNav();

## During setup new project > you need to use these steps

Steps- 
1. Go to ***htdocs*** or ***html*** > you project .
2. 1st check  .gitmodule file blog module lines ( exists or not)

        [submodule "protected/modules/smtp"]
        path = protected/modules/smtp
        url = http://192.168.10.21/yii2/modules/smtp.git

3. Then, run (in your project root)  ***bash ../scripts/clone-submodules.sh*** in your terminal.

## How to test > you need to use these steps

Steps- 
1. Go to the Browser and open your project.
2. Add or Import SMTP Account with valid credentials.
3. Click on test button in view section of account and enter email to send email.
4. Go to ***htdocs*** or ***html*** > you project ..
5. Open Terminal and use `php console.php smtp/email-queue/send` or `php console.php email-queue/send`
6. Check All pending Emails sending if there is any.




 
