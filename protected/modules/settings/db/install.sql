-- -------------------------------------------

SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

SET AUTOCOMMIT=0;
START TRANSACTION;
-- -------------------------------------------
-- -------------------------------------------
-- START BACKUP
-- -------------------------------------------

-- -------------------------------------------

-- -------------------------------------------
-- TABLE `tbl_setting`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_settings_variable`;
CREATE TABLE IF NOT EXISTS `tbl_settings_variable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(64) NOT NULL,
  `module` varchar(64) DEFAULT '*',
  `value` text DEFAULT NULL,
  `type_id` varchar(255) DEFAULT NULL,
  `state_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
 `updated_on` datetime DEFAULT NULL,

   PRIMARY KEY (`id`),
  INDEX(`key`),
  INDEX(`created_by_id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------

COMMIT;
-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
