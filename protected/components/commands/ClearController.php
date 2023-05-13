<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\commands;

use app\base\TDefaultData;
use app\components\TConsoleController;
use app\models\EmailQueue;
use app\models\File;
use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;
use app\models\LoginHistory;
use app\models\Feed;

class ClearController extends TConsoleController
{

    public function actionChar()
    {
        self::log('actionChar');
        $name = \Yii::$app->db->createCommand()
            ->setSql("select database()")
            ->queryScalar();

        \Yii::$app->db->createCommand()
            ->setSql(" ALTER DATABASE `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")
            ->execute();
        foreach (\Yii::$app->db->schema->tableNames as $table) {

            self::log("character " . $table);
            try {
                \Yii::$app->db->createCommand()
                    ->setSql("ALTER TABLE
                `$table`
                CONVERT TO CHARACTER SET utf8mb4
                COLLATE utf8mb4_unicode_ci")
                    ->execute();
            } catch (Exception $e) {
                echo $e->getTraceAsString();
            }
        }
    }

    public function actionIndex()
    {
        $this->actionRuntime();
        $this->actionAsset();
    }

    /**
     * Add default data
     */
    public function actionDefault()
    {
        $this->actionRuntime();
        $this->actionAsset();
        $this->actionUploads();
        $this->actionDb();
        TDefaultData::data();
    }

    public function actionRuntime($delete = false)
    {
        $dir = Yii::getAlias('@runtime');
        self::log('cleaning Runtime :' . $dir);
        if (is_dir($dir)) {
            if ($delete) {
                FileHelper::removeDirectory($dir);
                return;
            }

            $objects = FileHelper::findFiles($dir);
            self::log('Count :' . count($objects));
            foreach ($objects as $object) {

                if (! FileHelper::unlink($object)) {
                    self::log('Unlink Error:' . $object);
                }
            }
            $objects = FileHelper::findDirectories($dir);
            self::log('Count :' . count($objects));
            foreach ($objects as $object) {

                if (is_dir($object)) {
                    FileHelper::removeDirectory($object);
                }
            }
            self::log('Runtime cleaned');
        }
        // Clean Assets
        $this->actionAsset();
    }

    public function actionAsset($delete = false)
    {
        $assetsDirs = FileHelper::normalizePath(DB_CONFIG_PATH . '../../assets/');
        self::log('cleaning Assets :' . $assetsDirs);
        if (is_dir($assetsDirs)) {

            if ($delete) {
                FileHelper::removeDirectory($assetsDirs);
                return;
            }

            $objects = FileHelper::findFiles($assetsDirs, [
                'recursive' => true
            ]);

            self::log('Count :' . count($objects));
            foreach ($objects as $object) {

                if (is_dir($object)) {
                    FileHelper::removeDirectory($object);
                }
                if (! FileHelper::unlink($object)) {
                    self::log('Unlink Error:' . $object);
                }
            }
            $objects = FileHelper::findDirectories($assetsDirs);
            self::log('Count :' . count($objects));
            foreach ($objects as $object) {

                if (is_dir($object)) {
                    FileHelper::removeDirectory($object);
                }
            }

            self::log('Assets cleaned');
        }
    }

    public function actionUploads()
    {
        $uploadDirs = UPLOAD_PATH;
        if (is_dir($uploadDirs)) {

            FileHelper::removeDirectory($uploadDirs);
        }
        self::log('Uploads cleaned');
    }

    public function actionDb($dontSkip = 0)
    {
        self::log('clean db dontSkip:' . $dontSkip);

        $skip_tables = [
            'tbl_user_role',
            'tbl_user'
        ];
        \Yii::$app->db->createCommand()
            ->checkIntegrity(false)
            ->execute();

        foreach (\Yii::$app->db->schema->tableNames as $table) {
            if (! $dontSkip && in_array($table, $skip_tables)) {
                continue;
            }
            self::log("Cleaning " . $table);
            \Yii::$app->db->createCommand()
                ->truncateTable($table)
                ->execute();
        }
        \Yii::$app->db->createCommand()
            ->checkIntegrity(true)
            ->execute();

        FileHelper::removeDirectory(UPLOAD_PATH);
    }

    public function actionFiles($id = 0, $limit = 0)
    {
        $query = File::find()->where([
            '>',
            'id',
            $id
        ])->orderBy('id asc');

        if ($limit > 0) {
            $query->limit($limit);
        }
        File::log("Cleaning up files : " . $query->count());
        foreach ($query->each() as $file) {

            try {

                $file->rename();
            } catch (Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
    }

    /**
     * Deleted User history which is older than 1 year
     */
    public function actionHistory()
    {
        $query = LoginHistory::find()->andWhere([
            '<',
            'created_on',
            date('Y-m-d', strtotime('-1 year'))
        ])->limit(1000);
        foreach ($query->each() as $model) {
            self::log('deleting' . $model->id . ':' . $model);
            $model->delete();
        }
    }

    /**
     * Deleted Feeds which is older than 2 year
     */
    public function actionFeed()
    {
        $query = Feed::find()->where([
            '<',
            'created_on',
            date('Y-m-d', strtotime('-2 year'))
        ])->limit(1000);

        foreach ($query->each() as $model) {
            self::log('deleting feed' . $model->id . ':' . $model);
            $model->delete();
        }
    }

    /**
     * For clear dubug mode
     */
    public function actionDebug()
    {
        $devFile = realpath(".") . "/.dev";
        if (is_file($devFile)) {
            @unlink($devFile);
            self::log('Production mode enabled');
        }
    }
}

