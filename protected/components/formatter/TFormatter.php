<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components\formatter;

use yii\helpers\Html;
use yii\i18n\Formatter;

class TFormatter extends Formatter
{

    public function init()
    {
        parent::init();
        $this->nullDisplay = '';
    }

    public function asHours($value, $format = '%02d:%02d Hours')
    {
        if (empty($value) || ! is_numeric($value)) {
            return false;
        }

        $minutes = round($value / 60);
        $hours = floor($minutes / 60);
        $remainMinutes = ($minutes % 60);

        return sprintf($format, $hours, $remainMinutes);
    }

    /**
     * Formats the value as a tel link.
     * Usage: 'contact_no:phone',
     *
     * @param string|null $value
     *            the value to be formatted.
     * @param array $options
     *            the tag options in terms of name-value pairs.
     * @return string the formatted result.
     */
    public function asPhone($value, $options = [])
    {
        if (empty($value)) {
            return $this->nullDisplay;
        }
        $url = 'tel:' . $value;
        return Html::a($value, $url, $options);
    }

    /**
     * Formats the value as a click to call link.
     * Usage : 'contact_no:clickToCall',
     *
     * @param string|null $value
     *            the value to be formatted.
     * @param array $options
     *            the tag options in terms of name-value pairs.
     * @return string the formatted result.
     */
    public function asClickToCall($value, $options = [])
    {
        if (empty($value)) {
            return $this->nullDisplay;
        }
        $value = preg_replace('/\s+|-/', '', $value);
        \Yii::warning('asClickToCall:', $value);
        if (\Yii::$app->hasModule('calling')) {

            return \app\modules\calling\models\Mobile::showCallingButton($value);
        }
        return $value;
    }

        /**
     * bnumber formatter
     * Usage : 'count:bnumber',
     * @param int $n
     * @return boolean|string
     */
    public function asBnumber($n)
    {
        // first strip any formatting;
        $n = (0 + str_replace(",", "", $n));
        // is this a number?
        if (! is_numeric($n))
            return false;

        // now filter it;
        if ($n > 1000000000000)
            return round(($n / 1000000000000), 1) . 'T';
        else if ($n > 1000000000)
            return round(($n / 1000000000), 1) . 'B';
        else if ($n > 1000000)
            return round(($n / 1000000), 1) . 'M';
        else if ($n > 1000)
            return round(($n / 1000), 1) . 'K';

        return number_format($n);
    }

}
