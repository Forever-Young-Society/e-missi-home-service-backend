<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use yii\web\Session;
use yii\web\Cookie;

class TSession extends Session
{

    public function init()
    {
        $cookiePath = '/';
        $path = \Yii::$app->request->baseUrl;
        if (! empty($path)) {
            $cookiePath = $path;
        }

        $this->setCookieParams([
            'httponly' => true,
            'path' => $cookiePath,
            'sameSite' => Cookie::SAME_SITE_LAX
        ]);
        $this->name = '_session_' . \Yii::$app->id;
        $savePath = \Yii::$app->runtimePath . DIRECTORY_SEPARATOR . 'sessions';
        if (! is_dir($savePath)) {
            @mkdir($savePath, FILE_MODE, true);
        }
        $this->savePath = $savePath;
        parent::init();
    }
}