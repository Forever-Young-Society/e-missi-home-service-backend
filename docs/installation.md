<h1 align="center">
    <a href="http://toxsl.com" title="toxsl" target="_blank">
        <img width = "20%" height = "20%" src="https://toxsl.com/themes/toxsl/img/toxsl_logo.png" alt="Toxsl Logo"/>
    </a>
    <br>
    <hr>
</h1>

This is the Yii2-base-admin-panel-rest that will help you with pre-installed modules and many more features on just one go .Few steps are going to be performed by the user to setup the project on their workspace.
This setup already has bunch of inbuild modules that are going to help you with your project and some of those are :-

Yii2-base-admin-panel-rest
Blog Module
Comments Module
Favourite Module
Feature Module
Logger Module
Api Module
Contact Module
Installer Module
Sitemap Module
Seo Module
Shadow Module
Backup Module
Media Module
Chat Module


> NOTE: This git respository will provide you enough modules that are going to help you with your on going projects.
        Make sure you follow the steps to make them working for your projects.

## Installation

The preferred way to install this BASE is through [script](http://192.168.10.21/common/scripts.git).
Make sure you place it in root of your htdocs .

To install script module

```
git clone http://192.168.10.21/common/scripts.git
```

To install yii2-admin-panel-rest

```
git clone http://192.168.10.21/yii2/yii2-admin-panel-rest-1392.git
```

To install submodules

```
bash ../scripts/clone-submodules.sh
```

If you have composer.json

```
composer install --prefer-dist 
```

If you need to update vendor again you can use followig command

```
composer update --prefer-dist --prefer-stable
```

To install database run this command

```
php console.php installer/install -du=admin -dp=admin@123
```

Remember if you run above command, your previous data will format completely. I recommend you to never run this command on live server.

If you face database credentials permission error, then run command php console.php installer/install -du admin -dp admin@123 in my case admin is my database username and admin@123 is my database password.

To install database for single module

```
php console.php installer/install/module -m="modulename"
```

Install default data using : 

``` 
php console.php clear/default
```

## Usage
Once setup is done you need to follow the final setup with the installer .

```
make sure you give READ/WRITE permission to your folder.
```
## License

**www.toxsl.com** 
