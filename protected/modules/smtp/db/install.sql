-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------
-- START BACKUP
-- -------------------------------------------

-- -----------------------------------------------------
-- TABLE `tbl_smtp_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tbl_smtp_account`;
CREATE TABLE IF NOT EXISTS `tbl_smtp_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `server` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` int(11) NOT NULL DEFAULT '25',
  `encryption_type` int(11) NOT NULL DEFAULT '0',
  `limit_per_email` int(255) DEFAULT NULL,
  `state_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX(`title`),
  INDEX(`email`),
  KEY `id` (`id`),
  KEY `fk_smtp_account_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_smtp_account_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_smtp_email_queue`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_smtp_email_queue`;
CREATE TABLE IF NOT EXISTS `tbl_smtp_email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cc` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bcc` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `type_id` int(11) DEFAULT NULL,
  `state_id` int(11) NOT NULL DEFAULT '1',
  `attempts` int(11) DEFAULT NULL,
  `sent_on` datetime DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `model_type` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_account_id` int(11) DEFAULT NULL,
  `message_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `re_message_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX(`from`),
  INDEX(`to`),
  INDEX(`state_id`),
  INDEX(`model_type`),
  INDEX(`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- TABLE `tbl_smtp_unsubscribe`
-- --------------------------------------------------------------
DROP TABLE IF EXISTS `tbl_smtp_unsubscribe`;
CREATE TABLE IF NOT EXISTS `tbl_smtp_unsubscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX(`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
