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
namespace app\modules\smtp\helpers;

trait DnsHelper
{

    public static function getMXServer($email)
    {
        if (empty($email)) {
            return null;
        }
        $mxhosts = [];
        list ($username, $hostname) = explode('@', $email);
        if (dns_get_mx($hostname, $mxhosts)) {

            return $mxhosts[0];
        }
        return null;
    }
}