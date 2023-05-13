-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------
-- -------------------------------------------
-- TABLE `tbl_seo`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_seo`;
CREATE TABLE IF NOT EXISTS `tbl_seo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `data` varchar(255) DEFAULT NULL,
  `state_id` int(11) NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seo_idx_route` (`route`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- -------------------------------------------

-- TABLE `tbl_seo_analytics`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_seo_analytics`;
CREATE TABLE IF NOT EXISTS `tbl_seo_analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain_name` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_information` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `state_id` int(11) NOT NULL DEFAULT '1',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_seo_analytics_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_seo_analytics_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



DROP TABLE IF EXISTS `tbl_seo_redirect`;
CREATE TABLE IF NOT EXISTS `tbl_seo_redirect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_seo_redirect_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_seo_redirect_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_seo_log`

-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_seo_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referer_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(256) DEFAULT NULL,
  `current_url` varchar(512) DEFAULT NULL,
  `state_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `user_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view_count` int(11) DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
   PRIMARY KEY (`id`)
   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
