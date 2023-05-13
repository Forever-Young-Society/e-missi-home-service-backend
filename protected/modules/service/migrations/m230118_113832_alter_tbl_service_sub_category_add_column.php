<?php
use yii\db\Migration;

/**
 * Class m230118_113832_alter_tbl_service_sub_category_add_column
 */
class m230118_113832_alter_tbl_service_sub_category_add_column extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%service_sub_category}}');
        if (! isset($table->columns['combination_count'])) {
            $this->addColumn('{{%service_sub_category}}', 'combination_count', $this->integer(11)
                ->defaultValue(1));
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%service_sub_category}}');
        if (isset($table->columns['combination_count'])) {
            $this->dropColumn('{{%service_sub_category}}', 'combination_count');
        }
    }
}
