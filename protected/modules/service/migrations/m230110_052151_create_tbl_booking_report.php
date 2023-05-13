<?php
use yii\db\Migration;

/**
 * Class m230110_052151_create_tbl_booking_report
 */
class m230110_052151_create_tbl_booking_report extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%service_report}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_service_report`;
CREATE TABLE IF NOT EXISTS `tbl_service_report` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_provided` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int DEFAULT '0',
  `booking_id` int NOT NULL,
  `service_id` int DEFAULT '0',
  `user_id` int DEFAULT '0',
  `dependant_id` int DEFAULT '0',
  `state_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`),
  INDEX(`booking_id`),
  INDEX(`state_id`),
  INDEX(`created_on`),
  INDEX(`created_by_id`),
  KEY `fk_service_report_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_service_report_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%service_report}}');
        if (isset($table)) {
            $this->dropTable('{{%service_report}}');
        }
    }
}
