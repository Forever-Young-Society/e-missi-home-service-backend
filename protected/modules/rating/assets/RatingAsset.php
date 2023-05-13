<?php
namespace app\modules\rating\assets;

use yii\web\AssetBundle;

class RatingAsset extends AssetBundle
{

    /**
     *
     * @inheritdoc
     */
    public $sourcePath = '@app/modules/rating/assets/src';

    /**
     *
     * @inheritdoc
     */
    public $css = [
        'css/style-rating.css'
    ];

    public $js = [
        'js/rating.js'
    ];

    /* Do not need in b4 version */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
