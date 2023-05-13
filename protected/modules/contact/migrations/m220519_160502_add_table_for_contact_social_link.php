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
class m220519_160502_add_table_for_contact_social_link extends Migration
{

    public function safeUp()
    {
        $table1 = Yii::$app->db->getTableSchema('{{%contact_social_link}}');

        if (! $table1) {

            $table = Yii::$app->db->getTableSchema('{{%social_link}}');
            if ($table) {

                $this->execute("ALTER TABLE {{%social_link}} RENAME TO {{%contact_social_link}};");
            } else {

                $this->execute("
                CREATE TABLE IF NOT EXISTS `tbl_contact_social_link` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                  `ext_url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                  `type_id` int(11) NOT NULL DEFAULT '0',
                  `state_id` int(11) NOT NULL DEFAULT '1',
                  `created_on` datetime DEFAULT NULL,
                  `created_by_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                   INDEX(`title`),
                   INDEX(`created_on`),
                   INDEX(`state_id`),
                  KEY `fk_contact_social_link_created_by_id` (`created_by_id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
            }
        }
    }

    public function safeDown()
    {}
}