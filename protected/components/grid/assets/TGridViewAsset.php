<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\components\grid\assets;

use yii\web\AssetBundle;

/**
 *
 * @copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
class TGridViewAsset extends AssetBundle
{

    /**
     *
     * @inheritdoc
     */
    public $sourcePath = '@app/components/grid/assets/src';


    /**
     *
     * @inheritdoc
     */
    public $css = [
        'css/grid.css'
    ];
}
