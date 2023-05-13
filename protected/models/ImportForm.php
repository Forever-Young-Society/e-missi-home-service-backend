<?php
namespace app\models;

use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class ImportForm extends Model
{

    public $file;
    public $id;

    public $email;

    public $mobile_number;

    /**
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            
            
            [
                'file',
                'file',
                
            ],
           
            [
                
                'email',
                'email'
            ],
            
            [
                [
                    
                    'mobile_number',
                    
                    
                ],
                'trim'
                
            ],
            [
                [
                    
                    'id'
                ],
                'integer'
            ],
            [
                [
                    'file',
                    'mobile_number',
                    'email',
                    'id'
                ],
                'safe'
            ]
        
        ];
    }
    public function asJson() {
        $Json = [ ];
        $Json ['email'] = $this->email;
        $Json ['file'] = $this->file;
        $Json ['mobile_number'] = $this->mobile_number;
        return $Json;
    }
}
