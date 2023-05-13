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

use yii\base\Exception;
use yii\helpers\FileHelper;
use Yii;

/**
 * Setup Commands for first time
 *
 * @author shiv
 *        
 */
class TFileHelper extends FileHelper
{

    public static function getTempDirectory()
    {
        $tmpDir = \Yii::$app->runtimePath . '/tmp';

        if (! is_dir($tmpDir) && (! @mkdir($tmpDir, FILE_MODE) && ! is_dir($tmpDir))) {
            throw new Exception('temp directory does not exist');
        }

        return $tmpDir;
    }

    /**
     * generate temmp file name
     *
     * @param $ext 'txt'
     */
    public static function getTempFile($prefix = 'temp', $ext = null)
    {
        $tmpDir = self::getTempDirectory();

        return tempnam($tmpDir, $prefix) . '.' . $ext;
    }

    public function removeRootDirectory($dir = null)
    {
        if ($dir == null) {
            $dir = Yii::getAlias('@app');
        }
        if (is_dir($dir)) {
            parent::removeDirectory($dir);
        }
    }
}