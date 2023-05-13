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
namespace app\modules\logger\components;

use app\modules\logger\models\Log;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\HttpException;

/**
 * ErrorHandler handles uncaught PHP errors and exceptions.
 *
 * ErrorHandler displays these errors using appropriate views based on the
 * nature of the errors and the mode the application runs at.
 *
 * ErrorHandler is configured as an application component in [[\yii\base\Application]] by default.
 * You can access that instance via `Yii::$app->errorHandler`.
 *
 *
 * $config['modules']['logger'] = [
 * 'class' => 'app\modules\logger\Module',
 * ];
 * $config['components']['errorHandler'] = [
 * 'class' => 'app\modules\logger\components\TErrorHandler'
 * ];
 * For more details and usage information on ErrorHandler, see the [guide article on handling errors](guide:runtime-handling-errors).
 */
class TErrorHandler extends \yii\web\ErrorHandler
{

    public $errorAction = 'logger/log/custom-error';

    public function init()
    {
        parent::init();

        if (YII_ENV == 'dev') {
            $this->errorAction = 'site/error';
        }
    }

    /**
     * Converts an exception into an array.
     *
     * @param \Exception|\Error $exception
     *            the exception being converted
     * @return array the array representation of the exception.
     */
    protected function convertExceptionToArray($exception)
    {
        Log::addException($exception, $exception->statusCode);
        return parent::convertExceptionToArray($exception);
    }

    /**
     * Converts an exception into a simple string.
     *
     * @param \Exception|\Error|\Throwable $exception
     *            the exception being converted
     * @return string the string representation of the exception.
     */
    public static function convertExceptionToString($exception)
    {
        if ($exception instanceof UserException) {
            return "{$exception->getName()}: {$exception->getMessage()}";
        }

        if (YII_DEBUG) {
            return static::convertExceptionToVerboseString($exception);
        }

        return 'An internal server error occurred.';
    }
}
