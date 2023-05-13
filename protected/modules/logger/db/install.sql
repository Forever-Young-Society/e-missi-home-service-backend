SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
-- -------------------------------------------

-- -------------------------------------------

-- START BACKUP

-- -------------------------------------------

-- -------------------------------------------

-- TABLE `tbl_logger_log`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_logger_log`;
CREATE TABLE IF NOT EXISTS `tbl_logger_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `error` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int(11) NOT NULL DEFAULT 1,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int(11) NOT NULL,
  `referer_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


COMMIT;
-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
 -- -------AutobackUpStart------ -- -------------------------------------------

-- -------------------------------------------

-- END BACKUP

-- -------------------------------------------
