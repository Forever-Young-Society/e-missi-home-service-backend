-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

-- -------------------------------------------
-- TABLE `tbl_api_device_detail`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_api_access_token`;
CREATE TABLE IF NOT EXISTS `tbl_api_access_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_token` varchar(256) NOT NULL,
  `device_token` varchar(256) NOT NULL,
  `device_name` varchar(256) DEFAULT NULL,
  `device_type` varchar(256) NOT NULL,
  `type_id` int(11) DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_api_access_token_create_user` (`created_by_id`),
  CONSTRAINT `tbl_api_access_token_create_user` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
