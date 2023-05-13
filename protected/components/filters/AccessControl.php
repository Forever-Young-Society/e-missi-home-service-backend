<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\filters;

use Yii;
use yii\base\Action;
use yii\base\Module;
use yii\helpers\Url;

class AccessControl extends \yii\filters\AccessControl
{

    public $ruleConfig = [
        'class' => AccessRule::class
    ];

    /**
     *
     * @var array
     */
    public $params = [];

    /**
     *
     * @var array list of actions that not need to check access
     */
    public $allowActions = [];

    /**
     *
     * @inheritdoc
     */
    public function beforeAction($action): bool
    {
        $fileaccess = $this->beforeActionSkip($action);
        $ret = Yii::$app->user->canRoute(Yii::$app->controller->module->id, $action->getUniqueId(), true, $fileaccess);
        if (! $ret) {
            if ($this->denyCallback !== null) {
                call_user_func($this->denyCallback, null, $action);
            } else {
                $this->denyAccess($this->user);
            }
        }
        return $ret;
    }

    public function beforeActionSkip($action)
    {
        $user = $this->user;
        $request = Yii::$app->getRequest();
        /* @var $rule AccessRule */
        foreach ($this->rules as $rule) {
            if ($rule->allows($action, $user, $request)) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @inheritdoc
     */
    protected function isActive($action): bool
    {
        if ($this->isErrorPage($action) || $this->isLoginPage($action) || $this->isAllowedAction($action)) {
            return false;
        }

        return parent::isActive($action);
    }

    /**
     * Returns a value indicating whether a current url equals `errorAction` property of the ErrorHandler component
     *
     * @param Action $action
     *
     * @return bool
     */
    private function isErrorPage(Action $action): bool
    {
        if ($action->getUniqueId() === Yii::$app->getErrorHandler()->errorAction) {
            return true;
        }

        return false;
    }

    /**
     * Returns a value indicating whether a current url equals `loginUrl` property of the User component
     *
     * @param Action $action
     *
     * @return bool
     */
    private function isLoginPage(Action $action): bool
    {
        $url = Url::to(Yii::$app->user->loginUrl);
        if (empty($url)) {
            return false;
        }
        $loginUrl = trim($url, '/');

        if (Yii::$app->user->isGuest && $action->getUniqueId() === $loginUrl) {
            return true;
        }

        return false;
    }

    /**
     * Returns a value indicating whether a current url exists in the `allowActions` list.
     *
     * @param Action $action
     *
     * @return bool
     */
    private function isAllowedAction(Action $action): bool
    {
        if ($this->owner instanceof Module) {
            $ownerId = $this->owner->getUniqueId();
            $id = $action->getUniqueId();
            if (! empty($ownerId) && strpos($id, $ownerId . '/') === 0) {
                $id = substr($id, strlen($ownerId) + 1);
            }
        } else {
            $id = $action->id;
        }

        foreach ($this->allowActions as $route) {
            if (substr($route, - 1) === '*') {
                $route = rtrim($route, '*');
                if ($route === '' || strpos($id, $route) === 0) {
                    return true;
                }
            } else {
                if ($id === $route) {
                    return true;
                }
            }
        }

        return false;
    }
}