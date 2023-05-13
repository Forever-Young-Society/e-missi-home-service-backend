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

use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class ImportForm extends Model
{

    public $file;

    /**
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [
                'file',
                'file'
            ]
        ];
    }

    public function asJson()
    {
        $Json = [];
        $Json['email'] = $this->email;
        $Json['file'] = $this->file;
        $Json['mobile_number'] = $this->mobile_number;
        return $Json;
    }
}
