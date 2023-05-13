<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use Mpdf\Mpdf;
use yii\helpers\ArrayHelper;

class TPdfWriter extends Mpdf
{

    public $data = [];

    public function __construct($data = null)
    {
        $this->data['tempDir'] = \Yii::getAlias('@runtime');

        if (! empty($data) && is_array($data)) {

            $this->data = ArrayHelper::merge($this->data, $data);
        }

        parent::__construct($this->data);
        $this->SetProtection([
            'print'
        ]);
        
        if (! \Yii::$app instanceof \yii\console\Application) {
            $this->SetAuthor(\Yii::$app->user->userName);
            $this->SetCreator(\Yii::$app->user->userName);
        }
    }

    public function Output($name = '', $dest = '')
    {
        $this->SetSubject($name);
        $this->SetTitle($name);
        return parent::Output($name, $dest);
    }

    public function enableWaterMark($text = null)
    {
        // call watermark content aand image
        $this->SetWatermarkText($text != null ? $text : \Yii::$app->name);
        $this->showWatermarkText = true;
        $this->watermarkTextAlpha = 0.1;
    }
}

?>
