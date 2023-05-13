<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\gdpr;

use app\components\TBaseWidget;
use app\components\gdpr\assets\GdprAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Cookie;

class Gdpr extends TBaseWidget
{

    private $name = null;

    public $privacylink = '/site/privacy';

    public $description = "We use cookies, check our {privacy}.";

    public function init()
    {
        parent::init();
        $this->name = "gdpr_" . \Yii::$app->id;

        $isSet = \Yii::$app->request->cookies->getValue($this->name);
        \Yii::info($this->name . ' : Coockie: ' . $isSet);
        if (YII_ENV == 'dev' || ! empty($isSet)) {
            $this->visible = false;
        } else {
            GdprAsset::register(\Yii::$app->getView());

            $this->description = str_replace('{privacy}', Html::a('Privacy Policies', Url::toRoute([
                $this->privacylink
            ])), $this->description);
        }
        \Yii::info($this->name . ' : $this->visible: ' . $this->visible);
        $post = \Yii::$app->request->post('accept');

        if ($post) {
            $cookie = new Cookie([
                'name' => $this->name,
                'value' => $post,
                'expire' => time() + 86400 * 365,
                'domain' => \Yii::$app->request->hostName,
                'path' => \Yii::$app->request->baseUrl
            ]);
            \Yii::$app->response->cookies->add($cookie);
            \Yii::$app->controller->redirect(\Yii::$app->request->referrer);
        }
    }

    public function renderHtml()
    {
        echo $this->render('gdpr', [
            'description' => $this->description
        ]);
    }
}
