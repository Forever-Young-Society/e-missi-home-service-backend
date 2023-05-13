<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\modules\settings\models;

use Yii;
use yii\base\Model;

/**
 * ReplyForm is the model behind the reply form.
 */
class SettingsFormCommon extends Model
{

    public $enable = 1;

    /**
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [
                [
                    'enable'
                ],
                'required'
            ]
        ];
    }

    /**
     *
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'enable' => \yii::t('app', 'Enable')
        ];
    }

    public function init()
    {
        parent::init();
        foreach ($this->attributes as $key => $value) {
            $this->$key = \Yii::$app->settings->getValue($key, $this->$key);
        }
    }

    public function save()
    {
        foreach ($this->attributes as $key => $value) {
            \Yii::$app->settings->setValue($key, $value);
        }
    }

    public function getFormAttributeId($attribute)
    {
        return Yii::$app->controller->module->id . $attribute;
    }
}
