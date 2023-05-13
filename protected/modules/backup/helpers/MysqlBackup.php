<?php
namespace app\modules\backup\helpers;

use Yii;
use yii\base\Exception;
use yii\helpers\VarDumper;
use app\components\helpers\TLogHelper;

class MysqlBackup
{

    use TLogHelper;

    public $db_name;

    public $username;

    public $db_password;

    public $host;

    private $_moduleName = null;

    public $tables = [];

    public $fp;

    public $file_name;

    public $back_temp_file = 'db_backup_';

    public $_path;

    // this is the break point that we use to break the data in chunks
    const BREACKPOINT = " -- -------AutobackUpStart------ ";

    public $enableZip = true;

    public function setModule($moduleName = null)
    {
        $this->_moduleName = $moduleName;
    }

    public function execSqlFile($sqlFile)
    {
        $message = "ok";
        $data = '';
        if (is_file($sqlFile)) {
            $handle = @fopen($sqlFile, "r");
            if ($handle) {
                $i = 1;
                while (! feof($handle)) {
                    $buffer = fgets($handle);
                    if (strpos($buffer, self::BREACKPOINT) !== false) {
                        $data = trim($data);
                        if ($i == 1) {
                            try {
                                $cmd = \Yii::$app->db->createCommand($data);
                                $cmd->execute();
                            } catch (Exception $e) {
                                $message = $e->getMessage();
                            }
                        } else {
                            // Here the query is being executed in chunks
                            $head = $this->topHeading();
                            $data = PHP_EOL . $head . PHP_EOL . $data . PHP_EOL;
                            $bot = $this->botHeading();
                            $data .= $head . $bot;
                            try {
                                $cmd = \Yii::$app->db->createCommand($data);
                                $cmd->execute();
                            } catch (Exception $e) {
                                $message = $e->getMessage();
                            }
                        }
                        $data = '';
                        $i ++;
                    } else {
                        $data .= $buffer;
                    }
                }
                fclose($handle);
            }
        }
        return $message;
    }

    public function topHeading()
    {
        $str = PHP_EOL . '-- -------------------------------------------' . PHP_EOL . 'SET AUTOCOMMIT=0;' . PHP_EOL . PHP_EOL . 'SET SQL_QUOTE_SHOW_CREATE = 1;' . PHP_EOL . 'SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;' . PHP_EOL . 'SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;' . PHP_EOL . 'START TRANSACTION;' . PHP_EOL . '-- -------------------------------------------' . PHP_EOL;
        return $str;
    }

    public function botHeading()
    {
        $str = PHP_EOL . '-- -------------------------------------------' . PHP_EOL . 'COMMIT;' . PHP_EOL . 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;' . PHP_EOL . 'SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;' . PHP_EOL . '-- -------------------------------------------' . PHP_EOL;

        return $str;
    }

    public function clean($ignore = [
        'tbl-user',
        'tbl_user_role'
    ])
    {
        if (! $sql->StartBackup()) {
            return "error";
        }

        $message = '';

        foreach ($tables as $tableName) {
            if (in_array($tableName, $ignore))
                continue;
            fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
            fwrite($this->fp, 'DROP TABLE IF EXISTS ' . addslashes($tableName) . ';' . PHP_EOL);
            fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);

            $message .= $tableName . ',';
        }
        $sql->EndBackup();
        // logout so there is no problme later .
        Yii::$app->user->logout();

        $sql->execSqlFile($this->file_name);

