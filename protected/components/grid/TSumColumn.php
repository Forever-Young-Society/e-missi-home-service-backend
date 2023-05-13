<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */

namespace app\components\grid;

use yii\grid\DataColumn;

/**
 * Sum of all the values in the column
 * @author shiv
 *
 */
class TSumColumn extends DataColumn {
    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if ( is_numeric($value))
        {
            $this->footer += $value;
        }
        
        return $value;
    }
}