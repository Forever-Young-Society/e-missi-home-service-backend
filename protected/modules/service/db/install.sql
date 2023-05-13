-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------

-- -------------------------------------------

-- TABLE `tbl_service_category`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_service_category`;
CREATE TABLE IF NOT EXISTS `tbl_service_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int DEFAULT '1',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`title`),
  INDEX(`state_id`),
  INDEX(`created_on`),
  INDEX(`created_by_id`),
  KEY `fk_service_category_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_service_category_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_service_sub_category`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_service_sub_category`;
CREATE TABLE IF NOT EXISTS `tbl_service_sub_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `provider_price` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `category_id` int NOT NULL,
  `parent_id` int DEFAULT '0',
  `combination_count` int DEFAULT '1',
  `type_id` int DEFAULT '0',
  `service_type` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
   PRIMARY KEY (`id`),
   INDEX(`title`),
   INDEX(`state_id`),
   INDEX(`parent_id`),
   INDEX(`category_id`),
   INDEX(`created_on`),
   INDEX(`created_by_id`),
   KEY `fk_service_sub_category_created_by_id` (`created_by_id`),
   CONSTRAINT `fk_service_sub_category_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_service_skill`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_service_skill`;
CREATE TABLE IF NOT EXISTS `tbl_service_skill` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int NOT NULL,
  `parent_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
   PRIMARY KEY (`id`),
   INDEX(`title`),
   INDEX(`state_id`),
   INDEX(`category_id`),
   INDEX(`created_on`),
   INDEX(`created_by_id`),
   KEY `fk_service_skill_created_by_id` (`created_by_id`),
   CONSTRAINT `fk_service_skill_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------

-- TABLE `tbl_service_term`

-- --------------------------------------------
DROP TABLE IF EXISTS `tbl_service_term`;
CREATE TABLE IF NOT EXISTS `tbl_service_term` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
   INDEX(`state_id`),
   INDEX(`category_id`),
   INDEX(`created_on`),
   INDEX(`created_by_id`),
  KEY `FK_service_term_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_service_term_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------

-- TABLE `tbl_service_report`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_service_report`;
CREATE TABLE IF NOT EXISTS `tbl_service_report` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_provided` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int DEFAULT '0',
  `booking_id` int NOT NULL,
  `service_id` int DEFAULT '0',
  `user_id` int DEFAULT '0',
  `dependant_id` int DEFAULT '0',
  `state_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`user_id`),
  INDEX(`booking_id`),
  INDEX(`state_id`),
  INDEX(`created_on`),
  INDEX(`created_by_id`),
  KEY `fk_service_report_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_service_report_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- -- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- --------------------------------------------------------------------------------------
-- END BACKUP
-- -------------------------------------------
