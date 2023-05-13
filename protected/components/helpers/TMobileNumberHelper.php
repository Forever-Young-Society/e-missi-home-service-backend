<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\components\helpers;

/**
 * TDeviceHelper::isMobile()
 */
class TMobileNumberHelper
{
    use TLogHelper;

    public static function getNumberWithCode($number)
    {
        self::log('toMobileWithCode :number : ' . $number);
        if (! TStringHelper::startsWith($number, '+') && strlen($number) == 10) {
            return '+91' . $number;
        }
        if (TStringHelper::startsWith($number, '91') && strlen($number) == 12) {
            return '+' . $number;
        }
        if (TStringHelper::startsWith($number, '0') && strlen($number) == 11) {
            return '+91' . substr($number, 1);
        }
        $number = preg_replace('/[^0-9\+]/', '', $number);
        return $number;
    }
}
