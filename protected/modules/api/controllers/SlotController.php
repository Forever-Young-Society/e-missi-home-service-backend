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
namespace app\modules\api\controllers;

use app\components\filters\AccessControl;
use app\components\filters\AccessRule;
use app\models\User;
use app\modules\api\components\ApiBaseController;
use app\modules\availability\models\Slot;
use yii\data\ActiveDataProvider;
use app\modules\availability\models\ProviderSlot;
use yii\helpers\Json;
use app\modules\availability\models\SlotBooking;
use app\modules\notification\models\Notification;
use app\modules\service\models\SubCategory;
use app\modules\availability\models\BookingService;
use app\modules\service\models\Report;
use app\modules\service\models\Category;

/**
 * SlotController implements the API actions for Slot model.
 */
class SlotController extends ApiBaseController
{

    public $modelClass = "app\modules\availability\models\Slot";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class
                ],
                'rules' => [
                    [
                        'actions' => [
                            'list',
                            'add-schedule',
                            'get-provider-slot',
                            'availability',
                            'booking-detail',
                            'provider-bookings',
                            'current-duty',
                            'start-booking',
                            'complete-booking',
                            'booking-report',
                            'report-detail',
                            'report-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isServiceProvider();
                        }
                    ],
                    [
                        'actions' => [
                            'get-provider-slot',
                            'availability',
                            'booking',
                            // 'payment',
                            'booking-detail',
                            'edit-booking',
                            'user-bookings',
                            'cancel-booking',
                            'reschedule-booking',
                            'report-detail',
                            'payment-history'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ]
                ]
            ]
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        return $actions;
    }

    /**
     *
     * @OA\Get(path="/slot/list",
     *   summary="Get slot List",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Returns slot List",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionList($cat_id, $start_time, $end_time, $page = 0)
    {
        $data = [];
        $this->setStatus(400);
        $categoryModel = Category::findOne($cat_id);
        if (empty($categoryModel)) {
            $data['message'] = \Yii::t('app', "Service not found");
            return $data;
        }
        $date = date("Y-m-d", strtotime($start_time));
        $query = Slot::findActive()->andWhere([
            'type_id' => $categoryModel->type_id
        ]);
        if ($date == date('Y-m-d')) {
            $query->andWhere([
                '>=',
                'start_time',
                date('H:i:s')
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Post(path="/slot/add-schedule",
     *   summary="Add availability schedule",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(
     *              property="ProviderSlot[availability]",
     *              type="text", format="text",
     *              example="",
     *              description="Enter availability json"
     *              ),
     *              @OA\Property(
     *              property="ProviderSlot[type_id]",
     *              type="integer", format="integer",
     *              example="4",
     *              description="Enter service type"
     *              ),
     *
     *           ),
     *       ),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    // [{"start_time":"Y-m-d H:i:s","end_time":"Y-m-d H:i:s","slot_id":5},{"start_time":"Y-m-d H:i:s","end_time":"Y-m-d H:i:s","slot_id":5}]
    public function actionAddSchedule($start_time, $end_time)
    {
        $data = [];
        $this->setStatus(400);
        $provider_slot = new ProviderSlot();
        $post = \Yii::$app->request->post();
        if ($provider_slot->load($post)) {
            $service = Category::findOne($provider_slot->type_id);
            if (empty($service)) {
                $data['message'] = \Yii::t('app', "Service not found");
                return $data;
            }
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $json = $provider_slot->slot;

                $array = Json::decode($json, false);
                ProviderSlot::deleteAll([
                    'and',
                    [
                        'created_by_id' => \Yii::$app->user->id,
                        'type_id' => $service->type_id
                    ],
                    [
                        'between',
                        'start_time',
                        $start_time,
                        $end_time
                    ]
                ]);
                foreach ($array as $value) {
                    $exist_slot = ProviderSlot::find()->my()
                        ->andWhere([
                        'availability_slot_id' => $value->id,
                        'start_time' => $value->start_time,
                        'end_time' => $value->end_time
                    ])
                        ->one();
                    if (empty($exist_slot)) {
                        $slot = new ProviderSlot();
                        $slot->availability_slot_id = $value->id;
                        $slot->start_time = $value->start_time;
                        $slot->end_time = $value->end_time;
                        $slot->type_id = $service->type_id;
                        $slot->state_id = ProviderSlot::STATE_ACTIVE;
                        if (! $slot->save()) {
                            $transaction->rollBack();
                            $data['error'] = $slot->getErrors();
                            return $data;
                        }
                    }
                }
                $transaction->commit();
                $this->setStatus(200);
                $data['message'] = \Yii::t('app', "Availability saved successfully.");
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                $data['error'] = \yii::t('app', $e->getMessage());
                return $data;
            }
        } else {
            $data['message'] = "Data not posted.";
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/slot/get-provider-slot",
     *   summary="Add availability schedule",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="reschedule_time",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(
     *              property="ProviderSlot[provider_id]",
     *              type="integer", format="integer",
     *              example="",
     *              description="Enter provider id"
     *              ),
     *              @OA\Property(
     *              property="ProviderSlot[type_id]",
     *              type="integer", format="integer",
     *              example="4",
     *              description="Enter service type"
     *              ),
     *              @OA\Property(
     *              property="ProviderSlot[start_time]",
     *              type="string", format="string",
     *              example="2022-12-12",
     *              description="Enter start time"
     *              ),
     *              @OA\Property(
     *              property="ProviderSlot[end_time]",
     *              type="string", format="string",
     *              example="2022-12-12",
     *              description="Enter end time"
     *              ),
     *           ),
     *       ),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionGetProviderSlot($reschedule_time = '')
    {
        $data = [];
        $this->setStatus(400);
        $provider_slot = new ProviderSlot();
        $post = \Yii::$app->request->post();
        if (! $provider_slot->load($post)) {
            $data['message'] = "Data not posted.";
            return $data;
        }
        $service = Category::findOne($provider_slot->type_id);
        if (empty($service)) {
            $data['message'] = \Yii::t('app', "Service not found");
            return $data;
        }
        $provider_slot->type_id = $service->type_id;
        // $this->setStatus(400);
        if (! $provider_slot->checkIsSlotExists()) {
            $data['list'] = [];
            return $data;
        }

        /* array of doctor slots */
        $query = ProviderSlot::find()->where([
            'created_by_id' => $provider_slot->provider_id,
            'type_id' => $provider_slot->type_id
        ])
            ->andWhere([
            'between',
            'start_time',
            $provider_slot->start_time,
            $provider_slot->end_time
        ])
            ->andWhere([
            '>=',
            'start_time',
            date("Y-m-d H:i:s")
        ]);

        if (! empty($reschedule_time)) {
            $query->andWhere([
                '>',
                'start_time',
                $reschedule_time
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Get(path="/slot/availability",
     *   summary="Add availability schedule",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="provider_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="service_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns availability schedule",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionAvailability($provider_id, $service_id, $date, $page = 0)
    {
        $data = [];
        $available_dates = [];
        $unavailable_dates = [];
        $service = Category::findOne($service_id);
        if (empty($service)) {
            $data['available_list'] = $available_dates;
            $data['blockout_list'] = $unavailable_dates;
            return $data;
        }
        $unavailable_dates = [];
        $date = explode('-', $date);
        $available_dates = ProviderSlot::findActive()->select('date(start_time)')
            ->andWhere([
            'created_by_id' => $provider_id,
            'type_id' => $service->type_id
        ])
            ->andWhere([
            'Year(start_time)' => $date[0],
            'Month(start_time)' => $date[1]
        ])
            ->andWhere([
            '>=',
            'start_time',
            date("Y-m-d H:i:s")
        ])
            ->distinct()
            ->column();

        $unavailable_dates = ProviderSlot::getUnAvailableDates($available_dates, $provider_id, $service_id);
        $data['available_list'] = $available_dates;
        $data['blockout_list'] = $unavailable_dates;

        return $data;
    }

    /**
     *
     * @OA\Post(path="/slot/booking",
     *   summary="Slot booking",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(
     *              property="SlotBooking[provider_id]",
     *              type="integer", format="integer",
     *              example="2",
     *              description="Enter provider id"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[service_ids]",
     *              type="integer", format="integer",
     *              example="2",
     *              description="Enter service id"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[dependant_id]",
     *              type="integer", format="integer",
     *              example="2",
     *              description="Enter dependant id"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[workzone_id]",
     *              type="integer", format="integer",
     *              example="2",
     *              description="Enter workzone id"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[zipcode]",
     *              type="string", format="text",
     *              example="160071",
     *              description="Enter zipcode"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[type_id]",
     *              type="integer", format="integer",
     *              example="4",
     *              description="Enter booking type"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[slot_id]",
     *              type="integer", format="integer",
     *              example="4",
     *              description="Enter slot id"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[start_time]",
     *              type="string", format="string",
     *              example="2022-12-12",
     *              description="Enter start time"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[end_time]",
     *              type="string", format="string",
     *              example="2022-12-12",
     *              description="Enter end time"
     *              ),
     *           ),
     *       ),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionBooking()
    {
        $data = [];
        $this->setStatus(400);
        $bookingModel = new SlotBooking();
        $post = \Yii::$app->request->post();
        if ($bookingModel->load($post)) {
            if ($bookingModel->checkIsBookingExists()) {
                $data['error'] = \Yii::t('app', "This slot is already booked.");
                return $data;
            }
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $arr = explode(',', $bookingModel->service_ids);
                $serviceModel = SubCategory::findOne($arr[0]);
                if (empty($serviceModel)) {
                    $data['error'] = \Yii::t('app', "Service not found");
                    $transaction->rollBack();
                    return $data;
                }
                $bookingModel->service_id = ($serviceModel->type_id == SubCategory::TYPE_SUB_SERVICE_YES) ? $serviceModel->id : $serviceModel->parent_id;
                $bookingModel->state_id = SlotBooking::STATE_PENDING;
                $bookingModel->user_amount = $serviceModel->price;
                $bookingModel->provider_amount = $serviceModel->provider_price;
                $bookingModel->admin_revenue = $bookingModel->getAdminRevenue();
                if ($bookingModel->type_id == SlotBooking::TYPE_SELF) {
                    $bookingModel->dependant_id = SlotBooking::STATE_PENDING;
                }
                if ($bookingModel->save()) {
                    $bookingModel->order_id = $bookingModel->uniqueOrderId();
                    $bookingModel->updateAttributes([
                        'order_id'
                    ]);
                    foreach ($arr as $service_id) {
                        $service = SubCategory::findOne($service_id);
                        if (empty($service)) {
                            $data['error'] = \Yii::t('app', "Service not found");
                            $transaction->rollBack();
                            return $data;
                        }
                        $booking_service = new BookingService();
                        $booking_service->title = $service->title;
                        $booking_service->booking_id = $bookingModel->id;
                        $booking_service->service_id = $service_id;
                        if (! $booking_service->save()) {
                            $data['error'] = $booking_service->getErrors();
                            $transaction->rollBack();
                            return $data;
                        }
                    }
                    $transaction->commit();
                    $this->setStatus(200);
                    // $bookindModel->sendBookingRequestmailtoDoctor();
                    $data['details'] = $bookingModel->asJson();
                    $data['message'] = \Yii::t('app', "Booking added successfully.");
                } else {
                    $transaction->rollBack();
                    $data['error'] = $bookingModel->getErrors();
                }
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                $data['error'] = \yii::t('app', $e->getMessage());
                return $data;
            }
        } else {
            $data['error'] = \Yii::t('app', "Data not posted.");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/slot/edit-booking",
     *   summary="Edit slot booking",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="booking_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(
     *              property="SlotBooking[slot_id]",
     *              type="integer", format="integer",
     *              example="4",
     *              description="Enter slot id"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[start_time]",
     *              type="string", format="string",
     *              example="2022-12-12",
     *              description="Enter start time"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[end_time]",
     *              type="string", format="string",
     *              example="2022-12-12",
     *              description="Enter end time"
     *              ),
     *           ),
     *       ),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionEditBooking($booking_id)
    {
        $data = [];
        $this->setStatus(400);
        $bookingModel = SlotBooking::findOne($booking_id);
        if (empty($bookingModel)) {
            $data['message'] = \Yii::t('app', "Booking not found");
            return $data;
        }
        $post = \Yii::$app->request->post();
        if ($bookingModel->load($post)) {
            if ($bookingModel->checkIsBookingExists()) {
                $data['message'] = \Yii::t('app', "This slot is already booked.");
                return $data;
            }
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                if ($bookingModel->save()) {
                    $transaction->commit();
                    $this->setStatus(200);
                    // $bookindModel->sendBookingRequestmailtoDoctor();
                    $data['details'] = $bookingModel->asJson();
                    $data['message'] = \Yii::t('app', "Booking updated successfully.");
                } else {
                    $transaction->rollBack();
                    $data['error'] = $bookingModel->getErrors();
                }
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                $data['error'] = \yii::t('app', $e->getMessage());
                return $data;
            }
        } else {
            $data['error'] = \Yii::t('app', "Data not posted.");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/slot/reschedule-booking",
     *   summary="Edit slot booking",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="booking_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(
     *              property="SlotBooking[slot_id]",
     *              type="integer", format="integer",
     *              example="4",
     *              description="Enter slot id"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[start_time]",
     *              type="string", format="string",
     *              example="2022-12-12",
     *              description="Enter start time"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[end_time]",
     *              type="string", format="string",
     *              example="2022-12-12",
     *              description="Enter end time"
     *              ),
     *           ),
     *       ),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionRescheduleBooking($booking_id)
    {
        $data = [];
        $this->setStatus(400);
        $user = \Yii::$app->user->identity;
        $bookingModel = SlotBooking::findOne($booking_id);
        if (empty($bookingModel)) {
            $data['message'] = \Yii::t('app', "Booking not found");
            return $data;
        }
        $old_start_time = $bookingModel->start_time;
        $old_end_time = $bookingModel->end_time;
        $old_start = date('h:i a', strtotime($old_start_time));
        $post = \Yii::$app->request->post();
        if ($bookingModel->load($post)) {
            if ($bookingModel->checkIsBookingExists()) {
                $data['message'] = \Yii::t('app', "This slot is already booked.");
                return $data;
            }
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $bookingModel->old_start_time = $old_start_time;
                $bookingModel->old_end_time = $old_end_time;
                $bookingModel->is_reschedule_confirm = SlotBooking::USER_RESCHEDULE_YES;
                $bookingModel->user_reschedule = SlotBooking::USER_RESCHEDULE_YES;
                $new_start = date('h:i a', strtotime($bookingModel->start_time));
                if ($bookingModel->save()) {
                    $message = \Yii::t('app', $user->full_name . ' has rescheduled booking from ' . $old_start . ' to ' . $new_start);
                    Notification::create([
                        'to_user_id' => $bookingModel->provider_id,
                        'created_by_id' => $bookingModel->created_by_id,
                        'title' => $message,
                        'model' => $bookingModel,
                        'type_id' => Notification::TYPE_BOOKING_NOTIFICATION
                    ], false);
                    $transaction->commit();
                    $this->setStatus(200);
                    $data['details'] = $bookingModel->asJson();
                    $data['message'] = \Yii::t('app', "Booking Rescheduled Successfully.");
                } else {
                    $transaction->rollBack();
                    $data['error'] = $bookingModel->getErrors();
                }
            } catch (\yii\base\Exception $e) {
                $transaction->rollBack();
                $data['error'] = \yii::t('app', $e->getMessage());
                return $data;
            }
        } else {
            $data['error'] = \Yii::t('app', "Data not posted.");
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/slot/payment",
     *   summary="Slot booking",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="booking_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(
     *              property="SlotBooking[transaction_id]",
     *              type="string", format="text",
     *              example="",
     *              description="Enter transaction id"
     *              ),
     *              @OA\Property(
     *              property="SlotBooking[description]",
     *              type="string", format="text",
     *              example="",
     *              description="Enter payment response"
     *              ),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionPayment($booking_id)
    {
        $data = [];
        $this->setStatus(400);
        $user = \Yii::$app->user->identity;
        $bookingModel = SlotBooking::findOne($booking_id);
        if (empty($bookingModel)) {
            $data['message'] = \Yii::t('app', 'No booking found');
            return $data;
        }
        if ($bookingModel->checkIsBookingExists()) {
            $data['message'] = \Yii::t('app', "This slot is already booked by someone please choose another slot.");
            return $data;
        }
        $post = \Yii::$app->request->post();
        if (! $bookingModel->load($post)) {
            $data['message'] = \Yii::t('app', 'Data not posted.');
            return $data;
        }
        if (empty($bookingModel->transaction_id)) {
            $data['message'] = \Yii::t('app', 'Transaction can not be blank');
            return $data;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $bookingModel->payment_status = SlotBooking::PAYMENT_SUCCESS;
            if (! $bookingModel->save()) {
                $data['error'] = $bookingModel->getErrors();
                $transaction->rollBack();
                return $data;
            }
            $bookingModel->sendBookingNotificationToUser();
            $bookingModel->sendBookingNotificationToProvider($user->full_name);
            $this->setStatus(200);
            $transaction->commit();
            $data['message'] = \Yii::t('app', 'Payment completed successfully');
            $data['details'] = $bookingModel->asJson();
        } catch (\Exception $e) {
            $data['message'] = $e->getMessage();
            $transaction->rollBack();
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/slot/booking-detail",
     *   summary="",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Booking detail",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionBookingDetail($id)
    {
        $data = [];
        $model = SlotBooking::findOne($id);
        if (! empty($model)) {
            $data['detail'] = $model->asJson();
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'Booking not found');
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/slot/provider-bookings",
     *   summary="",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="state_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Booking detail",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionProviderBookings($state_id, $page = 0)
    {
        $state_arr = SlotBooking::getProviderBookingStates($state_id);
        $booking = SlotBooking::find()->my('provider_id')
            ->andWhere([
            'payment_status' => SlotBooking::PAYMENT_SUCCESS
        ])
            ->andWhere([
            'in',
            'state_id',
            $state_arr
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $booking,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Get(path="/slot/current-duty",
     *   summary="",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Current booking list",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionCurrentDuty($page = 0)
    {
        $time = date('Y-m-d H:i:s', strtotime("+5 minute"));
        $booking = SlotBooking::find()->my('provider_id')
            ->andWhere([
            'in',
            'state_id',
            [
                SlotBooking::STATE_PENDING,
                SlotBooking::STATE_INPROGRESS
            ]
        ])
            ->andWhere([
            'payment_status' => SlotBooking::PAYMENT_SUCCESS
        ]);
        // ->andWhere([
        // '<=',
        // 'start_time',
        // $time
        // ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $booking,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Get(path="/slot/user-bookings",
     *   summary="",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="booking_type",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Booking detail",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionUserBookings($booking_type, $page = 0)
    {
        $state_arr = SlotBooking::getBookingStates($booking_type);
        $booking = SlotBooking::find()->my()
            ->andWhere([
            'payment_status' => SlotBooking::PAYMENT_SUCCESS
        ])
            ->andWhere([
            'in',
            'state_id',
            $state_arr
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $booking,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Get(path="/slot/payment-history",
     *   summary="",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Booking detail",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionPaymentHistory($page = 0)
    {
        $booking = SlotBooking::find()->my()->andWhere([
            '!=',
            'payment_status',
            SlotBooking::PAYMENT_PENDING
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $booking,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Post(path="/slot/cancel-booking",
     *   summary="Cancel booking",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="booking_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(
     *              property="SlotBooking[cancel_reason]",
     *              type="string", format="text",
     *              example="",
     *              description="Enter transaction id"
     *              ),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns canceled booking info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionCancelBooking($booking_id)
    {
        $data = [];
        $this->setStatus(400);
        $bookingModel = SlotBooking::find()->where([
            'id' => $booking_id
        ])
            ->my()
            ->one();
        if (empty($bookingModel)) {
            $data['message'] = \Yii::t('app', "No booking found.");
            return $data;
        }
        if ($bookingModel->state_id == SlotBooking::STATE_CANCELED) {
            $data['message'] = \Yii::t('app', "Booking is already canceled.");
            return $data;
        }
        $post = \Yii::$app->request->post();
        if (! $bookingModel->load($post)) {
            $data['message'] = \Yii::t('app', "Data not posted.");
            return $data;
        }
        $bookingModel->state_id = SlotBooking::STATE_CANCELED;
        $bookingModel->cancel_date = date('Y-m-d H:i:s');
        if (! $bookingModel->save()) {
            $data['error'] = $bookingModel->getErrors();
            return $data;
        }
        $message = \Yii::t('app', 'Booking canceled by user');
        Notification::create([
            'to_user_id' => $bookingModel->provider_id,
            'created_by_id' => $bookingModel->created_by_id,
            'title' => $message,
            'model' => $bookingModel,
            'type_id' => Notification::TYPE_BOOKING_NOTIFICATION
        ], false);
        $this->setStatus(200);
        $data['detail'] = $bookingModel->asJson();
        $data['message'] = \Yii::t('app', "Booking canceled successfully.");

        return $data;
    }

    /**
     *
     * @OA\Get(path="/slot/start-booking",
     *   summary="",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="booking_type",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Booking detail",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionStartBooking($booking_id)
    {
        $data = [];
        $this->setStatus(400);
        $bookingModel = SlotBooking::find()->where([
            'id' => $booking_id
        ])
            ->my('provider_id')
            ->one();
        if (empty($bookingModel)) {
            $data['message'] = \Yii::t('app', "No booking found.");
            return $data;
        }
        if ($bookingModel->state_id == SlotBooking::STATE_INPROGRESS) {
            $data['message'] = \Yii::t('app', "Booking is already inprogress.");
            return $data;
        }
        $bookingModel->state_id = SlotBooking::STATE_INPROGRESS;
        if (! $bookingModel->save()) {
            $data['error'] = $bookingModel->getErrors();
            return $data;
        }
        $this->setStatus(200);
        $data['detail'] = $bookingModel->asJson();
        $message = \Yii::t('app', 'Booking has been started');
        Notification::create([
            'to_user_id' => $bookingModel->created_by_id,
            'created_by_id' => $bookingModel->provider_id,
            'title' => $message,
            'model' => $bookingModel,
            'type_id' => Notification::TYPE_BOOKING_NOTIFICATION
        ], false);
        $data['message'] = \Yii::t('app', "Booking started successfully.");

        return $data;
    }

    /**
     *
     * @OA\Get(path="/slot/complete-booking",
     *   summary="",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="booking_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Booking detail",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionCompleteBooking($booking_id)
    {
        $data = [];
        $this->setStatus(400);
        $bookingModel = SlotBooking::find()->where([
            'id' => $booking_id
        ])
            ->my('provider_id')
            ->one();
        if (empty($bookingModel)) {
            $data['message'] = \Yii::t('app', "No booking found.");
            return $data;
        }
        if ($bookingModel->state_id == SlotBooking::STATE_COMPLETED) {
            $data['message'] = \Yii::t('app', "Booking is already completed.");
            return $data;
        }
        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $bookingModel->state_id = SlotBooking::STATE_COMPLETED;
            if (! $bookingModel->save()) {
                $transaction->rollBack();
                $data['error'] = $bookingModel->getErrors();
                return $data;
            }
            if (! $bookingModel->saveServiceReport()) {
                $transaction->rollBack();
                $data['error'] = \Yii::t('app', "Error while saving service report");
                return $data;
            }
            $transaction->commit();
            $this->setStatus(200);
            $message = \Yii::t('app', 'Booking completed successfully');
            Notification::create([
                'to_user_id' => $bookingModel->created_by_id,
                'created_by_id' => $bookingModel->provider_id,
                'title' => $message,
                'model' => $bookingModel,
                'type_id' => Notification::TYPE_BOOKING_NOTIFICATION
            ], false);
            $data['detail'] = ! empty($bookingModel->reportDetail) ? $bookingModel->reportDetail->asJson() : (object) [];
            $data['message'] = \Yii::t('app', "Booking completed successfully.");
        } catch (\yii\base\Exception $e) {
            $transaction->rollBack();
            $data['error'] = \yii::t('app', $e->getMessage());
            return $data;
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/slot/report-detail",
     *   summary="",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="booking_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Booking report detail",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionReportDetail($booking_id)
    {
        $data = [];
        $model = Report::findOne([
            'booking_id' => $booking_id
        ]);
        if (! empty($model)) {
            $data['detail'] = $model->asJson();
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'Booking report not found');
        }
        return $data;
    }

    /**
     *
     * @OA\Post(path="/slot/booking-report",
     *   summary="Booking report",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="booking_id",
     *     in="query",
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(
     *              property="Report[description]",
     *              type="string", format="text",
     *              example="Good",
     *              description="Enter service report"
     *              ),
     *           ),
     *       ),
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Returns newly created user info",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionBookingReport($booking_id)
    {
        $data = [];
        $this->setStatus(400);
        $bookingModel = SlotBooking::findOne($booking_id);
        if (empty($bookingModel)) {
            $data['message'] = \Yii::t('app', "Booking not found");
            return $data;
        }
        $report = Report::findOne([
            'booking_id' => $booking_id
        ]);
        if (empty($report)) {
            $data['message'] = \Yii::t('app', "Booking report not found");
            return $data;
        }
        $post = \Yii::$app->request->post();
        if ($report->load($post)) {
            $report->state_id = Report::STATE_COMPLETE;
            if ($report->save()) {
                $this->setStatus(200);
                $data['details'] = $report->asJson();
                $data['message'] = \Yii::t('app', "Booking report added successfully.");
            } else {
                $data['error'] = $report->getErrors();
            }
        } else {
            $data['error'] = \Yii::t('app', "Data not posted.");
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/slot/report-list",
     *   summary="Get report List",
     *   tags={"Slot"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Returns report List",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionReportList($page = 0)
    {
        $query = Report::find()->my();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);
        return $dataProvider;
    }
}
