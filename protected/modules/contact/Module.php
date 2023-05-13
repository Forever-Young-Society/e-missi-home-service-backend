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
namespace app\modules\contact;

use app\components\TController;
use app\components\TModule;
use app\models\User;
use app\modules\contact\assets\ContactAsset;

/**
 * contact module definition class
 */
class Module extends TModule
{

    const NAME = 'contact';

    public $controllerNamespace = 'app\modules\contact\controllers';

    // public $defaultRoute = 'information';
    public $enableAck = false;

    /**
     *
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (\Yii::$app instanceof \yii\web\Application) {
                ContactAsset::register(\Yii::$app->getView());
            }
            return true;
        }
        return false;
    }

    public static function subNav()
    {
        return TController::addMenu(\Yii::t('app', 'Contacts'), '//contact', 'phone', (Module::isManager()), [
            TController::addMenu(\Yii::t('app', 'Home'), '//contact', 'home', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Addresses'), '//contact/address', 'map', (Module::isManager())),
            TController::addMenu(\Yii::t('app', 'Phones'), '//contact/phone', 'phone', (Module::isManager())),
            TController::addMenu(\Yii::t('app', 'Information'), '//contact/information', 'list', (Module::isManager())),
            TController::addMenu(\Yii::t('app', 'Chatscripts'), '//contact/chatscript', 'comment', Module::isManager()),
            TController::addMenu(\Yii::t('app', 'Social Links'), '//contact/social-link', 'list', Module::isManager())
        ]);
    }

    public static function dbFile()
    {
        return __DIR__ . '/db/install.sql';
    }

    public static function getRules()
    {
        return [
            'contact-us' => '/contact/information/info-address',
            'meeting' => '/contact/information/meeting',
            'contact/request/demo' => 'contact/information/info-address',
            'contact/request/quote' => 'contact/information/info-address',
            'contact/request/thankyou' => 'contact/information/thankyou'
        ];
    }

    public static function getCronJobs()
    {
        return [
            "* * * * * \t /contact/information/mark-spam"
        ];
    }
}
