<?php
use yii\db\Migration;

/**
 * Class m230111_101056_create_tbl_payment_response
 */
class m230111_101056_create_tbl_payment_response extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%payment_response}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_payment_response`;
CREATE TABLE IF NOT EXISTS `tbl_payment_response` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`state_id`),
  INDEX(`created_on`),
  INDEX(`created_by_id`),
  KEY `FK_payment_response_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_payment_response_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%payment_response}}');
        if (isset($table)) {
            $this->dropTable('{{%payment_response}}');
        }
    }
}
