<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\base;

use app\components\helpers\TLogHelper;
use app\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 *
 * {@inheritdoc}
 */
class TBaseActiveRecord extends ActiveRecord
{

    use TLogHelper;

    public function isAllowed()
    {
        if (User::isAdmin())
            return true;
        if ($this instanceof User) {
            return ($this->id == Yii::$app->user->id);
        }

        return User::isUser();
    }

    public static function getExtUrlBase()
    {
        if (isset(Yii::$app->params['extBaseUrl'])) {
            return Yii::$app->params['extBaseUrl'];
        }
        return Url::home(true);
    }

    public static function getExtUrl($url)
    {
        return str_replace(Url::home(true), self::getExtUrlBase(), $url);
    }

    public function getDate()
    {
        return Yii::$app->formatter->asDate(time(), 'long');
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        return true;
    }

    public function beforeSave($insert)
    {
        if (! parent::beforeSave($insert)) {
            return false;
        }
        if (! $insert && ($this->oldAttributes['state_id'] != $this->state_id)) {
            $this->beforeStateChange($this->oldAttributes['state_id'], $this->state_id);
        }
        return true;
    }

    public function beforeStateChange($old_state_id, $state_id)
    {
        self::log("beforeStateChange from :$old_state_id  to :$state_id");
    }

    /**
     * Return date value set using session
     * @param string $attribute
     * @return $date
     */
    public function getDateRangeValue($attribute)
    {
        $session = Yii::$app->session;
        $value = $session->get($attribute);
        return isset($value) ? $value : false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (! $insert && ($this->oldAttributes['state_id'] != $this->state_id)) {
            $this->afterStateChange($this->oldAttributes['state_id'], $this->state_id);
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterStateChange($old_state_id, $state_id)
    {
        self::log("afterStateChange from :$$old_state_id  to :$state_id");
    }

    public function getChangedContent($changedAttributes)
    {
        $msg = '';
        foreach ($changedAttributes as $key => $change) {
            if (! empty($change)) {

                if ($key == 'state_id') {
                    $change = $this->getStateOptions()[$change] . '==>' . $this->getState();
                } else if ($key == 'type_id') {
                    $change = $this->getTypeOptions()[$change] . '==>' . $this->gettype();
                } else {
                    $list = self::attributeLabels();
                    $change = $list[$key] . ' : ' . $change . '==>' . $this->$key;
                }

                $msg .= $change . PHP_EOL;
            }
        }
        return $msg;
    }
}
