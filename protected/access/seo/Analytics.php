<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of Toxsl Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\access\seo;

use app\models\User;

class Analytics
{

    public static function getAccessFilters()
    {
        return [
            [
                'actions' => [
                    'delete',
                    'clear',
                    'index',
                    'add',
                    'view',
                    'update',
                    'ajax',
                    'mass',
                    'view'
                ],
                'allow' => true,
                'matchCallback' => function () {
                    return User::isAdmin();
                }
            ]
        ];
    }
}
