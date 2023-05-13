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
namespace app\modules\sitemap\helpers;

use yii\base\Behavior;
use yii\base\InvalidConfigException;

/**
 * Behavior for XML Sitemap Yii2 module.
 *
 * For example:
 *
 * ```php
 * public function behaviors()
 * {
 * return [
 * 'sitemap' => [
 * 'class' => SitemapBehavior::className(),
 * 'scope' => function ($model) {
 * $model->select(['url', 'lastmod']);
 * $model->andWhere(['is_deleted' => 0]);
 * },
 * 'dataClosure' => function ($model) {
 * return [
 * 'loc' => Url::to($model->url, true),
 * 'lastmod' => strtotime($model->lastmod),
 * 'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
 * 'priority' => 0.8
 * ];
 * }
 * ],
 * ];
 * }
 * ```
 *
 * @see http://www.sitemaps.org/protocol.html
 *
 */
class SitemapBehavior extends Behavior
{

    public function sitemap()
    {
        return self::find();
    }
}
