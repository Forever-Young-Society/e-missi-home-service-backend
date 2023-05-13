#Components Readme (Author Shiv Charan Panjeta < shiv@toxsl.com >)

#About Brief

This file is to give you an overview about the components that has been modified or new generated code help  which is of overall use.The Components are building blocks of Yii and involves events,behavoirs and properties 


The components folder will get install in your project when your run the command (bash ../scripts/clone-submodules.sh) and the day to day changes and improvemrnts  will be updated in existing by running the (bash ../scripts/git-sync-all.sh)


#Useful folders 


-Commands(It is extended by TConsole Controller n the controller class, you define one or more actions that correspond to sub-commands of the controller. Within each action, you write code that implements the appropriate tasks for that particular sub-command. )

-Filters (Yii provides two authorization methods: Access Control Filter (ACF) and Role-Based Access Control (RBAC) and is extended by yii\filters\AccessControl)

-Formatter (formats the click to tel link or on click formats to call link)


-Grid (The GridView widget is used to display data in a grid.helpful for the pagination sorting and many more features like sum )

-Helpers (Helpers  simplify common coding tasks, such as string or array manipulations, HTML code generation. These helper classes are organized under the yii\helpers namespace and are all static classes(meaning they contain only static properties and methods and should not be instantiated).)

-ProfileImage (This helps to crop the image and  upload the image)

-Toster Toasts renders an toast bootstrap component.


-Validators(This folders includes the files which is useful for the data Validation Like Email,Domain,AdharCard,PhoneNumber,PanNumber,Password)


# Some Useful Files
- TUrlManager (for handling the Url )

-TactiveRecord (this file is extended by the Active Record and is helpful in manipulating the data stored in database)

-TActiveQuery(this file is extended by the Active Query  and is helpful in retrival of relational contextual data)

- TPdfWriter (This  library offers ability to generate PDF files from UTF-8 encoded HTML and is modified from mpdf)

- TActiveForm (TActiveForm is a widget that builds an interactive HTML form for one or multiple data models.)

- FireBaseNotification (In this file you will find out the handling of fcm )

- PageHeader (this file is extended by the BaseWidget class and is helpful in the customization of pageheader)

- PageWidget (this file is extended by the BaseWidget class and is helpful in the customization of page)

- India (this file provides you the list of all the cities,states of India)

- Settings(this file is extended by the Yii\Base\Component class of Yii)

- World (this file provides you the list of all the countries of the world with their symbols)

- WorldClock(this file is extended by the BaseWidget class and requires "powerkernel/yii2-flag-icon-css": "*" in require in your composer files. it provides the alpha symbols)
