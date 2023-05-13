-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------

-- -------------------------------------------

-- TABLE `tbl_workzone_zone`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_workzone_zone`;
CREATE TABLE IF NOT EXISTS `tbl_workzone_zone` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
   PRIMARY KEY (`id`),
   INDEX(`title`),
   INDEX(`state_id`),
   INDEX(`created_on`),
   INDEX(`created_by_id`),
   KEY `fk_workzone_zone_created_by_id` (`created_by_id`),
   CONSTRAINT `fk_workzone_zone_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------

-- TABLE `tbl_workzone_location`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_workzone_location`;
CREATE TABLE IF NOT EXISTS `tbl_workzone_location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_location` int NOT NULL,
  `secondary_location` int DEFAULT '0',
  `second_secondary_location` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
   PRIMARY KEY (`id`),
   INDEX(`title`),
   INDEX(`state_id`),
   INDEX(`created_on`),
   INDEX(`created_by_id`),
   KEY `fk_workzone_location_created_by_id` (`created_by_id`),
   CONSTRAINT `fk_workzone_location_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------

-- TABLE `tbl_workzone_postcode`

-- --------------------------------------------
DROP TABLE IF EXISTS `tbl_workzone_postcode`;
CREATE TABLE IF NOT EXISTS `tbl_workzone_postcode` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,  
  `created_by_id` int NOT NULL,
   PRIMARY KEY (`id`),
   INDEX(`state_id`),
   INDEX(`location_id`),
   INDEX(`created_on`),
   INDEX(`created_by_id`),
  KEY `FK_workzone_postcode_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_workzone_postcode_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- --------------------------------------------------------------------------------------
-- END BACKUP
-- -------------------------------------------
