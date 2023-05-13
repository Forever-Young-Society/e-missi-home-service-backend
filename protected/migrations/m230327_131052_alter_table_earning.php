<?php
use yii\db\Migration;

/**
 * Class m230327_131052_alter_table_earning
 */
class m230327_131052_alter_table_earning extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%earning}}');
        if (! isset($table)) {
            $this->execute("CREATE TABLE IF NOT EXISTS `tbl_earning` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `provider_id` int DEFAULT '0',
  `provider_amount` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `booking_amount` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `admin_amount` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `state_id` int DEFAULT '1',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_earning_created_by` (`created_by_id`),
  CONSTRAINT `fk_earning_created_by` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%earning}}');
        if (isset($table)) {
            $this->dropTable('{{%earning}}');
        }
    }
}