        unlink($this->file_name);
    }

    public function getTables()
    {
        if ($this->_moduleName != null) {
            return $this->getModuleTables($this->_moduleName);
        }
        $sql = 'SHOW TABLES';
        $cmd = Yii::$app->db->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    public function getModuleTables($moduleName)
    {
        if (class_exists("$moduleName")) {
            $class = $moduleName;
        } else {
            $class = "app\\modules\\" . $moduleName . "\\Module";
        }
        self::log(__FUNCTION__ . ":" . $class);

        if (method_exists($class, 'dbFile')) {
            $sqlFile = $class::dbFile();
            self::log(__FUNCTION__ . ":" . $sqlFile);
            if (is_array($sqlFile)) {
                if (isset($sqlFile[0]))
                    $sqlFile = $sqlFile[0];
                else
                    $sqlFile = null;
            }
            if ($sqlFile != null && is_file($sqlFile)) {
                self::log(__FUNCTION__ . " :DB:" . $sqlFile . ' ==> OK');
                $sqlArray = file_get_contents($sqlFile);

                // TODO find tables name and create drop instuctions
                if (preg_match_all("/CREATE TABLE(.*)`(.*)`/i", $sqlArray, $matches)) {
                    // var_dump($matches[2]);
                    return $matches[2];
                }
            }
        }
        return [];
    }

    private function loadConfig()
    {
        if (file_exists(DB_CONFIG_FILE_PATH)) {
            $dbconfig = include (DB_CONFIG_FILE_PATH);
            $this->username = $dbconfig['username'];
            $this->db_password = $dbconfig['password'];
            if (preg_match('/host=(.*);/', $dbconfig['dsn'], $matches)) {
                // VarDumper::dump($matches);
                $this->host = $matches[1];
            }
            if (preg_match('/dbname=(.*)$/', $dbconfig['dsn'], $matches)) {
                // VarDumper::dump($matches);
                $this->db_name = $matches[1];
            }
        }
    }

    public function fullBackup($addcheck = true)
    {
        if (shell_exec('which mysqldump')) {
            $this->loadConfig();
            $cmd = 'mysqldump --no-tablespaces -h ' . $this->host . ' -u ' . $this->username . ' -p' . $this->db_password . ' ' . $this->db_name . '> ' . escapeshellarg($this->getFilePath());
            self::log(":cmd : " . $cmd);
            $outtext = shell_exec($cmd);
            $this->createZipBackup();
            return $outtext;
        }
        if (! $this->startBackup()) {
            self::log(__FUNCTION__ . ":Started");
        }
        $tables = $this->getTables();
        foreach ($tables as $tableName) {
            $this->getColumns($tableName);
        }
        foreach ($tables as $tableName) {
            $this->getData($tableName);
        }

        $sqlFile = $this->endBackup();
        self::log(__FUNCTION__ . ":Finished : " . $sqlFile);
        return $sqlFile;
    }

    public function startBackup($addcheck = true)
    {
        $this->file_name = $this->getPath();

        if ($this->_moduleName != null) {
            $this->file_name .= $this->_moduleName . '_' . date('Y.m.d_H.i.s') . '.sql';
        } else {
            $this->file_name .= $this->back_temp_file . date('Y.m.d_H.i.s') . '.sql';
        }
        $this->fp = fopen($this->file_name, 'w+');

        if ($this->fp == null)
            return false;
        fwrite($this->fp, 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";' . PHP_EOL);
        fwrite($this->fp, 'SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;' . PHP_EOL);
        fwrite($this->fp, 'SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;' . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        if ($addcheck) {
            fwrite($this->fp, 'SET AUTOCOMMIT=0;' . PHP_EOL);
            fwrite($this->fp, 'START TRANSACTION;' . PHP_EOL);
            fwrite($this->fp, 'SET SQL_QUOTE_SHOW_CREATE = 1;' . PHP_EOL);
        }
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        $this->writeComment('START BACKUP');
        return true;
    }

    public function endBackup($addcheck = true)
    {
        if ($addcheck) {
            fwrite($this->fp, PHP_EOL . 'COMMIT;' . PHP_EOL);
        }
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        fwrite($this->fp, 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;' . PHP_EOL);
        fwrite($this->fp, 'SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;' . PHP_EOL);

        fwrite($this->fp, self::BREACKPOINT);

        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        $this->writeComment('END BACKUP');
        fclose($this->fp);
        $this->fp = null;
        if ($this->enableZip) {

            $this->createZipBackup();
        }
        return $this->file_name;
    }

    public function getColumns($tableName)
    {
        $sql = 'SHOW CREATE TABLE ' . $tableName;
        $cmd = Yii::$app->db->createCommand($sql);
        $table = $cmd->queryOne();
        $create_query = $table['create table'] . ';';

        $create_query = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_query);
        $create_query = preg_replace('/AUTO_INCREMENT\s*=\s*([0-9])+/', '', $create_query);
        if ($this->fp) {
            $this->writeComment('TABLE `' . addslashes($tableName) . '`');
            $final = 'DROP TABLE IF EXISTS `' . addslashes($tableName) . '`;' . PHP_EOL . $create_query . PHP_EOL . PHP_EOL;
            fwrite($this->fp, $final);
        } else {
            $this->tables[$tableName]['create'] = $create_query;
            return $create_query;
        }
    }

    public function getData($tableName)
    {
        $sql = 'SELECT COUNT(*) as row_count FROM ' . $tableName;
        $cmd = Yii::$app->db->createCommand($sql);
        $dataReader = $cmd->query();
        $dataReader = $dataReader->read();
        $count = $dataReader['row_count'];
        // this is used to break the data in 100 records per section
        $var = 100;

        if ($count > $var) {
            // this sql is executed when we have records in a table greater than 100

            $i = 1;
            $limit = 0;
            while ($count > $var) {
                $sql = 'SELECT * FROM ' . $tableName . ' LIMIT ' . ($limit) . ', 100';
                $cmd = Yii::$app->db->createCommand($sql);
                $dataReader = $cmd->query();
                // the data is being executed by calling this function
                $this->WriteData($dataReader, $tableName);
                $limit = $i * $var;
                $i ++;
                $count = $count - $var;
            }
            if ($count > 0) {
                $sql = 'SELECT * FROM ' . $tableName . ' LIMIT ' . ($limit) . ', 100';
                $cmd = Yii::$app->db->createCommand($sql);
                $dataReader = $cmd->query();
                // the data is being executed by calling this function
                $this->writeData($dataReader, $tableName);
            }
        } else {
            // if the table records are less than 100 records this sql is executed
            if ($count > 0) {
                $sql = 'SELECT * FROM ' . $tableName;
                $cmd = Yii::$app->db->createCommand($sql);
                $dataReader = $cmd->query();
                if ($count > 0) {
                    $this->writeData($dataReader, $tableName);
                }
            }
        }
        if ($this->fp)
            fflush($this->fp);
        return true;
    }

    // this function is used to get the backup in chunks on each chunk a breakpoint is inserted
    private function writeData($dataReader, $tableName)
    {
        $val = '';
        $line = PHP_EOL;
        fwrite($this->fp, $line);
        foreach ($dataReader as $data) {
            $itemNames = array_keys($data);
            $itemNames = array_map("addslashes", $itemNames);
            $items = join('`,`', $itemNames);

            $itemValues = array_values($data);
            for ($i = 0; $i < count($itemValues); $i ++) {

                if ($itemValues[$i]) {
                    $itemValues[$i] = addslashes($itemValues[$i]);
                }
            }

            $valueString = join('","', $itemValues);
            if ($itemValues)
                $val .= "(" . '"' . $valueString . '"' . ")," . PHP_EOL;
        }
        $val = rtrim($val, ",\n");
        if ($val != "") {
            fwrite($this->fp, $line);
            $data_string = "INSERT INTO `$tableName` (`$items`) VALUES" . PHP_EOL . rtrim($val, ",") . ";" . PHP_EOL;
            fwrite($this->fp, $line);
            if ($this->fp) {
                fwrite($this->fp, $data_string);
                fwrite($this->fp, PHP_EOL);
                fwrite($this->fp, self::BREACKPOINT);
            }
        }
    }

    public function getFilePath()
    {
        $this->file_name = $this->getPath();

        if ($this->_moduleName != null) {
            $this->file_name .= $this->_moduleName . '_' . date('Y.m.d_H.i.s') . '.sql';
        } else {
            $this->file_name .= $this->back_temp_file . date('Y.m.d_H.i.s') . '.sql';
        }
        return $this->file_name;
    }

    public function getPath()
    {
        if ($this->_path == null) {

            if ($this->_moduleName != null) {
                $this->_path = Yii::$app->getModule($this->_moduleName)->basePath . '/db/';
            } else {
                $this->_path = Yii::$app->basePath . '/db/';
            }
            if (defined('DB_BACKUP_FILE_PATH')) {
                $this->_path = DB_BACKUP_FILE_PATH;
            }
            if (! is_file($this->_path)) {
                @mkdir($this->_path, 0775, true);
            }
        }
        return $this->_path;
    }

    private function writeComment($string)
    {
        fwrite($this->fp, PHP_EOL . '-- -------------------------------------------' . PHP_EOL);
        fwrite($this->fp, PHP_EOL . '-- ' . $string . PHP_EOL);
        fwrite($this->fp, PHP_EOL . '-- -------------------------------------------' . PHP_EOL);
    }

    /**
     * Charge method to backup and create a zip with this
     */
    private function createZipBackup()
    {
        if (class_exists(\ZipArchive::class)) {
            $zip = new \ZipArchive();
            $file_name = $this->file_name . '.zip';
            if ($zip->open($file_name, \ZipArchive::CREATE) === TRUE) {
                $zip->addFile($this->file_name, basename($this->file_name));
                $zip->close();

                @unlink($this->file_name);
                $this->file_name = $file_name;
            }
        } else {
            echo "ZipArchive missing class ";
        }
    }

    /**
     * Method responsible for reading a directory and add them to the zip
     *
     * @param string $alias
     * @param string $directory
     */
    private function zipDirectory($zip, $alias, $directory)
    {
        if ($handle = opendir($directory)) {
            while (($file = readdir($handle)) !== false) {
                if (is_dir($directory . $file) && $file != "." && $file != ".." && ! in_array($directory . $file . '/', $this->module->excludeDirectoryBackup))
                    $this->zipDirectory($zip, $alias . $file . '/', $directory . $file . '/');

                if (is_file($directory . $file) && ! in_array($directory . $file, $this->module->excludeFileBackup))
                    $zip->addFile($directory . $file, $alias . $file);
            }
            closedir($handle);
        }
    }

    /**
     * Zip file execution
     *
     * @param string $zipFile
     *            Name of file zip
     */
    public function unzip($sqlZipFile)
    {
        if (is_file($sqlZipFile)) {
            $zip = new \ZipArchive();
            $result = $zip->open($sqlZipFile);
            if ($result === true) {
                $zip->extractTo(dirname($sqlZipFile));
                $zip->close();
                $sqlZipFile = str_replace(".zip", "", $sqlZipFile);
            }
        }
        return $sqlZipFile;
    }
}
