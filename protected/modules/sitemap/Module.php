<?php
namespace app\modules\sitemap;

/**
 * sitemap module definition class
 */
use app\components\TModule;
use app\modules\sitemap\models\Item;
use yii\helpers\Url;

class Module extends TModule
{

    public $enableGzip = false;

    public $controllerNamespace = 'app\modules\sitemap\controllers';

    /**
     *
     * @var array
     */
    public $models = [];

    /**
     *
     * @var array
     */
    public $urls = [];

    const BATCH_MAX_SIZE = 1000;

    const PRIORITY = 0;

    /**
     *
     * @var string|bool
     */
    public $defaultChangefreq = Item::CHANGEFREQ_WEEKLY;

    /**
     *
     * @var float|bool
     */
    public $defaultPriority = self::PRIORITY;

    /**
     *
     * @var callable
     */
    public $scope;

    public function init()
    {
        parent::init();
    }

    public static function getRules()
    {
        return [

            [
                'pattern' => 'sitemap',
                'route' => 'sitemap/default/list',
                'suffix' => '.xml'
            ],
            [
                'pattern' => 'robots',
                'route' => 'sitemap/default/robots',
                'suffix' => '.txt'
            ]
        ];
    }

    /**
     * Build and cache a site map.
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function buildSitemap()
    {
        $urls = [];

        foreach ($this->urls as $route) {
            Item::addUrl(Url::toRoute($route['loc'], true));
        }

        foreach ($this->models as $modelName) {

            if (is_array($modelName)) {
                $model = $modelName['class'];
            } else {
                $model = $modelName;
            }
            $this->generateSiteMap($model);
        }

        return $urls;
    }

    public function generateSiteMap($model)
    {
        if (method_exists($model, 'sitemap')) {
            $query = $model::sitemap();
        } else {
            $query = $model::find();

            $fileds = [
                'id',
                'title',
                'created_on'
            ];
            if ($model->hasAttribute('updated_on')) {
                $fileds[] = 'updated_on';
            }
            $query->select($fileds);

            $query->where([
                'state_id' => 1
            ]);

            $query->orderBy([
                'id' => SORT_DESC
            ]);
        }

        $query->limit(self::BATCH_MAX_SIZE);

        foreach ($query->each() as $model) {

            $lastmod = null;
            if ($model->hasAttribute('updated_on') && isset($model->updated_on)) {
                $lastmod = $model->updated_on;
            } else {
                if ($model->created_on) {
                    $lastmod = $model->created_on;
                }
            }

            Item::addUrl($model->getAbsoluteUrl(), $this->defaultPriority, $this->defaultChangefreq, $lastmod);
        }
    }

    public function process()
    {
        $this->buildSitemap();
    }
    
    public static function getCronJobs()
    {
        return [
            "20 2 * * * \t sitemap/sync"
        ];
    }
}
