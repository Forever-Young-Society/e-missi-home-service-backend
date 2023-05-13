<?php
namespace app\modules\notification\commands;

use app\components\TConsoleController;
use app\models\EmailQueue;
use app\modules\emailreader\models\Email;
use app\modules\emailreader\models\EmailAccount;
use app\modules\smtp\models\Account;
use yii\base\Exception;
use app\modules\notification\models\Notification;

class NotificationController extends TConsoleController
{

    /**
     * Clean old Notifications
     */
    public function actionClean()
    {
        $query = Notification::find();

        $query->limit(1000)->orderBy('id asc');

        self::log("Cleaning up  Notifications: " . $query->count());
        foreach ($query->each() as $notification) {
            self::log("Deleting Notification :" . $notification->id . ' - ' . $notification);
            try {
                $notification->delete();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }

    /**
     * Delete emails
     *
     * @param boolean $truncate
     * @return number
     */
    public function actionClear($truncate = false)
    {
        $query = Notification::find()->orderBy('id ASC');

        self::log("Cleaning up  Notifications: " . $query->count());

        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                self::log('Deleting :' . $model->id);
                $model->delete();
            }
        }

        if ($truncate) {
            Notification::truncate();
        }
        return 0;
    }
}

