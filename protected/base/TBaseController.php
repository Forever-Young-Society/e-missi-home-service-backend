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
namespace app\base;

use app\models\User;
use Yii;
use yii\web\Controller;
use app\components\EmailVerification;

abstract class TBaseController extends Controller
{

    public $allowedIPs = [
        '127.0.0.1',
        '::1',
        '192.168.*.*'
    ];

    public $layout = '//guest-main';

    public $menu = [];

    public $top_menu = [];

    public $side_menu = [];

    public $user_menu = [];

    public $tabs_data = null;

    public $tabs_name = null;

    public $dryRun = false;

    public $assetsDir = '@webroot/assets';

    public $ignoreDirs = [];

    public $nav_left = [];

    protected $_author = '@toxsltech';

    // nav-left-medium';
    protected $_pageCaption;

    protected $_pageDescription;

    protected $_pageKeywords;

    public function beforeAction($action)
    {
        if (! parent::beforeAction($action)) {
            return false;
        }
        if (! Yii::$app->user->isGuest && ! User::isAdmin()) {
            EmailVerification::checkIfVerified();
        }
        if (! \Yii::$app->user->isGuest) {
            $this->layout = 'main';
        }
        return true;
    }

    public static function addmenu($label, $link, $icon, $visible = null, $list = null, $submenu = false)
    {
        if (! $visible)
            return null;
        $item = [
            'label' => '<i
							class="fa fa-' . $icon . '"></i> <span>' . $label . '</span>',
            'url' => [
                $link
            ]
        ];
        if ($list != null) {
            $item['options'] = [
                'class' => 'menu-list nav-item'
            ];

            $item['items'] = $list;
        }
        if ($submenu) {
            $item['options'] = [
                'class' => 'sub-menu-list nav-item'
            ];

            $item['items'] = $list;
        }

        return $item;
    }

    public function renderNav()
    {
        $nav_left = [

            self::addMenu(Yii::t('app', 'Dashboard'), '//', 'tachometer', (! User::isGuest())),
            'Account Management' => self::addMenu(Yii::t('app', 'Account Management'), '#', 'key', User::isManager(), [
                self::addMenu(Yii::t('app', 'Users'), '//user/index', 'users', (User::isManager())),
                self::addMenu(Yii::t('app', 'Service Providers'), '//user/provider', 'user-secret', (User::isManager())),
                self::addMenu(Yii::t('app', 'Incomplete Users'), '//user/incomplete', 'user', (User::isManager())),
                self::addMenu(Yii::t('app', 'Rejected Providers'), '//user/rejected', 'times', (User::isManager()))
            ], true),
            self::addMenu(Yii::t('app', 'Dependent Users'), '//user/dependent-user', 'user', (User::isManager())),
            self::addMenu(Yii::t('app', 'Provider Approvals <i class="fa fa-bell sidebar-badge"><sup id="chat-count">' . User::getProviderApprovalCount() . '</sup></i>'), '//user/provider-approval', 'history', User::isAdmin()),
            self::addMenu(Yii::t('app', 'Available Languages'), '//language/index', 'language', (User::isManager())),

            'Revenue Management' => self::addMenu(Yii::t('app', 'Revenue Management'), '#', 'money', User::isManager(), [
                self::addMenu(\Yii::t('app', 'Dashboard'), '//availability/default/revenue', 'lock', User::isAdmin()),
                self::addMenu(\Yii::t('app', 'Completed Bookings'), '//availability/slot-booking/revenue-index', 'ticket', User::isAdmin())
            ]),

            'Booking Management' => self::addMenu(Yii::t('app', 'Booking Management'), '#', 'bookmark', User::isManager(), [
                self::addMenu(\Yii::t('app', 'Dashboard'), '//availability', 'lock', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Availability Slots'), '//availability/provider-slot/index', 'calendar', (User::isManager())),
                self::addMenu(\Yii::t('app', 'Bookings'), '//availability/slot-booking/index', 'ticket', User::isAdmin()),
                self::addMenu(\Yii::t('app', 'Booking Reports'), '//service/report/index', 'tasks', User::isAdmin())
            ], true),

            "FAQ's" => self::addMenu(Yii::t('app', "FAQ's"), '#', 'question', User::isManager(), [
                self::addMenu(Yii::t('app', "User FAQ's"), '/faq/faq/index', 'list', (User::isManager())),
                self::addMenu(Yii::t('app', "Provider FAQ's"), '/faq/faq/provider-index', 'list', (User::isManager()))
            ], true),

            'Manage' => self::addMenu(Yii::t('app', 'Manage'), '#', 'tasks', User::isManager(), [
                self::addMenu(Yii::t('app', 'Activities'), '//feed/index/', 'tasks', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Login History'), '//login-history/', 'sign-in', User::isAdmin()),
                self::addMenu(Yii::t('app', 'Backup'), '//backup/', 'download', User::isAdmin()),
                \app\modules\logger\Module::subNav(),
                // \app\modules\seo\Module::subNav(),
                \app\modules\settings\Module::subNav(),
                \app\modules\notification\Module::subNav(),
                \app\modules\smtp\Module::subNav(),
                \app\modules\scheduler\Module::subNav(),
                \app\modules\sms\Module::subNav()
            ], true),
            self::addMenu(Yii::t('app', 'Contact Information'), '//contact/information', 'list', (User::isManager()))
        ];
        if (yii::$app->hasModule('service'))
            $nav_left['service'] = \app\modules\service\Module::subNav();
        if (yii::$app->hasModule('workzone'))
            $nav_left['workzone'] = \app\modules\workzone\Module::subNav();
        if (yii::$app->hasModule('page'))
            $nav_left['page'] = \app\modules\page\Module::subNav();

        $this->nav_left = $nav_left;
        return $this->nav_left;
    }

    public function redirectBack()
    {
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}

