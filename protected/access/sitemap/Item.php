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
namespace app\access\sitemap;

use app\models\User;

class Item
{

    public static function getAccessFilters()
    {
        return [
            [
                'actions' => [
                    'index',
                    'add',
                    'view',
                    'update',
                    'ajax',
                    'sync',
                    'clear',
                    'delete'
                ],
                'allow' => true,
                'matchCallback' => function () {
                    return User::isAdmin();
                }
            ],
            [
                'actions' => [
                    'sync'
                ],
                'allow' => true,
                'matchCallback' => function () {
                    return User::isAdmin() || User::isUser() || User::isServiceProvider() || User::isGuest();
                }
            ]
        ];
    }
}
