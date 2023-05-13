-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------

-- --------------------------------------------
-- TABLE `tbl_contact_information`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_contact_information`;
CREATE TABLE IF NOT EXISTS `tbl_contact_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT  NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skype_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget_type_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT '0',
  `type_id` varchar(255) DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX(`full_name`),
  INDEX(`email`),
  INDEX(`created_on`),
 KEY `FK_contact_information_created_by` (`created_by_id`),
  CONSTRAINT `FK_contact_information_created_by` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_contact_address`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_contact_address`;
CREATE TABLE IF NOT EXISTS `tbl_contact_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(11) DEFAULT '0',
`image_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`title`),
  INDEX(`email`),
  KEY `fk_address_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_address_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------
-- TABLE `tbl_contact_phone`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_contact_phone`;
CREATE TABLE IF NOT EXISTS `tbl_contact_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_chat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skype_chat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gtalk_chat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  `state_id` int(11) NOT NULL,
  `whatsapp_enable` int(11) DEFAULT '0',
  `telegram_enable` int(11) DEFAULT '0',
  `toll_free_enable` int(11) DEFAULT '0',
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX(`title`),
  INDEX(`state_id`),
  KEY `FK_contact_phone_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_contact_phone_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_seo_chatscript`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_contact_chatscript`;
CREATE TABLE IF NOT EXISTS `tbl_contact_chatscript` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script_code` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_link` int(11) DEFAULT '1',
  `show_bubble` int(11) DEFAULT '1',
  `popup_delay` int(11) NOT NULL,
  `chat_server` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int(11) NOT NULL DEFAULT '1',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_contact_chatscript_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_contact_chatscript_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------

-- TABLE `tbl_contact_social_link`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_contact_social_link`;
CREATE TABLE IF NOT EXISTS `tbl_contact_social_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ext_url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int(11) NOT NULL DEFAULT '1',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
   INDEX(`title`),
   INDEX(`created_on`),
   INDEX(`state_id`),
  KEY `fk_contact_social_link_created_by_id` (`created_by_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- --------------------------------------------------------------------------------------
-- END BACKUP
-- -------------------------------------------
