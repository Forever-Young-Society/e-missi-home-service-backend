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
namespace app\modules\contact\widgets;

use app\components\TBaseWidget;
use yii\web\Cookie;

class PopUpWidget extends TBaseWidget
{

    public $cookie_name = 'black-friday';

    public $banner_img_url;

    public $badge_img_url;

    public $expire;

    public function run()
    {
        if ($this->expire < date('Y-m-d H:i')) {
            $this->visible = false;
        }
        if ($this->visible) {
            $this->getView()->registerAssetBundle(\app\modules\contact\widgets\assets\PopUpAsset::class);
            $cookie = new Cookie([
                'name' => $this->cookie_name,
                'value' => time(),
                'expire' => time() + 86400 * 30,
                'domain' => \Yii::$app->request->hostName
            ]);
            \Yii::$app->response->cookies->add($cookie);

            return $this->render('pop-up', [
                'img_url' => $this->banner_img_url,
                'badge_img_url' => $this->badge_img_url,
                'cookie_name' => $this->cookie_name
            ]);
        }
    }
}
