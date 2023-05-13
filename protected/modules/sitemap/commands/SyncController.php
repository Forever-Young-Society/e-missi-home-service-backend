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
namespace app\modules\sitemap\commands;

use app\components\TConsoleController;
use app\modules\sitemap\models\Item;
use app\modules\sitemap\models\SettingsForm;
use yii\console\ExitCode;
use yii\helpers\Url;

/**
 * SyncController implements the CRUD actions for Account model.
 */
class SyncController extends TConsoleController
{

    /*
     * sitemap cache
     */
    public function actionIndex()
    {
        $moduleSettings = new SettingsForm();

        if (! $moduleSettings->enable) {
            self::log("Sitemap not enabled");
            return ExitCode::OK;
        }
        $this->module->process();
        $this->actionTest();
    }

    /**
     * Delete all
     *
     * @param boolean $truncate
     * @return number
     */
    public function actionClear($truncate = true)
    {
        $query = Item::find()->orderBy('id ASC');

        Item::log('Deleting count :' . $query->count());
        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                $model->delete();
            }
        }

        if ($truncate) {
            Item::truncate();
        }
        return 0;
    }

    public function actionSubmit()
    {
        $searchEngines = [
            'Google' => 'https://www.google.com/ping?sitemap=',
            'Bing' => 'https://www.bing.com/ping?sitemap='
        ];

        $sitemapUrl = Url::toRoute([
            '/sitemap/default/index'
        ], true);

        foreach ($searchEngines as $engine => $url) {
            self::log('submitting: ' . $url . $sitemapUrl);
            file_get_contents($url . $sitemapUrl);
        }
    }

    /**
     * Delete all
     */
    public function actionTest()
    {
        $query = Item::find()->orderBy('id ASC');

        Item::log('Testing count :' . $query->count());
        foreach ($query->batch() as $models) {
            foreach ($models as $model) {
                $model->test();
            }
        }
        return 0;
    }
}
