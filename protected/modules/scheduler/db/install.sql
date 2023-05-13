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
-- TABLE `tbl_scheduler_type`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_scheduler_type`;
CREATE TABLE IF NOT EXISTS `tbl_scheduler_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX(`title`),
  INDEX(`state_id`),
  KEY `FK_scheduler_category_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_scheduler_category_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_scheduler_cronjob`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_scheduler_cronjob`;
CREATE TABLE IF NOT EXISTS `tbl_scheduler_cronjob` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `when` varchar(32)  COLLATE utf8mb4_unicode_ci NOT NULL,
  `command` text COLLATE utf8mb4_unicode_ci,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `state_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
    INDEX(`title`),
  KEY `fk_scheduler_command_created_by_id` (`created_by_id`),

  CONSTRAINT `fk_scheduler_command_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
    KEY `fk_tbl_scheduler_type_id` (`type_id`),

  CONSTRAINT `fk_tbl_scheduler_type_id` FOREIGN KEY (`type_id`) REFERENCES `tbl_scheduler_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- TABLE `tbl_scheduler_log`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_scheduler_log`;
CREATE TABLE IF NOT EXISTS `tbl_scheduler_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `result` text COLLATE utf8mb4_unicode_ci,
  `cronjob_id` int(11) NOT NULL,
  `scheduled_on` datetime NOT NULL,
  `executed_on` datetime DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scheduler_log_cronjob_id` (`cronjob_id`),
  KEY `fk_scheduler_log_created_by_id` (`created_by_id`),

  CONSTRAINT `fk_scheduler_log_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  CONSTRAINT `fk_scheduler_log_cronjob_id` FOREIGN KEY (`cronjob_id`) REFERENCES `tbl_scheduler_cronjob` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;


