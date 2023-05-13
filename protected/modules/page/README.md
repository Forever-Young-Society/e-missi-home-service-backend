Page Module
------------

-This module will help you to handle static CMC pages.

-Designed for Simply Play n Plug approach .

-Lets proceed with an Installation .

## Install

Add to your config file:

```php
...
$config['modules']['page'] = [
	'class' => 'app\modules\page\Module'
];
    ...
],
```

##Add the package to your composer.json:

    {
        "require": {
            "bizley/contenttools": "^1.4"
        }
    }

and run `composer update` or alternatively run `composer require bizley/contenttools`