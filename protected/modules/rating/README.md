<p align="center">
    <a href="http://toxsl.com" target="_blank">
        <img src="https://toxsl.com/themes/toxsl/img/toxsl_logo.png" width="400" alt="Yii Framework" />
    </a>
</p>

Rating & Review Module
------------

-This module will help you to give Rating and reviews for any of your models.

-This module will require only one 3rd party extension . :)

-Designed for Simply Play n Plug approach .

-Lets proceed with an Installation .

## Install

Add to your config file:

```php
...
'components' => [
    'rating' => [
        'class' => 'app\modules\rating\module'
    ],
    ...
],
```

## How to use
```php
For Add
<?=AddRatingWidget::widget(['model' => $yourModel]);?>

For Viewing
<?= RatingWidget::widget(['model' => $yourModel,]);?>
```

## With more Options
```php
<?php
echo RatingWidget::widget([
    'model' => $yourModel,
    'options' => [
        'showAverage' => false,
        'showBreakdown' => false
    ]
]);
?>
```

