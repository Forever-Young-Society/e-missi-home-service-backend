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
namespace app\modules\backup\models;

use app\components\helpers\TArrayHelper;
use app\modules\settings\models\SettingsFormCommon;

/**
 * ReplyForm is the model behind the reply form.
 */
class SettingsForm extends SettingsFormCommon
{

    public $allowDownload = 0;

    /**
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return TArrayHelper::merge(parent::rules(), [
            // name, email, subject and body are required
            [
                [
                    'allowDownload'
                ],
                'required'
            ],
            [
                [
                    'enable',
                    'allowDownload'
                ],
                'integer'
            ]
        ]);
    }

    /**
     *
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return TArrayHelper::merge(parent::attributeLabels(), [
            'enable' => \yii::t('app', 'Enable'),
            'allowDownload' => \yii::t('app', 'Allow Download')
        ]);
    }
}
