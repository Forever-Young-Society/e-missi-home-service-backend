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
namespace app\modules\api\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\filters\auth\AuthInterface;
use yii\filters\auth\AuthMethod;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\UnauthorizedHttpException;

/**
 * TRestAuth is an action filter that supports multiple authentication methods at the same time.
 *
 * The authentication methods contained by CompositeAuth are configured via [[authMethods]],
 * which is a list of supported authentication class configurations.
 *
 * The following example shows how to support three authentication methods:
 *
 * ```php
 * public function behaviors()
 * {
 * return [
 * 'compositeAuth' => [
 * 'class' => TRestAuth::className(),
 *
 * ],
 * ];
 * }
 * ```
 */
class TRestAuth extends AuthMethod
{

    public $authMethods = [
        HttpBasicAuth::class,
        HttpBearerAuth::class, // param : Authorization : Bearer L7b9G9n_jbw3oj8-G1X_t-Jg2FUNMcm1
        QueryParamAuth::class // param : access-token
    ];

    /**
     *
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if (empty($this->authMethods)) {
            return true;
        }

        $response = $this->response ?: Yii::$app->getResponse();

        try {
            $this->authenticate($this->user ?: Yii::$app->getUser(), $this->request ?: Yii::$app->getRequest(), $response);
        } catch (UnauthorizedHttpException $e) {

            throw $e;
        }

        return true;
    }

    /**
     *
     * {@inheritdoc}
     */
    public function authenticate($user, $request, $response)
    {
        foreach ($this->authMethods as $i => $auth) {
            if (! $auth instanceof AuthInterface) {
                $this->authMethods[$i] = $auth = Yii::createObject($auth);
                if (! $auth instanceof AuthInterface) {
                    throw new InvalidConfigException(get_class($auth) . ' must implement yii\filters\auth\AuthInterface');
                }
            }

            $identity = $auth->authenticate($user, $request, $response);
            if ($identity !== null) {
                return $identity;
            }
        }

        return null;
    }

    /**
     *
     * {@inheritdoc}
     */
    public function challenge($response)
    {
        foreach ($this->authMethods as $method) {
            /* @var $method AuthInterface */
            $method->challenge($response);
        }
    }
}