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
namespace app\modules\availability\controllers;

use app\components\TController;
use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;
use app\modules\availability\models\SlotBooking;
use yii2fullcalendar\models\Event;

/**
 * Default controller for the `availability` module
 */
class DefaultController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'revenue'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isAdmin();
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex()
    {
        $list = [];
        $model = new SlotBooking();
        $schedules = SlotBooking::find()->select('date(start_time)')
            ->distinct()
            ->column();
        foreach ($schedules as $date) {

            $scheduleModel = SlotBooking::find()->where([
                'date(start_time)' => $date
            ])->one();
            $event = new Event();
            $start_time = $scheduleModel->start_time;
            $end_time = $scheduleModel->end_time;
            $start_date = date('Y-m-d', strtotime($scheduleModel->start_time));
            $end_date = date('Y-m-d', strtotime($scheduleModel->end_time));
            $event->start = date('Y-m-d\TH:i:s\Z', strtotime($start_time));
            $event->end = date('Y-m-d\TH:i:s\Z', strtotime($end_time));
            $event->title = SlotBooking::getTodayBookingCount($start_date) . ' Bookings - ' . User::DEFAULT_CURRENCY . SlotBooking::getBookingRevenue($end_date);
            $event->id = $scheduleModel->id;
            $event->backgroundColor = 'green';
            $event->borderColor = 'green';

            $event->textColor = 'white';
            $event->resourceId = $scheduleModel->id;
            $event->editable = true;
            $list[] = $event;
        }

        return $this->render('index', [
            'events' => $list,
            'model' => $model
        ]);
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionRevenue()
    {
        return $this->render('revenue-index');
    }
}
