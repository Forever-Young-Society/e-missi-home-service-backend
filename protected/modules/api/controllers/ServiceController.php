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
use app\models\Language;
use app\models\User;
use app\models\UserTerm;
use app\modules\api\components\ApiBaseController;
use app\modules\service\models\Category;
use app\modules\service\models\ProviderSkill;
use app\modules\service\models\SubCategory;
use app\modules\service\models\Term;
use app\modules\workzone\models\Location;
use app\modules\workzone\models\Postcode;
use yii\data\ActiveDataProvider;
use app\modules\service\models\Report;

/**
 * ServiceController implements the API actions for Category model.
 */
class ServiceController extends ApiBaseController
{

    public $modelClass = "app\modules\service\models\Category";

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
                            'category-list',
                            'sub-category-list',
                            'language-list',
                            'term-detail',
                            'accept-term',
                            'location-list',
                            'location-zipcode',
                            'matches-list',
                            'provider-detail',
                            'skill-list',
                            'report-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isUser();
                        }
                    ],
                    [
                        'actions' => [
                            'category-list',
                            'sub-category-list',
                            'language-list',
                            'my-services',
                            'skill-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isServiceProvider();
                        }
                    ],
                    [
                        'actions' => [
                            'category-list',
                            'sub-category-list',
                            'language-list',
                            'skill-list'
                        ],
                        'allow' => true,
                        'matchCallback' => function () {
                            return User::isGuest();
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
     * @OA\Get(path="/service/category-list",
     *   summary="Get Workzone List",
     *   tags={"Service"},
     *   @OA\Response(
     *     response=200,
     *     description="Returns category list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionCategoryList($page = 0)
    {
        $query = Category::findActive();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
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
     * @OA\Get(path="/service/language-list",
     *   summary="Get Language List",
     *   tags={"Service"},
     *   @OA\Response(
     *     response=200,
     *     description="Returns language list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionLanguageList($page = 0)
    {
        $query = Language::findActive();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
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
     * @OA\Get(path="/service/sub-category-list",
     *   summary="Get Workzone List",
     *   tags={"Service"},
     *   @OA\Parameter(
     *     name="cat_id",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns category list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionSubCategoryList($cat_id, $page = 0)
    {
        $catIds = explode(',', $cat_id);
        $query = SubCategory::findActive()->andWhere([
            'in',
            'category_id',
            $catIds
        ])->andWhere([
            'type_id' => SubCategory::TYPE_SUB_SERVICE_YES
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 40,
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
     * @OA\Get(path="/service/skill-list",
     *   summary="Get skill list",
     *   tags={"Service"},
     *   @OA\Parameter(
     *     name="cat_id",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns skill list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionSkillList($cat_id, $page = 0)
    {
        $catIds = explode(',', $cat_id);
        $query = ProviderSkill::findActive()->andWhere([
            'in',
            'category_id',
            $catIds
        ])->andWhere([
            'type_id' => ProviderSkill::TYPE_SUB_SKILL_YES
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
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
     * @OA\Get(path="/service/my-services",
     *   summary="Get Workzone List",
     *   tags={"Service"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Response(
     *     response=200,
     *     description="Returns category list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionMyServices($page = 0)
    {
        $query = Category::find()->alias('c')
            ->joinWith('userCategories as uc', true, 'RIGHT JOIN')
            ->active();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
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
     * @OA\Get(path="/service/term-detail",
     *   summary="Get Workzone List",
     *   tags={"Service"},
     *   @OA\Parameter(
     *     name="cat_id",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns term detail",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionTermDetail($cat_id, $page = 0)
    {
        $data = [];
        $model = Term::findOne([
            'category_id' => $cat_id
        ]);
        if (! empty($model)) {
            $data['detail'] = $model->asJson();
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'Term Not Found');
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/service/accept-term",
     *   summary="Accept term",
     *   tags={"Service"},
     *   @OA\Parameter(
     *     name="cat_id",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Accept term",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionAcceptTerm($term_id, $page = 0)
    {
        $data = [];
        $this->setStatus(400);
        $model = Term::findOne($term_id);
        if (empty($model)) {
            $data['message'] = \Yii::t('app', 'Term and condition not found');
        } else {
            $userTerm = UserTerm::find()->where([
                'term_id' => $term_id
            ])
                ->my()
                ->one();
            if (empty($userTerm)) {
                $userTerm = new UserTerm();
            }
            $userTerm->term_id = $term_id;
            $userTerm->category_id = $model->category_id;
            if ($userTerm->save()) {
                $this->setStatus(200);
                $data['message'] = \Yii::t('app', 'Terms accepted successfully');
            } else {
                $data['error'] = $userTerm->getErrors();
            }
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/service/location-list",
     *   summary="Get Workzone List",
     *   tags={"User"},
     *   @OA\Response(
     *     response=200,
     *     description="Returns location list",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionLocationList($page = 0)
    {
        $query = Location::findActive();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
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
     * @OA\Get(path="/service/location-zipcode",
     *   summary="Get location zipcode List",
     *   tags={"Service"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\Parameter(
     *     name="location_id",
     *     in="query",
     *     @OA\Schema(
     *       type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Returns location zipcode List",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionLocationZipcode($location_id, $page = 0)
    {
        $query = Postcode::findActive()->andWhere([
            'location_id' => $location_id
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $page
            ],
            'sort' => [
                'defaultOrder' => [
                    'type_id' => SORT_ASC
                ]
            ]
        ]);
        $this->setStatus(200);
        return $dataProvider;
    }

    /**
     *
     * @OA\Post(path="/service/matches-list",
     *   summary="",
     *   tags={"Service"},
     *   security={
     *   {"bearerAuth": {}}
     *   },
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *              @OA\Property(property="SubCategory[id]", type="string", example="David",description="first name"),
     *              @OA\Property(property="SubCategory[zipcode]", type="string", example="53200",description="Email of the user"),
     *           ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Create new user",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *
     *     ),
     *   ),
     * )
     */
    public function actionMatchesList($page = 0)
    {
        $this->setStatus(400);
        $data = [];
        $model = new SubCategory();
        $post = \Yii::$app->request->post();
        if (! $model->load($post)) {
            $data['message'] = \yii::t('app', "No data posted.");
            return $data;
        }
        $service_id = explode(',', $model->service_ids);
        $subTypeModel = SubCategory::findOne($service_id[0]);
        if (empty($subTypeModel)) {
            $data['message'] = \yii::t('app', "Service not found");
            return $data;
        }
        $provider = $subTypeModel->getLocationProviderIds($model->zipcode);
        $query = User::findActive()->andWhere([
            'in',
            'id',
            $provider
        ])->andWhere([
            'role_id' => User::ROLE_SERVICE_PROVIDER,
            'is_approve' => User::IS_APPROVE
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
     * @OA\Get(path="/service/provider-detail",
     *   summary="",
     *   tags={"Service"},
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
     *     description="provider detail",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *   ),
     * )
     */
    public function actionProviderDetail($id)
    {
        $data = [];
        $model = User::findOne($id);
        if (! empty($model)) {
            $data['detail'] = $model->asServiceJson();
        } else {
            $this->setStatus(400);
            $data['message'] = \Yii::t('app', 'User Not Found');
        }
        return $data;
    }

    /**
     *
     * @OA\Get(path="/service/report-list",
     *   summary="Get report List",
     *   tags={"Service"},
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
        $query = Report::findActive()->my('user_id');
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
