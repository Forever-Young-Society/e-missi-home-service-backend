<?php
namespace app\modules\sitemap\controllers;

/**
 * Default controller for the `sitemap` module
 */
use app\components\TController;
use app\components\filters\AccessControl;
use app\models\User;
use app\modules\sitemap\models\Item;
use app\modules\sitemap\models\SettingsForm;
use Yii;
use yii\helpers\Url;

class DefaultController extends TController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,

                'rules' => [
                    [
                        'actions' => [
                            'list',
                            'robots'
                        ],
                        'allow' => true,
                        'roles' => [
                            '*',
                            '?'
                        ]
                    ],
                    [
                        'actions' => [
                            'index',
                            'test',
                            'settings',
                            'list',
                            'robots'
                        ],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return User::isManager();
                        }
                    ]
                ]
            ]
        ];
    }

    public function actionList()
    {
        $urlQuery = Item::findActive();
        if ($urlQuery->count() == 0) {
            // generate fresh
            $this->module->process();
        }
        $sitemapDataRaw = \yii::$app->view->renderFile("@app/modules/sitemap/templates/sitemap.php", [
            'urlQuery' => $urlQuery
        ]);
        $xml = new \DOMDocument();
        $xml->preserveWhiteSpace = false;
        $xml->formatOutput = true;
        $xml->loadXML($sitemapDataRaw);
        $sitemapData = $xml->saveXML();

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/xml');

        return $sitemapData;
    }

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSettings()
    {
        $moduleSettings = new SettingsForm();

        if ($moduleSettings->load(\Yii::$app->request->post())) {
            if ($moduleSettings->validate() && $moduleSettings->Save()) {

                return $this->redirect([
                    'settings'
                ]);
            }
        }
        return $this->render('settings', [

            'model' => $moduleSettings
        ]);
    }

    public function actionRobots()
    {
        $sitemapData = $this->renderPartial('robots', [
            'sitemap' => Url::toRoute([
                '/sitemap/default/index'
            ], true)
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/plain');

        return $sitemapData;
    }
}
