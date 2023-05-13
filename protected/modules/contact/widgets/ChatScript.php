<?php
/**
 * @link https://github.com/yiimaker/yii2-social-share
 * @copyright Copyright (c) 2017 Yii Maker
 * @license BSD 3-Clause License
 */
namespace app\modules\contact\widgets;

use app\components\TBaseWidget;
use app\components\helpers\TArrayHelper;
use app\modules\contact\models\Chatscript as ChatscriptModel;
use app\modules\contact\models\search\Information;
use yii\helpers\Url;
use yii\helpers\VarDumper;

class ChatScript extends TBaseWidget
{

    public string $email = '';

    public string $name = '';

    public string $contact = '';

    private $is_user_valid = 0;

    public $chat_script = null;

    public function init()
    {
        parent::init();

        $this->chat_script = ChatscriptModel::findActive()->orderBy('id DESC')->one();

        if ($this->chat_script == null) {

            $this->chat_script = ChatscriptModel::loadFromSEO();
        }

        if (empty($this->chat_script)) {
            $this->visible = false;
        }

        if (! $this->visible) {
            return;
        }
        // disable show_bubble for contact page
        if (\Yii::$app->controller->action->id == 'info-address') {
            $this->chat_script->show_bubble = 0;
        }
        \Yii::warning(' ChatScript ' . VarDumper::dumpAsString($this->chat_script));

        if (\Yii::$app->user->isGuest) {
            $this->loadFromContactForm();
        } else {
            $this->loadFromUser();
        }
    }

    protected function loadFromContactForm()
    {
        $outJson = \Yii::$app->session->get(Information::CONTACT_SESSION_KEY);
        // \Yii::warning(' sesssion contact found ' . Json::encode($outJson));

        if (! empty($outJson)) {
            $this->email = ! empty($outJson['email']) ? $outJson['email'] : "";
            $this->name = ! empty($outJson['full_name']) ? $outJson['full_name'] : "";
            $this->contact = ! empty($outJson['mobile']) ? $outJson['mobile'] : "";
        }
        if (! empty($this->email)) {
            $this->is_user_valid = 1;
        }
    }

    protected function loadFromUser()
    {
        \Yii::warning('loadFromUser  ' . \Yii::$app->user->identity);
        if (\Yii::$app->user->identity->hasProperty('email')) {
            $this->email = \Yii::$app->user->identity->email;
        }
        if (\Yii::$app->user->identity->hasProperty('full_name')) {
            $this->name = \Yii::$app->user->identity->full_name;
        }
        if (\Yii::$app->user->identity->hasProperty('contact_no')) {
            $this->contact = \Yii::$app->user->identity->contact_no . '';
        }
        if (! empty($this->email)) {
            $this->is_user_valid = true;
        }
    }

    public function renderHtml()
    {
        $toggleDelay = (YII_ENV == 'prod') ? $this->chat_script->popup_delay * 1000 : 1000;
        $windowsStatus = $this->chat_script->show_bubble ? 'open' : 'close';
        $baseUrl = $this->chat_script->chat_server ?: "https://woot.jilivechat.com";
        $urlAlert = Url::toRoute(TArrayHelper::merge([
            '/contact/information/chat'
        ], \Yii::$app->request->queryParams));

        echo \Yii::$app->view->registerJs('(function(d,t) {
            var user_valid = ' . $this->is_user_valid . ';
            var BASE_URL= "' . $baseUrl . '";
            var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=BASE_URL+"/packs/js/sdk.js";
            s.parentNode.insertBefore(g,s);
            g.onload=function(){
                window.chatwootSettings = {
                    type: "expanded_bubble",
                    showPopoutButton: true,
                },
                window.chatwootSDK.run({
                    websiteToken:"' . $this->chat_script->script_code . '",
                    baseUrl: BASE_URL
                },

                window.addEventListener("chatwoot:ready", function () {
                    if ( user_valid){

                        window.$chatwoot.setUser("' . $this->email . '", {
                            email: "' . $this->email . '",
                            name: "' . $this->name . '",
              
                            phone_number: "' . $this->contact . '",
                        });
                    }else{
                      setTimeout(() => {
                     
                                       window.$chatwoot.toggle("' . $windowsStatus . '");
                                       }, ' . $toggleDelay . ');
                        }
                        
                    }))  
          }
            })(document,"script");');
    }
}
