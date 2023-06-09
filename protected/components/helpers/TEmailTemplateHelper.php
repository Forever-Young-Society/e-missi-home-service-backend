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
namespace app\components\helpers;

use Yii;
use yii\helpers\StringHelper;

/**
 * This is the generic model class
 */
class TEmailTemplateHelper
{
    use TLogHelper;

    public static function renderFile($viewFile, $params, $userHeaders = true)
    {
        $message = '';
        // includes Header

        if ($userHeaders) {
            $message .= self::getHeaderFooter($viewFile, $params);
        }

        // Content
        $message .= \yii::$app->view->renderFile($viewFile, $params);

        // Includes Footer
        if ($userHeaders) {
            $message .= self::getHeaderFooter($viewFile, $params, 'footer.php');
        }
        return $message;
    }

    protected static function getHeaderFooter($viewFile, $params, $type = 'header.php')
    {
        $bodyFileView = Yii::getAlias($viewFile);
        $view = file_get_contents($bodyFileView);

        if (preg_match('/' . $type . '/', $view)) {
            return '';
        }
        $headerFileView = Yii::getAlias('@app/mail/' . $type);
        if (! is_file($headerFileView)) {

            $headerFileView = StringHelper::dirname($bodyFileView);
            $headerFileView .= '/' . $type;
        }
        if (is_file($headerFileView)) {
            return \yii::$app->view->renderFile($headerFileView, $params);
        }

        return '';
    }
}
