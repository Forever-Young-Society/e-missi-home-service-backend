<?php
namespace app\modules\backup\commands;

use app\modules\backup\helpers\MysqlBackup;
use Yii;
use app\components\TConsoleController;

/**
 * Backup commands
 *
 * @copyright : ToXSL Technologies Pvt. Ltd < https://toxsl.com >
 * @author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
class ModuleController extends TConsoleController
{

    public $module = null;

    public $schemaOnly = false;

    public $dryrun = false;

    public $file = null;

    public function options($actionID)
    {
        return [
            'module',
            'schemaOnly',
            'dryrun',
            'file'
        ];
    }

    public function optionAliases()
    {
        return [
            'm' => 'module',
            's' => 'schemaOnly',
            'd' => 'dryrun',
            'f' => 'file'
        ];
    }

    protected function getFileList($ext = '*.sql')
    {
        $sql = new MysqlBackup();
        $path = $sql->getPath();
        $list = [];
        $list_files = glob($path . $ext);
        if ($list_files) {
            $list = array_map('basename', $list_files);
            sort($list);
        }
        return $list;
    }

    /**
     * create backup
     *
     * @return string
     */
    public function actionCreate($m = null)
    {
        $sql = new MysqlBackup();
        $sql->setModule($m);

        if (! $sql->startBackup()) {
            $this->log(__FUNCTION__ . ":Started");
        }
        $tables = $sql->getTables();
        foreach ($tables as $tableName) {
            $sql->getColumns($tableName);
        }
        if (! $this->schemaOnly) {
            foreach ($tables as $tableName) {
                $sql->getData($tableName);
            }
        }

        $sqlFile = $sql->endBackup();
        $this->log(__FUNCTION__ . ":Finished : " . $sqlFile);
        return $sqlFile;
    }

    /**
     * Restore last backup
     *
     * @param
     *            string -f
     * @param
     *            string -d
     * @return string
     */
    public function actionRestore()
    {
        $message = 'NOK';
        $dryrun = $this->dryrun;
        $file = $this->file;
        if ($file == null) {
            $files = $this->getFileList();
            if (empty($files)) {
                $this->log(__FUNCTION__ . " : No Files dound");
            }
            $file = $files[0];
        }

        $this->log(__FUNCTION__ . " : " . $file);
        $sql = new MysqlBackup();

        $sqlZipFile = $file;
        if (! is_file($file)) {
            $sqlZipFile = $sql->getPath() . basename($file);
        }
        $sqlFile = $sql->unzip($sqlZipFile);
        if (! $dryrun)
            $message = $sql->execSqlFile($sqlFile);

        $this->log(__FUNCTION__ . " : " . $message);
        return $message;
    }

    /**
     * backup all Modules
     *
     * @param
     *            string -f
     * @param
     *            string -d
     * @return string
     */
    public function actionModules()
    {
        $list = Yii::$app->getModules();
        $this->log(__FUNCTION__ . ":Started  : ");
        $dataArray = [];
        foreach ($list as $module => $class) {

            $dataArray[] = $this->actionCreate($module);
        }

        $this->log(__FUNCTION__ . ":Finished : ");
        return $dataArray;
    }
}
