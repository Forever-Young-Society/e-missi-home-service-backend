<?php

namespace app\base;


use IPay88\Payment\Request;

class TipayRequest extends Request
{
    public static $paymentUrl = 'https://www.mobile88.com/epayment/entry.asp';
    
  
    /**
     * IPay88 Payment Request factory function
     *
     * @access public
     * @param string $merchantKey The merchant key provided by ipay88
     * @param hash $fieldValues Set of field value that is to be set as the properties
     *  Override `$fillable_fields` to determine what value can be set during this factory method
     * @example
     *  $request = IPay88\Payment\Request::make($merchantKey, $fieldValues)
     *
     */
    public static function make($merchantKey, $fieldValues)
    {
        $request = new Request($merchantKey);
        TipayRequestForm::render($fieldValues, self::$paymentUrl);
    }
}
