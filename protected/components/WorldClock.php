<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use powerkernel\flagiconcss\Flag;

// You are needed "powerkernel/yii2-flag-icon-css": "*" in require in your composer

/* How to use it ? */

/*
 * use app\components\WorldClock;
 * <?=
 * WorldClock::widget([
 * 'countries' => [
 * 'XX' => 'Country Name' // where XX is the ISO 3166-1-alpha-2 code of a country
 * ],
 * 'title'=>false // make it true to show country name also
 * ]);
 * ?>
 */
class WorldClock extends TBaseWidget
{

    public $countries = [];

    public $enableName = true;

    public $color = [
        "bg-primary",
        "bg-info",
        "bg-success",
        'bg-danger',
        'bg-warning',
        'bg-danger'
    ];

    public static $cstList = [
        'Argentina' => 'America/Argentina/Buenos_Aires',
        'Australia' => 'Australia/Sydney',
        'Brazil' => 'America/Bahia ',
        'Canada' => 'America/Iqaluit ',
        'Chile' => 'America/Santiago',
        'China' => 'Asia/Shanghai',
        'Congo' => 'Africa/Kinshasa ',
        'Ecuador' => 'America/Guayaquil',
        'French Polynesia' => 'Pacific/Tahiti',
        'Greenland' => 'America/Nuuk',
        'Indonesia' => 'Asia/Jakarta',
        'Kazakhstan' => 'Asia/Almaty',
        'Kiribati' => 'Pacific/Tarawa',
        'Mexico' => 'America/Mexico_City ',
        'Micronesia' => 'Pacific/Kosrae',
        'Mongolia' => 'Asia/Ulaanbaatar',
        'Papua New Guinea' => 'Pacific/Port_Moresby',
        'Portugal, Portuguese Republic' => 'Europe/Lisbon',
        'Russian Federation' => 'Europe/Moscow',
        'Spain' => 'Europe/Madrid',
        'United States of America' => 'America/New_York'
    ];

    public function init()
    {
        parent::init();

        $this->countries[] = 'India';
    }

    public function renderHtml()
    {
        ?>

<div class="world-clock">
	<ul class="d-flex pl-0">
    <?php
        foreach ($this->countries as $index => $country) {
            $countryCode = World::findIdByName($country);
            if (array_key_exists($country, self::$cstList)) {
                $timeZone = self::findCstByCountry($country);
            } else {
                try {

                    if ($countryCode == null) {
                        continue;
                    }
                    $timeZone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $countryCode)[0];
                } catch (\Exception $e) {
                    continue;
                }
            }
            $dateTime = new \DateTime("now", new \DateTimeZone($timeZone));
            $date = $dateTime->format('Y-m-d');
            $time = $dateTime->format('H:i:s');
            $key = $index % count($this->color);

            $color = $this->color[$key];

            ?> 
            <li
			class="d-flex mr-3 p-2 border text-white <?php echo $color?>">
			<p class="mb-0 font-weight-bold mr-2">
    	        	<?= Flag::widget(['tag' => 'span', 'country' => strtolower($countryCode), 'squared' => false, 'options' => [] ]); ?>
        	    	<?= $this->enableName?$country.' :  ':''; ?>
        	    </p> <span
			class="badge badge-light text-dark mr-2 p-1 bg-white">
        	        <?= $date; ?>
        	        </span> <span
			class="badge badge-light text-dark p-1 bg-white">	
        	        <?= $time; ?></span>

		</li>
	<?php
        }
        ?>
</ul>

</div>
<br><?php
    }

    public static function csts()
    {
        return self::$cstList;
    }

    public static function findCstByCountry($country)
    {
        if (isset(self::$cstList[($country)]))
            return self::$cstList[($country)];
        return $country;
    }
}