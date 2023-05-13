<?php
use yii\db\Migration;

/**
 * Class m221227_093455_create_tbl_booking_service
 */
class m221227_093455_create_tbl_booking_service extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%availability_booking_service}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_availability_booking_service`;
CREATE TABLE IF NOT EXISTS `tbl_availability_booking_service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int NOT NULL,
  `service_id` int NOT NULL,
  `state_id` int DEFAULT '1',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_availability_booking_service_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_availability_booking_service_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%availability_booking_service}}');
        if (isset($table)) {
            $this->dropTable('{{%availability_booking_service}}');
        }
    }
}
