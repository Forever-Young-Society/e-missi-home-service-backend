-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

-- -------------------------------------------
-- TABLE `tbl_sms_gateway`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sms_gateway`;
CREATE TABLE IF NOT EXISTS `tbl_sms_gateway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `value` longtext DEFAULT null,
  `mode` TINYINT DEFAULT '0',
  `state_id` int(11) DEFAULT '0',
  `type_id` int(11) DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sms_gateway_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_sms_gateway_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_sms_history`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sms_history`;
CREATE TABLE IF NOT EXISTS `tbl_sms_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `gateway_id` int(11) NOT NULL,
  `sms_detail` text DEFAULT NULL,
  `state_id` int(11) DEFAULT '0',
  `type_id` int(11) DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
