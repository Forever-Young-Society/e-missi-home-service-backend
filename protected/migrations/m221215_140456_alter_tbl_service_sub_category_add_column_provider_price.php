<?php
use yii\db\Migration;

/**
 * Class m221215_140456_alter_tbl_service_sub_category_add_column_provider_price
 */
class m221215_140456_alter_tbl_service_sub_category_add_column_provider_price extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%service_sub_category}}');
        if (! isset($table->columns['provider_price'])) {
            $this->addColumn('{{%service_sub_category}}', 'provider_price', $this->string(16)
                ->defaultValue(0)
                ->after('title'));
        }
        if (! isset($table->columns['price'])) {
            $this->addColumn('{{%service_sub_category}}', 'price', $this->string(16)
                ->defaultValue(0)
                ->after('title'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%service_sub_category}}');
        if (isset($table->columns['provider_price'])) {
            $this->dropColumn('{{%service_sub_category}}', 'provider_price');
        }
        if (isset($table->columns['price'])) {
            $this->dropColumn('{{%service_sub_category}}', 'price');
        }
    }
}
