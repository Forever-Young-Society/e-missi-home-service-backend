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
namespace app\modules\installer\models;

use yii\base\Model;

class Mail extends Model
{

    public $username = 'username';

    public $password = '';

    public $host = 'smtp.gmail.com';

    public $port = '25';

    public $encryption = 'tls';

    public $is_mail_prod = '0';

    const IS_MAIL = 1;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return [
            [
                [
                    'host',
                    'username'
                ],
                'required',
                'when' => function ($model) {
                    return $model->is_mail_prod == 1;
                },
                'whenClient' => "function (attribute, value) { return $('#mail-button').val() == '1'; }"
            ],
            [
                [
                    'is_mail_prod',
                    'port',
                    'password'
                ],
                'safe'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'is_mail_prod' => 'Create Mail Configuration'
        ];
    }
}