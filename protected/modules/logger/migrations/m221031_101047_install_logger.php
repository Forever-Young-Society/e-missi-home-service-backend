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
class m221031_101047_install_logger extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%logger_log}}');
        if (! $table) {
            $sql = file_get_contents(__DIR__ . '/../db/install.sql');
            $this->execute($sql);
        }
    }

    public function safeDown()
    {
        echo "m221031_101047_install_logger migrating down by doing nothing....\n";
    }
}