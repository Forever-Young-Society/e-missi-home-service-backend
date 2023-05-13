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
namespace app\modules\smtp\models;

use app\components\TActiveRecord;
use yii\base\Model;

/**
 * ReplyForm is the model behind the reply form.
 */
class ReplyForm extends Model
{

    public $email;

    public $subject;

    public $body;

    public $emailAccount;

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
                    'email',
                    'subject',
                    'body',
                    'emailAccount'
                ],
                'required'
            ],
            [
                [
                    'subject',
                    'email'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'body'
                ],
                'string'
            ],
            // email has to be a valid email address
            [

                'email',
                'email'
            ]
        ];
    }

    function getEmailList()
    {
        return TActiveRecord::listData(Account::findActive()->all());
    }

    /**
     *
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'subject' => \yii::t('app', 'Subject'),
            'body' => \yii::t('app', 'Message'),
            'emailAccount' => \yii::t('app', 'Email Account')
        ];
    }
}
