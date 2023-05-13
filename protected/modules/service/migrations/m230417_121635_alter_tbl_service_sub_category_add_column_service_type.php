<?php
use yii\db\Migration;

/**
 * Class m230417_121635_alter_tbl_service_sub_category_add_column_service_type
 */
class m230417_121635_alter_tbl_service_sub_category_add_column_service_type extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%service_sub_category}}');
        if (! isset($table->columns['service_type'])) {
            $this->addColumn('{{%service_sub_category}}', 'service_type', $this->integer(11)
                ->defaultValue(0));
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%service_sub_category}}');
        if (isset($table->columns['service_type'])) {
            $this->dropColumn('{{%service_sub_category}}', 'service_type');
        }
    }
}
