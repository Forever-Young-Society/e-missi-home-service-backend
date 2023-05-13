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
class m220519_120504_change_type_column_view_count extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%seo_log}}');
        if ($table && isset($table->columns['view_count'])) {
            $this->execute("UPDATE {{%seo_log}} SET `view_count`=0 WHERE 1");
        }
    }

    public function safeDown()
    {
        echo "m220517_120504_change_type_column_view_count migrating down by doing nothing....\n";
    }
}