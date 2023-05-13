SMS Module
==========
Sends an SMS message throw a different sms gateway


Installation For twilio component
------------

run

```
php composer require 2amigos/yii2-twilio-component

```

Usage
-----

Once the extension is installed :

Then you can use it in your code :

```php

<?php
	
	app\modules\sms\components\Sms::send([
		'to' => '+91xxxxxxxxxx',
	    'model' => $model
	    'text' => 'Hello World'
	])
?>
```