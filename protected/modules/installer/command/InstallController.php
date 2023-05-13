<?php
/**
 *@copyright   : ToXSL Technologies Pvt. Ltd < https://toxsl.com >
 *@author      : Shiv Charan Panjeta  < shiv@toxsl.com >
 */
namespace app\modules\installer\command;

use app\components\TConsoleController;
use app\modules\installer\helpers\InstallerHelper;
use app\modules\installer\models\CodeCheck;
use app\modules\installer\models\SystemCheck;
use Yii;
use yii\console\Exception;
use yii\db\mssql\PDO;

/**
 * Install controller for the `install` module
 */
class InstallController extends TConsoleController
{

    public $dryrun = false;

    public $db_name;

    public $username = "root";

    public $db_password = '';

    public $full_name;

    public $email;

    public $password;

    public $tablePrefix = 'tbl_';

    public $host = "127.0.0.1";

    public $moduleClass;

    public function options($actionID)
    {
        return [
            'dryrun',
            'host',
            'db_name',
            'db_password',
            'username',
            'full_name',
            'email',
            'password',
            'tablePrefix',
            'moduleClass'
        ];
    }

    public function optionAliases()
    {
        return [
            'd' => 'dryrun',
            'h' => 'host',
            'db' => 'db_name',
            'du' => 'username',
            'dp' => 'db_password',
            'name' => 'full_name',
            'e' => 'email',
            'p' => 'password',
            'tp' => 'tablePrefix',
            'm' => 'moduleClass'
        ];
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

    public function beforeAction($action)
    {
        self::log('beforeAction1');
        if (! parent::beforeAction($action)) {
            return false;
        }

        self::log('beforeAction2');
        if (empty($this->db_name))
            $this->db_name = Yii::$app->id;

        return true; // or false to not run the action
    }

    protected function removeDB()
    {
        $dbValid = true;
        // Connect to MySQL
        $link = mysqli_connect($this->host, $this->username, $this->db_password);
        if (! $link) {
            die('Could not connect: ' . mysql_error());
        }

        // Make my_db the current database
        $db_selected = mysqli_select_db($link, $this->db_name);

        if ($db_selected) {
            $dbValid = false;
            // If we couldn't, then it either doesn't exist, or we can't see it.
            $sql = 'DROP DATABASE ' . $this->db_name;

            if (mysqli_query($link, $sql)) {
                echo "Database removed successfully\n";
                $dbValid = true;
            } else {
                echo 'Error creating database: ' . mysqli_error($link) . "\n";
            }
        }

        mysqli_close($link);
        return $dbValid;
    }

    protected function removeTables()
    {
        $dbValid = true;
        // Connect to MySQL
        $link = mysqli_connect($this->host, $this->username, $this->db_password);
        if (! $link) {
            die('Could not connect: ' . mysql_error());
        }

        // Make my_db the current database
        $db_selected = mysqli_select_db($link, $this->db_name);

        if ($db_selected) {
            $dbValid = false;
        }

        mysqli_close($link);
        return $dbValid;
    }

    /**
     * Remove database
     */
    public function actionRemove()
    {
        if ($this->removeDB()) {

            if (file_exists(DB_CONFIG_FILE_PATH))
                @unlink(DB_CONFIG_FILE_PATH);
        }
    }

    protected function checkDB()
    {
        $dbValid = true;
        // Connect to MySQL
        $link = mysqli_connect($this->host, $this->username, $this->db_password);
        if (! $link) {
            die('Could not connect: ' . mysql_error());
        }
        echo "checking :Database \n";
        
        try {
            // Make my_db the current database
            $db_selected = mysqli_select_db($link, $this->db_name);
        } catch (\mysqli_sql_exception $e) {
            
            echo "Database not found.\n";
            $dbValid = false;
            // If we couldn't, then it either doesn't exist, or we can't see it.
            // CREATE DATABASE mydatabase CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            $sql = 'CREATE DATABASE ' . $this->db_name . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
            try {
                mysqli_query($link, $sql);
                
                echo "Database created successfully\n";
                $dbValid = true;
            } catch (\mysqli_sql_exception $e) {
                echo 'Error creating database: ' . mysqli_error($link) . "\n";
            }
        }
        
        mysqli_close($link);
        return $dbValid;
    }

    /**
     * Check system requirements
     */
    public function actionSystem()
    {
        SystemCheck::getResults($this->module);
    }

    /**
     * Check code quality
     */
    public function actionCode()
    {
        $checks = CodeCheck::getResults();
        $hasError = false;
        foreach ($checks as $check) {
            if ($check['state'] == 'ERROR')
                $hasError = true;
        }
        if ($hasError) {
            self::log(__FUNCTION__ . " :CODE:" . var_dump($checks));
        }
    }

    /**
     * Check database module
     */
    public function actionModule()
    {
        $this->loadConfig();
        if ($this->checkDB()) {
            $success = true;
            try {

                \Yii::$app->set('db', [
                    'class' => 'yii\db\Connection',
                    'dsn' => "mysql:host=$this->host;dbname=$this->db_name",
                    'emulatePrepare' => true,
                    'username' => $this->username,
                    'password' => $this->db_password,
                    'charset' => 'utf8mb4',
                    'tablePrefix' => $this->tablePrefix,
                    'attributes' => [
                        PDO::ATTR_CASE => PDO::CASE_LOWER
                    ]
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
                $success = false;
            }
        }

        if ($success) {
            $moduleName = $this->moduleClass;
            try {
                $message = 'NOK';

                if (class_exists("$moduleName")) {
                    $class = $moduleName;
                } else {
                    $class = "app\\modules\\" . $moduleName . "\\Module";
                }
                self::log(__FUNCTION__ . ":" . $class);
                if (method_exists($class, 'getExts')) {
                    $exts = $class::getExts();
                    foreach ($exts as $ext) {
                        $out = self::shellExec('apt-get install php-' . $ext);
                        if ($out) {
                            self::log(__FUNCTION__ . "$ext :shellExec:" . $out);
                        } else {
                            self::log(__FUNCTION__ . "$ext :shellExec:");
                        }
                    }
                }
                if (method_exists($class, 'dbFile')) {
                    $file = $class::dbFile();
                    $sqlFiles = is_array($file) ? $file : [
                        $file
                    ];
                    foreach ($sqlFiles as $sqlFile) {
                        if (is_file($sqlFile)) {
                            $sqlArray = file_get_contents($sqlFile);
                            $message = InstallerHelper::execSql($sqlArray);

                            self::log(__FUNCTION__ . " :DB:" . $sqlFile . ' ==> ' . $message);
                        } else {
                            self::log(__FUNCTION__ . " :DB:" . $sqlFile . " not exists.");
                        }
                    }
                } else {
                    self::log(__FUNCTION__ . " `dbFile` Method not exits");
                }
                if ($message == 'ok') {} else {
                    self::log(__FUNCTION__ . " : " . $message);
                }
            } catch (Exception $e) {
                self::log(__FUNCTION__ . " : " . $e->getMessage());
            }
        } else {
            self::log(__FUNCTION__ . " : database not ready");
        }
    }

    /**
     * Remove modules database check
     */
    public function actionRemoveModule()
    {
        if ($this->checkDB()) {
            $success = true;
            try {

                \Yii::$app->set('db', [
                    'class' => 'yii\db\Connection',
                    'dsn' => "mysql:host=$this->host;dbname=$this->db_name",
                    'emulatePrepare' => true,
                    'username' => $this->username,
                    'password' => $this->db_password,
                    'charset' => 'utf8mb4',
                    'tablePrefix' => $this->tablePrefix,
                    'attributes' => [
                        PDO::ATTR_CASE => PDO::CASE_LOWER
                    ]
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
                $success = false;
            }
        }

        if ($success) {
            $moduleName = $this->moduleClass;
            try {
                $message = 'NOK';

                if (class_exists("$moduleName")) {
                    $class = $moduleName;
                } else {
                    $class = "app\\modules\\" . $moduleName . "\\Module";
                }
                self::log(__FUNCTION__ . ":" . $class);
                if (method_exists($class, 'dbFile')) {
                    $sqlFile = $class::dbFile();

                    if (file_exists($sqlFile)) {
                        self::log(__FUNCTION__ . " :DB:" . $sqlFile . ' ==> OK');
                        $sqlArray = file_get_contents($sqlFile);

                        // TODO find tables name and create drop instuctions
                        if (preg_match_all("/DROP TABLE(.*);/i", $sqlArray, $matches)) {
                            $final = 'SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;' . PHP_EOL;
                            $final .= implode("\n", $matches[0]);
                            $final .= PHP_EOL . 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;';
                            // self::log(__FUNCTION__ . " : " . $final);
                        }
                        $message = InstallerHelper::execSql($final);
                    } else {
                        self::log(__FUNCTION__ . " :DB:" . $sqlFile . " not exists.");
                    }
                } else {
                    self::log(__FUNCTION__ . " `dbFile` Method not exits");
                }
                if ($message == 'ok') {} else {
                    self::log(__FUNCTION__ . " : " . $message);
                }
            } catch (Exception $e) {
                self::log(__FUNCTION__ . " : " . $e->getMessage());
            }
        } else {
            self::log(__FUNCTION__ . " : database not ready");
        }
    }

    /**
     * Check database
     *
     * @return number
     */
    public function actionIndex()
    {
        self::log('actionIndex');

        $success = true;

        $checks = SystemCheck::getResults($this->module, true);

        $message = "file not found";

        if ($this->checkDB()) {
            $success = true;
            try {

                \Yii::$app->set('db', [
                    'class' => 'yii\db\Connection',
                    'dsn' => "mysql:host=$this->host;dbname=$this->db_name",
                    'emulatePrepare' => true,
                    'username' => $this->username,
                    'password' => $this->db_password,
                    'charset' => 'utf8mb4',
                    'tablePrefix' => $this->tablePrefix,
                    'attributes' => [
                        PDO::ATTR_CASE => PDO::CASE_LOWER
                    ]
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
                $success = false;
            }
        }

        if (! $success) {

            self::log(__FUNCTION__ . "database not ready");
            return 0;
        }
        $dd = 'YII_ENV' == 'dev' ? '0' : '1';

        $text_file = "<?php
			return [
    			'class' => 'yii\db\Connection',
    			'dsn' => 'mysql:host=$this->host;dbname=$this->db_name',
    			'emulatePrepare' => true,
    			'username' => '$this->username',
    			'password' => '$this->db_password',
    			'charset' => 'utf8mb4',
    			'tablePrefix' => '$this->tablePrefix',
    			'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],
                'enableSchemaCache' => $dd ,
                'schemaCacheDuration' => 3600,
             // 'queryCacheDuration' => 10,
                'schemaCache' => 'cache',
			];";

        try {
            $message = 'NOK';
            file_put_contents(DB_CONFIG_FILE_PATH, $text_file);
            $message = InstallerHelper::execSqlFiles($this->module->sqlfile);

            if ($message != 'NOK') {
                self::log(" Installation Done.");
                InstallerHelper::setCookie();
                self::log("Cookies Done.");
            } else {
                unlink(DB_CONFIG_FILE_PATH);
                self::log(__FUNCTION__ . $message);
            }
        } catch (Exception $e) {
            unlink(DB_CONFIG_FILE_PATH);
            self::log(__FUNCTION__ . $e->getMessage());
        }
    }

    /**
     * Remove database
     */
    public function actionDatabase()
    {
        $this->loadConfig();
        $this->removeTables();

        $success = true;

        if ($this->checkDB()) {
            $success = true;
            try {
                \Yii::$app->set('db', [
                    'class' => 'yii\db\Connection',
                    'dsn' => "mysql:host=$this->host;dbname=$this->db_name",
                    'emulatePrepare' => true,
                    'username' => $this->username,
                    'password' => $this->db_password,
                    'charset' => 'utf8mb4',
                    'tablePrefix' => $this->tablePrefix,
                    'attributes' => [
                        PDO::ATTR_CASE => PDO::CASE_LOWER
                    ]
                ]);
            } catch (Exception $e) {
                echo $e->getMessage();
                $success = false;
            }
        }

        if ($success) {
            try {

                $message = 'NOK';
                $message = InstallerHelper::execSqlFiles($this->module->sqlfile);

                if ($message != 'NOK') {
                    self::log(" Installation Done.");
                    InstallerHelper::setCookie();
                    self::log("Cookies Done.");
                } else {
                    self::log(__FUNCTION__ . $message);
                }
            } catch (Exception $e) {
                self::log(__FUNCTION__ . $e->getMessage());
            }
        } else {
            self::log(__FUNCTION__ . "database not ready");
        }
    }
}



