<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\validators;

use yii\validators\Validator;
use app\components\helpers\TStringHelper;

class TKeywordValidator extends Validator
{

    public $exactMatch = false;

    public $minWords = 3;

    public $words = [
        'done',
        'completed',
        'nothing'
    ];

    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = trim($model->$attribute);
        $words = TStringHelper::countWords($model->$attribute);
        if ($words < $this->minWords) {
            $model->addError($attribute, 'not valid words : ' . $words);
        }
        foreach ($this->words as $word) {

            $pattern = "/$word/i";
            if ($this->exactMatch) {
                $pattern = "/^$word$/i";
            }
            if (preg_match($pattern, $model->$attribute)) {
                $model->addError($attribute, 'You are not allowed to use word : ' . $word);
            }
        }
    }
}