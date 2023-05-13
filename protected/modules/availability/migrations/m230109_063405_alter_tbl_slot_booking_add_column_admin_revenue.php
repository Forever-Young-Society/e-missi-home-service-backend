<?php
use yii\db\Migration;

/**
 * Class m230109_063405_alter_tbl_slot_booking_add_column_admin_revenue
 */
class m230109_063405_alter_tbl_slot_booking_add_column_admin_revenue extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%availability_slot_booking}}');
        if (! isset($table->columns['admin_revenue'])) {
            $this->addColumn('{{%availability_slot_booking}}', 'admin_revenue', $this->string(16)
                ->defaultValue(0)
                ->after('slot_id'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%availability_slot_booking}}');
        if (isset($table->columns['admin_revenue'])) {
            $this->dropColumn('{{%availability_slot_booking}}', 'admin_revenue');
        }
    }
}
