<?php
use yii\db\Migration;

/**
 * Class m221216_064618_alter_tbl_availability_slot_booking_add_column_transaction_id
 */
class m221216_064618_alter_tbl_availability_slot_booking_add_column_transaction_id extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%availability_slot_booking}}');
        if (! isset($table->columns['transaction_id'])) {
            $this->addColumn('{{%availability_slot_booking}}', 'transaction_id', $this->string(64)
                ->defaultValue(null)
                ->after('slot_id'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%availability_slot_booking}}');
        if (isset($table->columns['transaction_id'])) {
            $this->dropColumn('{{%availability_slot_booking}}', 'transaction_id');
        }
    }
}
