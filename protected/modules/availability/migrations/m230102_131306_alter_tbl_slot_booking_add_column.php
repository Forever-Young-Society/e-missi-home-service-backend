<?php
use yii\db\Migration;

/**
 * Class m230102_131306_alter_tbl_slot_booking_add_column
 */
class m230102_131306_alter_tbl_slot_booking_add_column extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%availability_slot_booking}}');
        if (! isset($table->columns['user_amount'])) {
            $this->addColumn('{{%availability_slot_booking}}', 'user_amount', $this->string(16)
                ->defaultValue(0)
                ->after('slot_id'));
        }
        if (! isset($table->columns['provider_amount'])) {
            $this->addColumn('{{%availability_slot_booking}}', 'provider_amount', $this->string(16)
                ->defaultValue(0)
                ->after('slot_id'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%availability_slot_booking}}');
        if (isset($table->columns['user_amount'])) {
            $this->dropColumn('{{%availability_slot_booking}}', 'user_amount');
        }
        if (isset($table->columns['provider_amount'])) {
            $this->dropColumn('{{%availability_slot_booking}}', 'provider_amount');
        }
    }
}
