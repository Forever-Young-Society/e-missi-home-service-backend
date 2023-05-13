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
class m220302_101259_install_smtp extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%emailreader_outgoing_server}}');
        if ($table) {
            $this->execute("ALTER TABLE {{%emailreader_outgoing_server}} RENAME TO {{%smtp_account}};");
            $this->execute("ALTER TABLE {{%smtp_account}} CHANGE `smtp_server` `server` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL; ");
            $this->execute("ALTER TABLE {{%smtp_account}} CHANGE `smtp_port` `port` INT NOT NULL DEFAULT '25'; ");
        } else {
            $table = Yii::$app->db->getTableSchema('{{%smtp_account}}');
            if (! $table) {
                $sql = file_get_contents(__DIR__ . '/../db/install.sql');
                $this->execute($sql);
            }
        }
    }

    public function safeDown()
    {
        // cannot remove tables for now.
    }
}