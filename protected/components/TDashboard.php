<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;


class TDashboard extends TBaseWidget
{
    public $columns = 1;
    public $items ;
    
    public function init()
    {
        parent::init();

        if (! isset($this->id)) {
            $this->options['id'] = $this->getId();
        }
    }

    public function renderHtml()
    {
        foreach ( $this->items as $item)
        {
            echo TAjaxDashBox::widget($item);
        }

        
    }
}

