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

-- TABLE `tbl_api_access_token`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_api_access_token`;
CREATE TABLE IF NOT EXISTS `tbl_api_access_token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `access_token` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_token` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_api_access_token_create_user` (`created_by_id`),
  CONSTRAINT `tbl_api_access_token_create_user` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_availability_booking_service`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_availability_booking_service`;
CREATE TABLE IF NOT EXISTS `tbl_availability_booking_service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` int NOT NULL,
  `service_id` int NOT NULL,
  `state_id` int DEFAULT '1',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `service_id` (`service_id`),
  KEY `booking_id` (`booking_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_availability_booking_service_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_availability_booking_service_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_availability_provider_slot`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_availability_provider_slot`;
CREATE TABLE IF NOT EXISTS `tbl_availability_provider_slot` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `availability_slot_id` int DEFAULT '0',
  `state_id` int NOT NULL DEFAULT '1',
  `type_id` int NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_availability_provider_slot_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_availability_provider_slot_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_availability_slot`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_availability_slot`;
CREATE TABLE IF NOT EXISTS `tbl_availability_slot` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `slot_gap_time` int DEFAULT NULL,
  `state_id` int NOT NULL DEFAULT '1',
  `type_id` int NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_availability_slot_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_availability_slot_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_availability_slot_booking`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_availability_slot_booking`;
CREATE TABLE IF NOT EXISTS `tbl_availability_slot_booking` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `provider_id` int NOT NULL,
  `service_id` int DEFAULT '0',
  `dependant_id` int DEFAULT '0',
  `workzone_id` int DEFAULT '0',
  `order_id` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_reason` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_date` datetime DEFAULT NULL,
  `slot_id` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `provider_reschedule` int DEFAULT '0',
  `user_amount` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `provider_amount` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `admin_revenue` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `user_reschedule` int DEFAULT '0',
  `is_reschedule_confirm` int DEFAULT '0',
  `payment_status` int DEFAULT '0',
  `old_start_time` datetime DEFAULT NULL,
  `old_end_time` datetime DEFAULT NULL,
  `state_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `provider_id` (`provider_id`),
  KEY `dependant_id` (`dependant_id`),
  KEY `service_id` (`service_id`),
  KEY `state_id` (`state_id`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_availability_slot_booking_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_availability_slot_booking_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_comment`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_comment`;
CREATE TABLE IF NOT EXISTS `tbl_comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `model_id` int NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `state_id` int DEFAULT '1',
  `type_id` int DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `model_type` (`model_type`),
  KEY `model_id` (`model_id`),
  KEY `fk_comment_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_comment_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_contact_address`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_contact_address`;
CREATE TABLE IF NOT EXISTS `tbl_contact_address` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int DEFAULT '0',
  `image_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `email` (`email`),
  KEY `fk_address_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_address_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_contact_chatscript`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_contact_chatscript`;
CREATE TABLE IF NOT EXISTS `tbl_contact_chatscript` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script_code` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_link` int DEFAULT '1',
  `show_bubble` int DEFAULT '1',
  `popup_delay` int NOT NULL,
  `chat_server` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int NOT NULL DEFAULT '1',
  `type_id` int NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  `role_id` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_contact_chatscript_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_contact_chatscript_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_contact_information`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_contact_information`;
CREATE TABLE IF NOT EXISTS `tbl_contact_information` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skype_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget_type_id` int DEFAULT NULL,
  `state_id` int DEFAULT '0',
  `type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `full_name` (`full_name`),
  KEY `email` (`email`),
  KEY `created_on` (`created_on`),
  KEY `FK_contact_information_created_by` (`created_by_id`),
  CONSTRAINT `FK_contact_information_created_by` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_contact_phone`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_contact_phone`;
CREATE TABLE IF NOT EXISTS `tbl_contact_phone` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_chat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `skype_chat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gtalk_chat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int NOT NULL DEFAULT '0',
  `state_id` int NOT NULL,
  `whatsapp_enable` int DEFAULT '0',
  `telegram_enable` int DEFAULT '0',
  `toll_free_enable` int DEFAULT '0',
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_contact_phone_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_contact_phone_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_contact_social_link`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_contact_social_link`;
CREATE TABLE IF NOT EXISTS `tbl_contact_social_link` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ext_url` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int NOT NULL DEFAULT '1',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  `type_id` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `created_on` (`created_on`),
  KEY `state_id` (`state_id`),
  KEY `fk_contact_social_link_created_by_id` (`created_by_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_earning`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_earning`;
CREATE TABLE IF NOT EXISTS `tbl_earning` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `provider_id` int DEFAULT '0',
  `provider_amount` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `booking_amount` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `admin_amount` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `state_id` int DEFAULT '1',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_earning_created_by` (`created_by_id`),
  CONSTRAINT `fk_earning_created_by` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_faq`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_faq`;
CREATE TABLE IF NOT EXISTS `tbl_faq` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int DEFAULT NULL,
  `type_id` int DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `created_on` (`created_on`),
  KEY `fk_faq_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_faq_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_feed`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_feed`;
CREATE TABLE IF NOT EXISTS `tbl_feed` (
  `id` int NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int NOT NULL DEFAULT '0',
  `type_id` int NOT NULL DEFAULT '0',
  `model_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `model_type` (`model_type`),
  KEY `model_id` (`model_id`),
  KEY `user_ip` (`user_ip`),
  KEY `state_id` (`state_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_feed_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_feed_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_file`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_file`;
CREATE TABLE IF NOT EXISTS `tbl_file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` int NOT NULL,
  `file_type` int DEFAULT '0',
  `is_approve` int DEFAULT '0',
  `type_id` int NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `model_type` (`model_type`),
  KEY `model_id` (`model_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_file_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_file_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_import_file`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_import_file`;
CREATE TABLE IF NOT EXISTS `tbl_import_file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int DEFAULT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int DEFAULT '0',
  `percentage` int DEFAULT '0',
  `failure_reason` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_count` int DEFAULT '0',
  `download_count` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `model_type` (`model_type`),
  KEY `model_id` (`model_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_import_file_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_import_file_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_language`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_language`;
CREATE TABLE IF NOT EXISTS `tbl_language` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_language_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_language_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_logger_log`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_logger_log`;
CREATE TABLE IF NOT EXISTS `tbl_logger_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `error` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `state_id` int NOT NULL DEFAULT '1',
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int NOT NULL,
  `referer_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_login_history`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_login_history`;
CREATE TABLE IF NOT EXISTS `tbl_login_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `user_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failer_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int NOT NULL,
  `type_id` int NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_ip` (`user_ip`),
  KEY `created_on` (`created_on`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_migration`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_migration`;
CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_notification`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_notification`;
CREATE TABLE IF NOT EXISTS `tbl_notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `model_id` int NOT NULL,
  `model_type` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint DEFAULT '0',
  `state_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `to_user_id` int DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_page`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_page`;
CREATE TABLE IF NOT EXISTS `tbl_page` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int DEFAULT '1',
  `application_type` int DEFAULT NULL,
  `type_id` int DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `type_id` (`type_id`),
  KEY `application_type` (`application_type`),
  KEY `fk_page_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_page_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_payment_response`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_payment_response`;
CREATE TABLE IF NOT EXISTS `tbl_payment_response` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `FK_payment_response_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_payment_response_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_rating`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_rating`;
CREATE TABLE IF NOT EXISTS `tbl_rating` (
  `id` int NOT NULL AUTO_INCREMENT,
  `model_id` int NOT NULL,
  `model_type` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` int NOT NULL,
  `rating` double NOT NULL,
  `title` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `state_id` int DEFAULT '1',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_rating_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_rating_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_sample_file`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sample_file`;
CREATE TABLE IF NOT EXISTS `tbl_sample_file` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` int DEFAULT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int DEFAULT NULL,
  `type_id` int NOT NULL DEFAULT '0',
  `state_id` int NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `model_type` (`model_type`),
  KEY `model_id` (`model_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_sample_file_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_sample_file_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_scheduler_cronjob`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_scheduler_cronjob`;
CREATE TABLE IF NOT EXISTS `tbl_scheduler_cronjob` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `when` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `command` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type_id` int NOT NULL DEFAULT '0',
  `state_id` int NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `fk_scheduler_command_created_by_id` (`created_by_id`),
  KEY `fk_tbl_scheduler_type_id` (`type_id`),
  CONSTRAINT `fk_scheduler_command_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  CONSTRAINT `fk_tbl_scheduler_type_id` FOREIGN KEY (`type_id`) REFERENCES `tbl_scheduler_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_scheduler_log`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_scheduler_log`;
CREATE TABLE IF NOT EXISTS `tbl_scheduler_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `state_id` int NOT NULL DEFAULT '0',
  `type_id` int NOT NULL DEFAULT '0',
  `result` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cronjob_id` int NOT NULL,
  `scheduled_on` datetime NOT NULL,
  `executed_on` datetime DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_scheduler_log_cronjob_id` (`cronjob_id`),
  KEY `fk_scheduler_log_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_scheduler_log_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  CONSTRAINT `fk_scheduler_log_cronjob_id` FOREIGN KEY (`cronjob_id`) REFERENCES `tbl_scheduler_cronjob` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_scheduler_type`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_scheduler_type`;
CREATE TABLE IF NOT EXISTS `tbl_scheduler_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int NOT NULL DEFAULT '0',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_scheduler_category_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_scheduler_category_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_seo`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_seo`;
CREATE TABLE IF NOT EXISTS `tbl_seo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int NOT NULL DEFAULT '1',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seo_idx_route` (`route`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_seo_analytics`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_seo_analytics`;
CREATE TABLE IF NOT EXISTS `tbl_seo_analytics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `account` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain_name` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_information` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int NOT NULL DEFAULT '1',
  `state_id` int NOT NULL DEFAULT '1',
  `type_id` int NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_seo_analytics_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_seo_analytics_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_seo_log`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_seo_log`;
CREATE TABLE IF NOT EXISTS `tbl_seo_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `referer_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_url` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int NOT NULL DEFAULT '0',
  `type_id` int DEFAULT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `user_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view_count` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_seo_redirect`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_seo_redirect`;
CREATE TABLE IF NOT EXISTS `tbl_seo_redirect` (
  `id` int NOT NULL AUTO_INCREMENT,
  `old_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int NOT NULL DEFAULT '0',
  `type_id` int NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_seo_redirect_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_seo_redirect_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_service`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_service`;
CREATE TABLE IF NOT EXISTS `tbl_service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int DEFAULT '0',
  `sub_category_id` int DEFAULT '0',
  `service_type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `description` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `duration` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `FK_service_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_service_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_service_category`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_service_category`;
CREATE TABLE IF NOT EXISTS `tbl_service_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int DEFAULT '1',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_service_category_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_service_category_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `service_provided` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
  KEY `user_id` (`user_id`),
  KEY `booking_id` (`booking_id`),
  KEY `state_id` (`state_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_service_report_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_service_report_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_service_skill`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_service_skill`;
CREATE TABLE IF NOT EXISTS `tbl_service_skill` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int NOT NULL,
  `parent_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `category_id` (`category_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_service_skill_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_service_skill_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_service_sub_category`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_service_sub_category`;
CREATE TABLE IF NOT EXISTS `tbl_service_sub_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `provider_price` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `category_id` int NOT NULL,
  `parent_id` int DEFAULT '0',
  `combination_count` int DEFAULT '1',
  `type_id` int DEFAULT '0',
  `service_type` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `parent_id` (`parent_id`),
  KEY `category_id` (`category_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_service_sub_category_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_service_sub_category_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_service_term`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_service_term`;
CREATE TABLE IF NOT EXISTS `tbl_service_term` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `category_id` (`category_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `FK_service_term_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_service_term_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_settings_variable`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_settings_variable`;
CREATE TABLE IF NOT EXISTS `tbl_settings_variable` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT '*',
  `value` text COLLATE utf8mb4_unicode_ci,
  `type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `key` (`key`),
  KEY `created_by_id` (`created_by_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_sitemap_item`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sitemap_item`;
CREATE TABLE IF NOT EXISTS `tbl_sitemap_item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority_id` int NOT NULL DEFAULT '0',
  `change_frequency_id` int NOT NULL DEFAULT '0',
  `model_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int NOT NULL,
  `type_id` int NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location` (`location`),
  KEY `state_id` (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_skill`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_skill`;
CREATE TABLE IF NOT EXISTS `tbl_skill` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_skill_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_skill_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_slider`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_slider`;
CREATE TABLE IF NOT EXISTS `tbl_slider` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_slider_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_slider_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_sms_gateway`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sms_gateway`;
CREATE TABLE IF NOT EXISTS `tbl_sms_gateway` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `mode` tinyint DEFAULT '0',
  `state_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sms_gateway_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_sms_gateway_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_sms_history`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_sms_history`;
CREATE TABLE IF NOT EXISTS `tbl_sms_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int DEFAULT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_id` int NOT NULL,
  `sms_detail` text COLLATE utf8mb4_unicode_ci,
  `state_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_smtp_account`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_smtp_account`;
CREATE TABLE IF NOT EXISTS `tbl_smtp_account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `server` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` int NOT NULL DEFAULT '25',
  `encryption_type` int NOT NULL DEFAULT '0',
  `limit_per_email` int DEFAULT NULL,
  `state_id` int NOT NULL DEFAULT '0',
  `type_id` int NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `email` (`email`),
  KEY `id` (`id`),
  KEY `fk_smtp_account_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_smtp_account_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_smtp_email_queue`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_smtp_email_queue`;
CREATE TABLE IF NOT EXISTS `tbl_smtp_email_queue` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cc` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bcc` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type_id` int DEFAULT NULL,
  `state_id` int NOT NULL DEFAULT '1',
  `attempts` int DEFAULT NULL,
  `sent_on` datetime DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `model_id` int DEFAULT NULL,
  `model_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_account_id` int DEFAULT NULL,
  `message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `re_message_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `from` (`from`),
  KEY `to` (`to`),
  KEY `state_id` (`state_id`),
  KEY `model_type` (`model_type`),
  KEY `model_id` (`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_smtp_unsubscribe`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_smtp_unsubscribe`;
CREATE TABLE IF NOT EXISTS `tbl_smtp_unsubscribe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` int NOT NULL DEFAULT '0',
  `type_id` int NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_user`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` int DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` int DEFAULT '0',
  `about_me` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `identity_number` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` int DEFAULT '0',
  `qualification` int DEFAULT '0',
  `contact_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int DEFAULT '0',
  `address` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_type` int DEFAULT '0',
  `step` int DEFAULT '0',
  `otp` int DEFAULT NULL,
  `otp_verified` int DEFAULT '0',
  `otp_attempt` int DEFAULT '1',
  `is_approve` int DEFAULT '0',
  `tos` int DEFAULT NULL,
  `role_id` int NOT NULL,
  `is_notify` int DEFAULT '0',
  `state_id` int NOT NULL,
  `type_id` int DEFAULT '0',
  `last_visit_time` datetime DEFAULT NULL,
  `last_action_time` datetime DEFAULT NULL,
  `last_password_change` datetime DEFAULT NULL,
  `login_error_count` int DEFAULT NULL,
  `activation_key` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `push_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `email_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `avg_rating` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `full_name` (`full_name`),
  KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `state_id` (`state_id`),
  KEY `created_on` (`created_on`),
  KEY `email_verified` (`email_verified`),
  KEY `updated_on` (`updated_on`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_user_category`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_user_category`;
CREATE TABLE IF NOT EXISTS `tbl_user_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_user_category_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_user_category_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_user_language`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_user_language`;
CREATE TABLE IF NOT EXISTS `tbl_user_language` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_user_language_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_user_language_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_user_skill`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_user_skill`;
CREATE TABLE IF NOT EXISTS `tbl_user_skill` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int NOT NULL,
  `skill_id` int NOT NULL,
  `parent_skill_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `fk_user_skill_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_user_skill_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_user_subcategory`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_user_subcategory`;
CREATE TABLE IF NOT EXISTS `tbl_user_subcategory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT '0',
  `sub_category_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_user_subcategory_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_user_subcategory_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_user_term`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_user_term`;
CREATE TABLE IF NOT EXISTS `tbl_user_term` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `term_id` int DEFAULT '0',
  `category_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `type_id` int DEFAULT '0',
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_user_term_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_user_term_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_user_workzone`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_user_workzone`;
CREATE TABLE IF NOT EXISTS `tbl_user_workzone` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `workzone_id` int DEFAULT '0',
  `latitude` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_user_workzone_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_user_workzone_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_work_zone`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_work_zone`;
CREATE TABLE IF NOT EXISTS `tbl_work_zone` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `latitude` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `FK_work_zone_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_work_zone_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_workzone_location`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_workzone_location`;
CREATE TABLE IF NOT EXISTS `tbl_workzone_location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `primary_location` int NOT NULL,
  `secondary_location` int DEFAULT '0',
  `second_secondary_location` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_workzone_location_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_workzone_location_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_workzone_postcode`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_workzone_postcode`;
CREATE TABLE IF NOT EXISTS `tbl_workzone_postcode` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`),
  KEY `location_id` (`location_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `FK_workzone_postcode_created_by_id` (`created_by_id`),
  CONSTRAINT `FK_workzone_postcode_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------

-- TABLE `tbl_workzone_zone`

-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_workzone_zone`;
CREATE TABLE IF NOT EXISTS `tbl_workzone_zone` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` int DEFAULT '0',
  `state_id` int DEFAULT '1',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `state_id` (`state_id`),
  KEY `created_on` (`created_on`),
  KEY `created_by_id` (`created_by_id`),
  KEY `fk_workzone_zone_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_workzone_zone_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


COMMIT;
-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
 -- -------AutobackUpStart------ -- -------------------------------------------

-- -------------------------------------------

-- END BACKUP

-- -------------------------------------------
