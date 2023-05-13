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
namespace app\components\ldap;

use Yii;
use yii\base\Component;

/**
 * Class Adldap
 *
 * @package alexeevdv\adldap
 */
class LdapWrapper extends Component
{

    /**
     * Options that will be passed to original Adldap class
     */
    public $options = [];

    /**
     * Original class instance
     */
    private $_instance;

    /**
     *
     * @inheritdoc
     */
    public function init()
    {
        $this->_instance = Yii::createObject(\Adldap\Adldap::class, [
            $this->options
        ]);

        parent::init();
    }

    /**
     *
     * @inheritdoc
     */
    public function __call($methodName, $methodParams)
    {
        if (method_exists($this->_instance, $methodName)) {
            return call_user_func_array([
                $this->_instance,
                $methodName
            ], $methodParams);
        }
        return parent::__call($methodName, $methodParams);
    }
}
