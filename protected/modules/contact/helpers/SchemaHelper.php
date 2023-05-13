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
namespace app\modules\contact\helpers;

use Spatie\SchemaOrg\Schema;
use app\modules\contact\models\Address;
use app\modules\contact\models\Phone;
use app\modules\contact\models\SocialLink;
use yii\helpers\Url;

/**
 * This is the model class
 */
class SchemaHelper
{

    public static function getOrganization()
    {
        $organization = Schema::organization()->name(\Yii::$app->params['company'])->url(Url::home(true));

        $addressQuery = Address::findActive();
        $address = $addressQuery->one();
        if ($address) {
            $organization->email($address->email)->address($address->address);
        }

        $organization->logo(Url::to(\Yii::$app->getView()->theme->getUrl('img/logo.png'), true));
        $organization->foundingDate('2012')->founder([
            'name' => 'Shiv Charan Panjeta'
        ]);
        $socialLinks = SocialLink::findActive()->select('ext_url')->column();

        $organization->sameAs($socialLinks);

        $contactQuery = Phone::findActive();
        $contacts = [];
        foreach ($contactQuery->each() as $contact) {
            $address = Address::findActive()->andWhere([
                'country' => $contact->country
            ])->one();
            $address_point = ($address) ? $address->address : $contact->country;

            $contacts[] = Schema::contactPoint()->telephone($contact->contact_no)
                ->contactType($contact->title)
                ->areaServed($address_point);
        }

        $organization->contactPoint($contacts);
        return $organization->toScript();
    }

    public static function getWebsiteSchema()
    {
        $propertyValueSpecification = Schema::propertyValueSpecification()->valueRequired(true)->valueName('search_term_string');
        $searchAction = Schema::searchAction()->target(Url::to([
            'site/search/?q=' . '{search_term_string}'
        ], true))->setProperty('query-input', [
            $propertyValueSpecification
        ]);

        $website = Schema::webSite()->name(\Yii::$app->params['company'])->url(Url::home(true));
        $website->potentialAction($searchAction);

        return $website->toScript();
    }
}
