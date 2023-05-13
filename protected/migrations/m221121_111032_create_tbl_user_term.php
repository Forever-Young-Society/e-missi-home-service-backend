<?php
use yii\db\Migration;

/**
 * Class m221121_111032_create_tbl_user_term
 */
class m221121_111032_create_tbl_user_term extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%user_term}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_user_term`;
CREATE TABLE IF NOT EXISTS `tbl_user_term` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `term_id` int DEFAULT '0',
  `category_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_user_term_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_user_term_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%user_term}}');
        if (isset($table)) {
            $this->dropForeignKey('fk_user_term_created_by_id', '{{%user_term}}', 'created_by_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
            $this->dropTable('{{%user_term}}');
        }
    }
}
