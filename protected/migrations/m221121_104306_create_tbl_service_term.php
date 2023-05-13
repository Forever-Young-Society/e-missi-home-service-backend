<?php
use yii\db\Migration;

/**
 * Class m221121_104306_create_tbl_service_term
 */
class m221121_104306_create_tbl_service_term extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%service_term}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_service_term`;
CREATE TABLE IF NOT EXISTS `tbl_service_term` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`state_id`),
  KEY `FK_service_term_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_service_term_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%service_term}}');
        if (isset($table)) {
            $this->dropForeignKey('fk_service_term_created_by_id', '{{%service_term}}', 'created_by_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
            $this->dropTable('{{%service_term}}');
        }
    }
}
