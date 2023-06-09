<?php
/**
 * @link https://github.com/yiimaker/yii2-social-share
 * @copyright Copyright (c) 2017 Yii Maker
 * @license BSD 3-Clause License
 */
namespace app\modules\contact\assets;

use yii\web\AssetBundle;

/**
 * Asset for social icons font.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 * @since 1.0
 */
class ContactAsset extends AssetBundle
{

    /**
     *
     * @inheritdoc
     */
    public $sourcePath = '@app/modules/contact/assets/src';

    /**
     *
     * @inheritdoc
     */
    public $css = [
        'css/contact.css'
    ];
}
