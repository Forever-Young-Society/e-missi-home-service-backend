<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use Yii;

class TBaseWidget extends \yii\base\Widget
{

    public $options = [];
    
    public $route;

    public $params;

    public $visible ;
    
    public function init()
    {
        parent::init();
        if (! isset($this->visible)) {
            $this->visible = true;
        }
    }
    
    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        if ($this->visible) {
            return $this->renderHtml();
        }
    }

    public function renderHtml()
    {
        
    }
}