<?php
use yii\db\Migration;

/**
 * Class m230125_105225_alter_service_report_change_column_service_provided
 */
class m230125_105225_alter_service_report_change_column_service_provided extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%service_report}}');
        if (isset($table->columns['service_provided'])) {
            $this->execute("ALTER TABLE `tbl_service_report` CHANGE `service_provided` `service_provided` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%service_report}}');
        if (isset($table->columns['service_provided'])) {
            $this->execute("ALTER TABLE `tbl_service_report` CHANGE `service_provided` `service_provided` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
        }
    }
}
