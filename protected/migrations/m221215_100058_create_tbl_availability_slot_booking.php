<?php
use yii\db\Migration;

/**
 * Class m221215_100058_create_tbl_availability_slot_booking
 */
class m221215_100058_create_tbl_availability_slot_booking extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%availability_slot_booking}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_availability_slot_booking`;
CREATE TABLE IF NOT EXISTS `tbl_availability_slot_booking` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `provider_id` int NOT NULL,
  `dependant_id` int DEFAULT '0',
  `service_id` int DEFAULT '0',
  `slot_id` varchar(16) NOT NULL,/* multiple */
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_reschedule` int DEFAULT '0',
  `user_reschedule` int DEFAULT '0',
  `payment_status` int DEFAULT '0',
  `is_reschedule_confirm` int NULL DEFAULT '0',
  `old_start_time` datetime DEFAULT NULL,
  `old_end_time` datetime DEFAULT NULL,
  `state_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`),
  KEY `fk_availability_slot_booking_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_availability_slot_booking_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%availability_slot_booking}}');
        if (isset($table)) {
            $this->dropTable('{{%availability_slot_booking}}');
        }
    }
}
