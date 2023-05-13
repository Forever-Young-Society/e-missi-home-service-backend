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
class m220310_110318_alter_tbl_settings_variable_add_created_on_updated_on extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%settings_variable}}');
        if (!isset($table->columns['created_on'])) {
            $this->execute("ALTER TABLE `tbl_settings_variable` ADD `created_on` DATETIME NULL AFTER `state_id`;  ");
        }
        $table = Yii::$app->db->getTableSchema('{{%settings_variable}}');
        if (!isset($table->columns['updated_on'])) {
            $this->execute(" ALTER TABLE `tbl_settings_variable` ADD `updated_on` DATETIME DEFAULT NULL AFTER `created_by_id` ");
        }
    }

    public function safeDown()
    {
        echo "m220310_110318_alter_tbl_settings_variable_add_created_on_updated_on migrating down by doing nothing....\n";
    }
}