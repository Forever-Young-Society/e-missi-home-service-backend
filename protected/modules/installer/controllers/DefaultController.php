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
namespace app\modules\installer\controllers;

use app\models\User;
use app\modules\installer\helpers\InstallerHelper;
use app\modules\installer\models\Mail;
use app\modules\installer\models\SetupDb;
use app\modules\installer\models\SystemCheck;
use PDO;
use Yii;
use yii\base\Exception;
use yii\web\Controller;

/**
 * Default controller for the `install` module
 */
class DefaultController extends Controller
{

    public $setup;

    public $setupDone = false;

    public function beforeAction($action)
    {
        $this->setup = new SetupDb();

        $this->setup->db_name = Yii::$app->request->get('db_name', Yii::$app->id);
        $this->setup->username = Yii::$app->request->get('username', 'root');
        $this->setup->password = Yii::$app->request->get('password', '');
        $this->setup->host = Yii::$app->request->get('host', '127.0.0.1');

        if (Yii::$app->request->get('db_name')) {
            $this->setupDone = true;
        }
        if (file_exists(DB_CONFIG_FILE_PATH . '.setup')) {
            $dbconfig = include (DB_CONFIG_FILE_PATH . '.setup');
            $this->setup->username = $dbconfig['username'];
            $this->setup->password = $dbconfig['password'];
            if (preg_match('/dbname=(.*)$/', $dbconfig['dsn'], $matches)) {
                // VarDumper::dump($matches);
                $this->setup->db_name = $matches[1];
                $this->setupDone = true;
            }
        }

        return parent::beforeAction($action);
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex()
    {
        if ($this->setupDone) {
            return $this->handleSetup($this->setup);
        }
        return $this->render('index');
    }

    public function actionGo()
    {
        $checks = SystemCheck::getResults($this->module, false);

        $hasError = $checks['summary']['errors'];

        // Render template
        return $this->render('check', [
            'checks' => $checks['requirements'],
            'hasError' => $hasError
        ]);
    }

    public function checkDB($model)
    {
        $dbValid = true;
        // Connect to MySQL
        $link = @mysqli_connect($model->host, $model->username, $model->password);
        if (! $link) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit();
        }

        // Make my_db the current database
        $db_selected = @mysqli_select_db($link, $model->db_name);

        if (! $db_selected) {
            $dbValid = false;
            // If we couldn't, then it either doesn't exist, or we can't see it.
            // CREATE DATABASE mydatabase CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            $sql = 'CREATE DATABASE ' . $model->db_name . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';

            if (@mysqli_query($link, $sql)) {
                echo "Database my_db created successfully\n";
                $dbValid = true;
            } else {
                echo 'Error creating database: ' . mysqli_error($link) . "\n";
            }
        }

        @mysqli_close($link);
        return $dbValid;
    }

    /*
     * STEP 2
     * CONFIGURE THE DATABASE FILE
     * Setupdb
     */
    public function actionStep2()
    {
        $model = $this->setup;
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $model->setAttributes($_POST['SetupDb']);
            $this->handleSetup($model);
        }

        return $this->render('database', [
            'model' => $model
        ]);
    }

    public function handleSetup($model)
    {
        if ($this->checkDB($model)) {
            $success = true;
            try {

                $db = \Yii::$app->set('db', [
                    'class' => 'yii\db\Connection',
                    'dsn' => "mysql:host=$model->host;dbname=$model->db_name",
                    'emulatePrepare' => true,
                    'username' => $model->username,
                    'password' => $model->password,
                    'charset' => 'utf8mb4',
                    'tablePrefix' => $model->table_prefix,
                    'attributes' => [
                        PDO::ATTR_CASE => PDO::CASE_LOWER
                    ]
                ]);
            } catch (Exception $e) {
                $success = false;
            }
        }

        if ($success) {
            $dd = 'YII_ENV' == 'dev' ? false : true;
            $text_file = "<?php
				return [
				'class' => 'yii\db\Connection',
				'dsn' => 'mysql:host=$model->host;dbname=$model->db_name',
				'emulatePrepare' => true,
				'username' => '$model->username',
				'password' => '$model->password',
				'charset' => 'utf8mb4',
				'tablePrefix' => '$model->table_prefix',
                'enableSchemaCache' => $dd ,
                'schemaCacheDuration' => 3600,
             // 'queryCacheDuration' => 10,
                'schemaCache' => 'cache',
                'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER]

				];";

            try {

                InstallerHelper::setCookie();

                file_put_contents(DB_CONFIG_FILE_PATH, $text_file);

                $message = InstallerHelper::execSqlFiles($this->module->sqlfile);

                if ($message != 'NOK') {

                    $count = User::find()->count();
                    if ($count > 0) {
                        return $this->redirect([
                            '/user/login'
                        ]);
                    } else {
                        return $this->redirect([
                            '/user/add-admin'
                        ]);
                    }
                } else {
                    unlink(DB_CONFIG_FILE_PATH);
                    \Yii::$app->session->setFlash('error', $message);
                }
            } catch (Exception $e) {

                unlink(DB_CONFIG_FILE_PATH);
                \Yii::$app->session->setFlash('error', 'Unable to setup Database.');
                // echo $e->getTraceAsString ();
            }
        } else {
            \Yii::$app->session->setFlash('error', 'database not ready');
        }
    }
}
