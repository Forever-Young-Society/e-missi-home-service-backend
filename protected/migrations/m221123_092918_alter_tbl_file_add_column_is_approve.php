<?php
use yii\db\Migration;

/**
 * Class m221123_092918_alter_tbl_file_add_column_is_approve
 */
class m221123_092918_alter_tbl_file_add_column_is_approve extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%file}}');
        if (! isset($table->columns['is_approve'])) {
            $this->addColumn('{{%file}}', 'is_approve', $this->integer(11)
                ->defaultValue(0)
                ->after('model_type'));
        }
        if (! isset($table->columns['reason'])) {
            $this->addColumn('{{%file}}', 'reason', $this->string(512)
                ->defaultValue(null)
                ->after('model_type'));
        }
    }

    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%file}}');
        if (isset($table->columns['is_approve'])) {
            $this->dropColumn('{{%file}}', 'is_approve');
        }
        if (isset($table->columns['reason'])) {
            $this->dropColumn('{{%file}}', 'reason');
        }
    }
}
