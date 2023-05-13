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
namespace app\modules\scheduler\commands;

use app\components\TConsoleController;
use app\modules\scheduler\Module;
use app\modules\scheduler\models\Cronjob;
use app\modules\scheduler\models\Log;
use app\modules\scheduler\models\SettingsForm;
use app\modules\scheduler\models\Type;
use ErrorException;
use Exception;
use yii\console\ExitCode;

class CronjobController extends TConsoleController
{

    public function actionIndex($current = null)
    {
        if ($current == null) {
            $current = \date('Y-m-d H:i:s');
        }

        $settings = new SettingsForm();

        if (! $settings->enableScheduler) {
            self::log("Scheduler not enabled");
            return ExitCode::OK;
        }

        self::log("Searching pending jobs .. @ " . $current);
        $cmdsQuery = Log::find();

        if ($cmdsQuery->count() == 0) {
            // empty try loading default
            // check default file
            $cronQuery = Cronjob::find();

            if ($cronQuery->count() == 0) {
                Cronjob::importFile();
            }
        }

        $cmdsQuery->where([
            'in',
            'state_id',
            [
                Log::STATE_PENDING
            ]
        ])
            ->andWhere([
            '<=',
            'scheduled_on',
            $current
        ])
            ->orderBy('id asc');
        self::log("Found pending jobs .. @ " . $cmdsQuery->count());
        foreach ($cmdsQuery->each() as $cmd) {
            try {

                $cmd->runAction();
            } catch (Exception $ex) {
                self::log('Command Failed:' . $cmd);
                self::log($ex->getMessage());
                self::log($ex->getTraceAsString());
            } catch (ErrorException $ex) {
                self::log('Command Failed:' . $cmd);
                self::log($ex->getMessage());
                self::log($ex->getTraceAsString());
            }
        }
        $this->actionSchedule($settings->runAsap);
        $this->actionCleanLogs();
    }

    public function actionSchedule($now = false)
    {
        $cmdsQuery = Cronjob::find()->where([
            'in',
            'state_id',
            [
                Cronjob::STATE_ACTIVE
            ]
        ])->orderBy('id asc');

        foreach ($cmdsQuery->each() as $cmd) {
            $cmd->scheduleNext($now);
        }
    }

    /**
     * Clean old logs
     *
     * @param number $days
     *            specify days
     * @param
     *            -f // clean all jobs
     */
    public function actionCleanLogs($days = 7)
    {
        $query = Log::find();

        if (! $this->force) {
            // if forced dont check state
            $query->where([
                'in',
                'state_id',
                [
                    Log::STATE_COMPLETED,
                    Log::STATE_FAILED
                ]
            ]);
            $query->andWhere([
                '<',
                'DATE(created_on)',
                date('Y-m-d', strtotime("-$days days"))
            ]);
        }

        $query->limit($this->limit)->orderBy('id asc');

        self::log("Cleaning up  : " . $query->count());
        foreach ($query->each() as $log) {
            self::log("Deleting   :" . $log);
            try {
                $log->delete();
            } catch (\Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }
        if ($this->force) {
            Log::truncate();
        }
        self::log("Done   :");
    }

    /**
     * Clean jobs
     */
    public function actionClean()
    {
        $query = Cronjob::find();

        $query->limit($this->limit)->orderBy('id asc');

        self::log(" Cleaning up  : " . $query->count());
        foreach ($query->each() as $log) {
            self::log("Deleting   :" . $log);
            try {
                $log->delete();
            } catch (\Exception $e) {
                echo $e->getMessage();
                echo $e->getTraceAsString();
            }
        }

        Cronjob::truncate();

        self::log("Done   :");
    }

    /**
     * Add cron job
     *
     * @param string $cmd
     * @param string $when
     */
    public function actionAdd($cmd, $when = null)
    {
        $model = new Cronjob();
        $model->loadDefaultValues();
        $model->state_id = Cronjob::STATE_ACTIVE;
        $model->when = isset($when) ? $when : '* * * * *';
        $model->command = $cmd;
        $model->type_id = 1;
        $model->save();
    }

    /**
     * Export Cronjobs
     */
    public function actionExport()
    {
        $content = '';
        $jobQuery = Cronjob::find();
        self::log(" Export Cronjobs  : " . $jobQuery->count());
        foreach ($jobQuery->each() as $job) {
            $content .= $job->exportText() . "\n";
        }

        $file = Module::self()->defaultJobsFile;

        file_put_contents($file, $content);
        self::log(" Exported File  : " . $file);
    }

    /**
     * Import Cronjobs
     */
    public function actionImport($file = null)
    {
        Type::addData([
            [
                'title' => 'Default'
            ]
        ]);
        self::log(" Import File  : " . $file);
        Cronjob::importFile($file);
    }

    /**
     * Default Cronjobs
     */
    public function actionDefault()
    {
        $this->actionClean();
        Type::addData([
            [
                'title' => 'Default'
            ]
        ]);
        self::log('Find Cronjobs from all modules');
        $config = include (DB_CONFIG_PATH . 'web.php');
        $dbPkgs = [
            "20 2 * * * \t clear/history",
            "20 2 * * * \t clear/runtime",
            "0 * * * * \t clear/debug"
        ];
        if (! empty($config['modules'])) {
            foreach ($config['modules'] as $module) {
                $class = isset($module['class']) ? $module['class'] : null;

                if (class_exists("$class") && method_exists($class, 'getCronJobs')) {
                    $dbPkgs = array_merge($dbPkgs, $class::getCronJobs());
                }
            }
        }
        foreach ($dbPkgs as $line) {
            Cronjob::importline($line);
        }
    }
}

