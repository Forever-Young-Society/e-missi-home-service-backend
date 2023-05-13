<?php
use yii\db\Migration;

/**
 * Class m221217_120431_alter_tbl_user_add_column_avg_rating
 */
class m221217_120431_alter_tbl_user_add_column_avg_rating extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (! isset($table->columns['avg_rating'])) {
            $this->addColumn('{{%user}}', 'avg_rating', $this->string(16)
                ->defaultValue(0));
        }
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (isset($table->columns['avg_rating'])) {
            $this->dropColumn('{{%user}}', 'avg_rating');
        }
    }
}
