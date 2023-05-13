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
 * php console.php module/migrate
 */
class m221022_131040_add_column_role_id_to_tbl_seo_analytics extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%seo_analytics}}');
        if (! isset($table->columns['role_id'])) {
            $this->addColumn('{{%seo_analytics}}', 'role_id', $this->integer(11)
                ->defaultValue(1));
        }
    }

    public function safeDown()
    {
        echo "m221022_131040_add_column_role_id_to_tbl_seo_analytics migrating down by doing nothing....\n";
    }
}