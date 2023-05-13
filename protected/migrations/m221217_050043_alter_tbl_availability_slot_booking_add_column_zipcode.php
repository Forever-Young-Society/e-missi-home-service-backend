<?php
use yii\db\Migration;

/**
 * Class m221217_050043_alter_tbl_availability_slot_booking_add_column_zipcode
 */
class m221217_050043_alter_tbl_availability_slot_booking_add_column_zipcode extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%availability_slot_booking}}');
        if (! isset($table->columns['workzone_id'])) {
            $this->addColumn('{{%availability_slot_booking}}', 'workzone_id', $this->integer(11)
                ->defaultValue(0)
                ->after('slot_id'));
        }
        if (! isset($table->columns['zipcode'])) {
            $this->addColumn('{{%availability_slot_booking}}', 'zipcode', $this->string(16)
                ->defaultValue(null)
                ->after('slot_id'));
        }
        if (! isset($table->columns['address'])) {
            $this->addColumn('{{%availability_slot_booking}}', 'address', $this->string(512)
                ->defaultValue(null)
                ->after('slot_id'));
        }
        if (! isset($table->columns['cancel_reason'])) {
            $this->addColumn('{{%availability_slot_booking}}', 'cancel_reason', $this->string(512)
                ->defaultValue(null)
                ->after('slot_id'));
        }
        if (! isset($table->columns['cancel_date'])) {
            $this->addColumn('{{%availability_slot_booking}}', 'cancel_date', $this->dateTime()
                ->defaultValue(null)
                ->after('slot_id'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%availability_slot_booking}}');
        if (isset($table->columns['workzone_id'])) {
            $this->dropColumn('{{%availability_slot_booking}}', 'workzone_id');
        }
        if (isset($table->columns['zipcode'])) {
            $this->dropColumn('{{%availability_slot_booking}}', 'zipcode');
        }
        if (isset($table->columns['address'])) {
            $this->dropColumn('{{%availability_slot_booking}}', 'address');
        }
        if (isset($table->columns['cancel_reason'])) {
            $this->dropColumn('{{%availability_slot_booking}}', 'cancel_reason');
        }
        if (isset($table->columns['cancel_date'])) {
            $this->dropColumn('{{%availability_slot_booking}}', 'cancel_date');
        }
    }
}
