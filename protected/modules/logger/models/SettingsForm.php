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
namespace app\modules\logger\models;

use app\components\helpers\TArrayHelper;
use app\modules\settings\models\SettingsFormCommon;

/**
 * ReplyForm is the model behind the reply form.
 */
class SettingsForm extends SettingsFormCommon
{

    public $enableEmails = 1;

    public $sendLogEmailsTo = null;

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
                    'enableEmails',
                    'sendLogEmailsTo'
                ],
                'required'
            ],
            [
                [
                    'enable',
                    'enableEmails'
                ],
                'integer'
            ],
            [
                [
                    'sendLogEmailsTo'
                ],
                'string'
            ],
            [
                'sendLogEmailsTo',
                'email'
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
            'enable' => \yii::t('app', 'Enable Logger'),
            'enableEmails' => \yii::t('app', 'Enable Emails'),
            'sendLogEmailsTo' => \yii::t('app', 'Send Emails To')
        ]);
    }
}
