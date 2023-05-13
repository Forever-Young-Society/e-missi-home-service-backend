<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\modules\settings\components;

use yii\base\Component;
use app\modules\settings\models\Variable;

class Keys extends Component
{

    public $enable_cache = false;

    public function init()
    {
        parent::init();
    }

    public function __set($key, $value)
    {
        return $this->setValue($key, $value);
    }

    public function __get($key)
    {
        return $this->getValue($key);
    }

    protected function findByKey($key, $m)
    {
        return Variable::find()->where([
            'key' => $key,
            'module' => $m
        ])->one();
    }

    public function setValue($key, $value, $m = null)
    {
        if (strstr($key, '.')) {
            list ($m, $key) = explode('.', $key);
        }
        $m = $m ?? \Yii::$app->controller->module->id;

        $model = $this->findByKey($key, $m);

        if (is_null($value) && ! is_null($model)) {
            $model->delete();
            Variable::log("setvalue  $m . $key ==>  $value", $m);
            return true;
        }
        if (is_null($model)) {
            $model = new Variable();
            $model->loadDefaultValues();
            $model->state_id = Variable::STATE_ACTIVE;
            $model->key = $key;
            $model->module = $m;
        }

        $model->value = (string) $value;
        if (! $model->save()) {
            \Yii::error("setvalue failed $m . $key ==>  $value", $m);
        }
        if ($this->enable_cache) {
            \Yii::$app->cache->set($m . $key, $value);
        }
        Variable::log("setvalue  $m . $key ==>  $value", $m);
    }

    public function getValue($key, $value = null, $m = null)
    {
        if (strstr($key, '.')) {
            list ($m, $key) = explode('.', $key);
        }
        $m = $m ?? \Yii::$app->controller->module->id;

        if ($this->enable_cache && \Yii::$app->cache->exists($m . $key)) {
            $value = \Yii::$app->cache->get($m . $key);
        } else {
            $model = $this->findByKey($key, $m);

            if (! is_null($model)) {
                $value = $model->value;
                if ($this->enable_cache) {
                    \Yii::$app->cache->set($m . $key, $value);
                }
            }
        }
        Variable::log("getvalue  $m . $key ==>  $value", $m);
        return $value;
    }

    public static function defaultCurrency()
    {
        return self::getCurrencySymbol('INR');
    }

    public static function getCurrencySymbol($currencyCode, $locale = 'en_US')
    {
        $formatter = new \NumberFormatter($locale . '@currency=' . $currencyCode, \NumberFormatter::CURRENCY);
        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }
}
