<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\components\validators;

use yii\validators\Validator;

class TPanNumberValidator extends Validator
{

    public $regExPattern = '/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/';

    public function validateAttribute($model, $attribute)
    {
        if (preg_match($this->regExPattern, $model->$attribute)) {
            $model->addError($attribute, 'Not valid Pan Card Number');
        }
    }
}