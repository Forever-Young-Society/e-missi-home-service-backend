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
namespace app\access\seo;

use app\models\User;
use Yii;

class Log
{

    public static function getAccessFilters()
    {
        return [
            [
                'actions' => [
                    'clear',
                    'delete',
                    'view',
                    'index',
                    'ajax'
                ],
                'allow' => true,
                'matchCallback' => function () {
                    return User::isAdmin();
                }
            ]
        ];
    }
}
