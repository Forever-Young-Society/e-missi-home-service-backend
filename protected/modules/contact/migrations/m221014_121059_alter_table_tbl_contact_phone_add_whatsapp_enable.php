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
class m221014_121059_alter_table_tbl_contact_phone_add_whatsapp_enable extends Migration
{

    public function safeUp()
    {
        $table = \Yii::$app->db->schema->getTableSchema('{{%contact_phone}}');

        if (! isset($table->columns['whatsapp_enable'])) {

            $this->execute("Alter TABLE `tbl_contact_phone` ADD `whatsapp_enable` int(11) DEFAULT '0' AFTER `country`; ");
        }
    }

    public function safeDown()
    {
        echo "m221014_121059_alter_table_tbl_contact_phone_add_whatsapp_enable migrating down by doing nothing....\n";
    }
}