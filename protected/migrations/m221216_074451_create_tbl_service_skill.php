<?php
use yii\db\Migration;

/**
 * Class m221216_074451_create_tbl_service_skill
 */
class m221216_074451_create_tbl_service_skill extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%service_skill}}');
        if (! isset($table)) {
            $this->execute("DROP TABLE IF EXISTS `tbl_service_skill`;
CREATE TABLE IF NOT EXISTS `tbl_service_skill` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int NOT NULL,
  `parent_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
   PRIMARY KEY (`id`),
   INDEX(`id`),
   INDEX(`title`),
   INDEX(`state_id`),
   KEY `fk_service_skill_created_by_id` (`created_by_id`),
   CONSTRAINT `fk_service_skill_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->getTableSchema('{{%service_skill}}');
        if (isset($table)) {
            $this->dropTable('{{%service_skill}}');
        }
    }
}
