<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use yii\bootstrap4\Progress;

class TProgress extends Progress
{

    public function init()
    {
        parent::init();

        $this->options['id'] = $this->getId();
        $this->barOptions['class'] = $this->getClass();
    }

    public function getClass()
    {
        $per = $this->percent;
        if ($per >= 80) {
            $context = 'bg-success';
        } elseif ($per >= 50 && $per < 80) {
            $context = 'bg-warning';
        } else {
            $context = 'bg-danger';
        }
        return $context;
    }
}

