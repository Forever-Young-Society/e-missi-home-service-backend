<?php
use yii\db\Migration;

/**
 * Class m221116_133024_alter_tbl_user_add_column_age
 */
class m221116_133024_alter_tbl_user_add_column_age extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (! isset($table->columns['age'])) {
            $this->addColumn('{{%user}}', 'age', $this->integer(11)
                ->defaultValue(0)
                ->after('otp_verified'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (isset($table->columns['age'])) {
            $this->dropColumn('{{%user}}', 'age');
        }
    }
}
