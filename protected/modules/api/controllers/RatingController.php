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
use app\modules\availability\models\SlotBooking;
use app\modules\rating\models\Rating;
use yii\data\ActiveDataProvider;

/**
 * RatingController implements the API actions for Rating model.
 */
class RatingController extends ApiBaseController
{

    public $modelClass = "app\modules\rating\models\Rating";

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
                            'add-rating'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ],
                    [
                        'actions' => [
                            'list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isServiceProvider();
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
     * @OA\Post(path="/rating/add-rating",
     *   summary="",
     *   tags={"Rating"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="provider_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(
     *       type="integer"
     *     )
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              required={"Rating[rating]"},
     *              @OA\Property(property="Rating[rating]", type="string", type="input", example="3.5",description="Rating"),
     *              @OA\Property(property="Rating[comment]", type="string", type="input", example="good",description="Review"),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Add Rating",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionAddRating($provider_id, $model_id)
    {
        $data = [];
        $this->setStatus(400);
        $model = Rating::find()->where([
            'provider_id' => $provider_id,
            'model_id' => $model_id
        ])
            ->my()
            ->one();

        if (empty($model)) {
            $model = new Rating();
        }
        $post = \Yii::$app->request->post();
        $providerModel = User::findOne($provider_id);
        if (empty($providerModel)) {
            $data['message'] = \yii::t('app', "Service provider not found");
            return $data;
        }
        if ($model->load($post)) {
            $model->state_id = User::STATE_ACTIVE;
            $model->provider_id = $providerModel->id;
            $model->model_type = SlotBooking::class;
            $model->model_id = $model_id;
            if ($model->save()) {
                $avgRating = Rating::find()->where([
                    'provider_id' => $provider_id
                ])->average('rating');
                if (! empty($avgRating)) {
                    $providerModel->avg_rating = round($avgRating, 2);
                    $providerModel->updateAttributes([
                        'avg_rating'
                    ]);
                }
                $this->setStatus(200);
                $data['detail'] = $providerModel->asProviderJson();
                $data['message'] = \Yii::t('app', 'Rating added succesfully');
            } else {
                $data['error'] = $model->getErrors();
            }
        } else {
            $data['message'] = \Yii::t('app', 'Data not posted');
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/rating/list",
     *   summary="Get Ratings",
     *   tags={"Rating"},
     *   @OA\Parameter(
     *     name="access-token",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns Ratings",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionList($type = Rating::SORT_LATEST, $page = 0)
    {
        $query = Rating::findActive()->my('provider_id');
        switch ($type) {
            case Rating::SORT_LATEST:
                $query->orderBy('id DESC');
                break;
            case Rating::SORT_HIGHEST:
                $query->orderBy('rating DESC');
                break;
            case Rating::SORT_LOWEST:
                $query->orderBy('rating ASC');
                break;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ]
        ]);

        return $dataProvider;
    }
}
