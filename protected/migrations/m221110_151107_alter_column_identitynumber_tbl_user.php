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
class m221110_151107_alter_column_identitynumber_tbl_user extends Migration
{

    public function safeUp()
    {
        $this->execute("ALTER TABLE `tbl_user` CHANGE `Identity_number` `identity_number` VARCHAR(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
    }

    public function safeDown()
    {
        echo "m221110_151107_alter_column_identitynumber_tbl_user migrating down by doing nothing....\n";
    }
}