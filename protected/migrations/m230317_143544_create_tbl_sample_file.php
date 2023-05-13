<?php
use yii\db\Migration;

/**
 * Class m230317_143544_create_tbl_sample_file
 */
class m230317_143544_create_tbl_sample_file extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%sample_file}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_sample_file`;
CREATE TABLE IF NOT EXISTS `tbl_sample_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `state_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`model_type`),
  INDEX(`model_id`),
  INDEX(`created_on`),
  INDEX(`created_by_id`),
  KEY `fk_sample_file_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_sample_file_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%sample_file}}');
        if (isset($table)) {
            $this->dropTable('{{%sample_file}}');
        }
    }
}
