<?php
use yii\db\Migration;

/**
 * Class m221226_132622_alter_tbl_service_sub_category_add_column_parent_id
 */
class m221226_132622_alter_tbl_service_sub_category_add_column_parent_id extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%service_sub_category}}');
        if (! isset($table->columns['parent_id'])) {
            $this->addColumn('{{%service_sub_category}}', 'parent_id', $this->integer(11)
                ->defaultValue(0)
                ->after('category_id'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%service_sub_category}}');
        if (isset($table->columns['parent_id'])) {
            $this->dropColumn('{{%service_sub_category}}', 'parent_id');
        }
    }
}
