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
use yii\db\Migration;

/**
 * php console.php module/migrate
 */
class m220514_190503_add_colunm_view_count extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%seo_log}}');
        if ($table && ! isset($table->columns['view_count'])) {
            $this->addColumn('{{%seo_log}}', 'view_count', $this->integer(11)
                ->defaultValue(0));
        }
    }

    public function safeDown()
    {
        echo "m220514_190503_add_colunm_view_count migrating down by doing nothing....\n";
    }
}