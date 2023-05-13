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
class m220521_160538_install_contact extends Migration
{

    public function safeUp()
    {
        $table = Yii::$app->db->getTableSchema('{{%contact_information}}');

        if (! $table) {
            $sql = file_get_contents(__DIR__ . '/../db/install.sql');
            $this->execute($sql);
        }
    }

    public function safeDown()
    {
        echo "m220521_160538_install_contact migrating down by doing nothing....\n";
    }
}