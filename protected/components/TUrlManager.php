<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use app\components\helpers\TArrayHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\UrlManager;

class TUrlManager extends UrlManager
{

    public $enablePrettyUrl = true;

    public $showScriptName = false;

    public $forceSSL = false;

    public $normalizer = [
        'class' => 'yii\web\UrlNormalizer'
        // use temporary redirection instead of permanent for debugging
        // 'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY
    ];

    public function getHostInfo()
    {
        $info = null;
        if ( isset(Yii::$app->params['extBaseUrl'])) {
            $info = Yii::$app->params['extBaseUrl'];
            \Yii::warning('extBaseUrl:getHostInfo:' . $info);
        } else {
            try {
                $info = parent::getHostInfo();
            } catch (\Exception $e) {
                \Yii::warning(':getHostInfo:' . $e->getMessage());
            }
        }
        if (! isset($info)) {
            throw new InvalidConfigException('Please configure UrlManager::hostInfo or params[extBaseUrl] correctly as you are running a console application.');
        }

        \Yii::warning(':getHostInfo:' . $info);
        return $info;
    }

    public function getBaseUrl()
    {
        try {
            $baseUrl = parent::getBaseUrl();
        } catch (\Exception $e) {
            $baseUrl = '';
        }

        return $baseUrl;
    }

    public function createAbsoluteUrl($params, $scheme = null)
    {
        return parent::createAbsoluteUrl($params, $this->forceSSL ? 'https' : $scheme);
    }

    /**
     * Parses the given request and returns the corresponding route and parameters.
     *
     * @param \yii\web\UrlManager $manager
     *            the URL manager
     * @param \yii\web\Request $request
     *            the request component
     * @return array|boolean the parsing result. The route and the parameters are returned as an array.
     *         If false, it means this rule cannot be used to parse this path info.
     */
    public static function cleanText($text = "")
    {
        $text = strip_tags($text);
        $text = preg_replace('/[^A-Z0-9]+/i', '-', $text);
        $text = strtolower(trim($text, '-'));

        return $text;
    }

    public function parseRequest($request)
    {
        return parent::parseRequest($request);
    }

    /**
     * Creates a URL according to the given route and parameters.
     *
     * @param \yii\web\UrlManager $manager
     *            the URL manager
     * @param string $route
     *            the route. It should not have slashes at the beginning or the end.
     * @param array $params
     *            the parameters
     * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($params)
    {
        preg_replace_callback('/(?<![A-Z])[A-Z]/', function ($matches) {
            return '-' . lcfirst($matches[0]);
        }, $params[0]);

        if (isset($params['title'])) {
            $params['title'] = self::cleanText($params['title']);
        }

        return parent::createUrl($params);
    }

    public function makeAbsoluteUrl($url)
    {
        if (strpos($url, '://') === false) {
            $hostInfo = $this->getHostInfo();
            if (strncmp($url, '//', 2) === 0) {
                $url = substr($hostInfo, 0, strpos($hostInfo, '://')) . ':' . $url;
            } else {
                $url = $hostInfo . $url;
            }
        }

        return $url;
    }
}