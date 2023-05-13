<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use yii\bootstrap4\ActiveField;

class TActiveField extends ActiveField
{

    public function __construct($config = [])
    {
        $config['horizontalCssClasses'] = [
            'label' => [
                'col-sm-4',
                'col-form-label',
                'text-left', 
                'text-sm-right'
            ],
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
            'field' => 'form-group row'
        ];

        parent::__construct($config);
    }
}