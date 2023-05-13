<?php
 /**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
 
use yii\db\Migration;
/**
 *   php console.php module/migrate 
 */
class m221110_121151_add_column_is_notify_in_table_user extends Migration{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        if (! isset($table->columns['is_notify'])) {
            $this->addColumn('{{%user}}', 'is_notify', $this->integer(11)
                ->defaultValue(0)
                ->after('role_id'));
        }
        
    }
    
    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%user}}');
        
        if (isset($table->columns['is_notify'])) {
            $this->dropColumn('{{%user}}', 'is_notify');
        }
        
    }
}