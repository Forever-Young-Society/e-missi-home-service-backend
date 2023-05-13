-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------

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
  INDEX(`state_id`),
  INDEX(`start_time`),
  INDEX(`end_time`),
  INDEX(`created_on`),
  INDEX(`created_by_id`),
  KEY `fk_availability_slot_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_availability_slot_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  INDEX(`state_id`),
  INDEX(`start_time`),
  INDEX(`end_time`),
  INDEX(`created_on`),
  INDEX(`created_by_id`),
  KEY `fk_availability_provider_slot_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_availability_provider_slot_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
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
  `slot_id` varchar(16) NOT NULL,/* multiple */
  `transaction_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_reschedule` int DEFAULT '0',
  `user_amount` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `provider_amount` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `admin_revenue` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `user_reschedule` int DEFAULT '0',
  `is_reschedule_confirm` int NULL DEFAULT '0',
  `payment_status` int DEFAULT '0',
  `old_start_time` datetime DEFAULT NULL,
  `old_end_time` datetime DEFAULT NULL,
  `state_id` int DEFAULT '0',
  `type_id` int DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by_id` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX(`provider_id`),
  INDEX(`dependant_id`),
  INDEX(`service_id`),
  INDEX(`state_id`),
  INDEX(`start_time`),
  INDEX(`end_time`),
  INDEX(`created_on`),
  INDEX(`created_by_id`),
  KEY `fk_availability_slot_booking_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_availability_slot_booking_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  INDEX(`state_id`),
  INDEX(`service_id`),
  INDEX(`booking_id`),
  INDEX(`created_on`),
  INDEX(`created_by_id`),
  KEY `fk_availability_booking_service_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_availability_booking_service_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ----------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- ----------------------------------------------
-- END BACKUP
-- ----------------------------------------------
