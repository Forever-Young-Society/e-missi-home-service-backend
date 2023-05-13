<?php
use yii\db\Migration;

/**
 * Class m230320_050855_create_tbl_import_file
 */
class m230320_050855_create_tbl_import_file extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%import_file}}');
        if (! isset($table)) {
            $this->execute("CREATE TABLE IF NOT EXISTS `tbl_import_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(11) DEFAULT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int DEFAULT '0',
  `percentage` int DEFAULT '0',
  `failure_reason` longtext DEFAULT NULL,
  `total_count` int DEFAULT '0',
  `download_count` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`model_type`),
  INDEX(`model_id`),
  INDEX(`created_on`),
  INDEX(`created_by_id`),
  KEY `fk_import_file_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_import_file_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%import_file}}');
        if (isset($table)) {
            $this->dropTable('{{%import_file}}');
        }
    }
}
