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

class TNameValidator extends Validator
{

    public $pattern = '/^[A-Za-z.]+((\s)?([A-Za-z])+)*$/';

    public function validateAttribute($model, $attribute)
    {
        if (! preg_match($this->pattern, $model->$attribute))
            $model->addError($attribute, $model->getAttributeLabel($attribute) . ' is invalid.');
    }

    public function validateValue($value)
    {
        if (! preg_match($this->pattern, $value)) {
            return false;
        }
        return true;
    }
}