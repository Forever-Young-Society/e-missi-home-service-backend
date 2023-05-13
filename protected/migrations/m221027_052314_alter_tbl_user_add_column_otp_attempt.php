<?php
use yii\db\Migration;

/**
 * Class m221027_052314_alter_tbl_user_add_column_otp_attempt
 */
class m221027_052314_alter_tbl_user_add_column_otp_attempt extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (! isset($table->columns['otp_attempt'])) {
            $this->addColumn('{{%user}}', 'otp_attempt', $this->string(32)
                ->defaultValue(0)
                ->after('otp_verified'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (isset($table->columns['otp_attempt'])) {
            $this->dropColumn('{{%user}}', 'otp_attempt');
        }
    }
}
