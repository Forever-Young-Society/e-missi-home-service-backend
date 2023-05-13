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
-- -------------------------------------------

-- TABLE `tbl_sitemap_item`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sitemap_item`;
CREATE TABLE IF NOT EXISTS `tbl_sitemap_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority_id` int(11) NOT NULL DEFAULT 0,
  `change_frequency_id` int(11) NOT NULL DEFAULT 0,
  `model_type` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX(`location`),
  INDEX(`state_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------