<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use yii\helpers\Url;
use app\components\helpers\TLogHelper;

class AppTheme extends \yii\base\Theme
{
    use TLogHelper;

    public $logo;

    public $name = 'base';

    public $style = '';

    public function init()
    {
        parent::init();

        if (YII_ENV == 'dev') {

            if (isset($_GET['theme']) && ! empty($_GET['theme'])) {
                $this->name = $_GET['theme'];
            }
            if (isset($_GET['style']) && ! empty($_GET['style'])) {
                $this->style = $_GET['style'];
            }
        }
        
        if (\Yii::$app->hasModule('settings')) {
            $name = \Yii::$app->settings->getValue('theme', null, '*');

            self::log('theme =>' . $name);

            if (isset($name)) {

                $this->name = $name;
                self::log('updated theme =>' . $this->name);
            }
        }
        if (strpos($this->name, ':')) {

            $data = explode(':', $this->name);
            $this->name = $data[0];
            $this->style = $data[1];
        }
        self::log('final theme =>' . $this->name);
        $this->basePath = '@app/../themes/' . $this->name;
        $this->baseUrl = '@web/themes/' . $this->name;
        $this->pathMap = [
            '@app/views' => '@app/../themes/' . $this->name . '/views'
        ];
    }

    public function getLogoUrl($schema = false)
    {
        return Url::to($this->baseUrl . '/img/logo.png', $schema);
    }
}

