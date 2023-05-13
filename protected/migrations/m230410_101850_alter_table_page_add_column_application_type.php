<?php
use yii\db\Migration;

/**
 * Class m230410_101850_alter_table_page_add_column_application_type
 */
class m230410_101850_alter_table_page_add_column_application_type extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%page}}');
        if (! isset($table->columns['application_type'])) {
            $this->addColumn('{{%page}}', 'application_type', $this->integer()
                ->defaultValue(0)
                ->after('type_id'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%page}}');
        if (isset($table->columns['application_type'])) {
            $this->dropColumn('{{%page}}', 'application_type');
        }
    }
}
