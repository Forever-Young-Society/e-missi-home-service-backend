<?php
use yii\db\Migration;

/**
 * Class m230111_093745_alter_tbl_slot_booking_add_column_order_id
 */
class m230111_093745_alter_tbl_slot_booking_add_column_order_id extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%availability_slot_booking}}');
        if (! isset($table->columns['order_id'])) {
            $this->addColumn('{{%availability_slot_booking}}', 'order_id', $this->string(16)
                ->defaultValue(0)
                ->after('slot_id'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%availability_slot_booking}}');
        if (isset($table->columns['order_id'])) {
            $this->dropColumn('{{%availability_slot_booking}}', 'order_id');
        }
    }
}
