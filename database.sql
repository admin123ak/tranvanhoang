-- ============================================
-- Full Database Setup for Panel
-- Admin Login: admin123 / admin123
-- Import 1 file nay la chay du het
-- ============================================

-- Xóa bảng cũ nếu có (từ phiên bản API Token trước đó)
DROP TABLE IF EXISTS `api_tokens`;
DROP TABLE IF EXISTS `api_config`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================
-- Table: history
-- ============================================
CREATE TABLE IF NOT EXISTS `history` (
  `id_history` int NOT NULL AUTO_INCREMENT,
  `keys_id` varchar(33) DEFAULT NULL,
  `user_do` varchar(33) DEFAULT NULL,
  `info` mediumtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_history`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ============================================
-- Table: packages
-- ============================================
CREATE TABLE IF NOT EXISTS `packages` (
  `id_package` int NOT NULL AUTO_INCREMENT,
  `package_name` varchar(100) NOT NULL,
  `package_id` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_package`),
  UNIQUE KEY `package_id` (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ============================================
-- Table: keys_code
-- ============================================
CREATE TABLE IF NOT EXISTS `keys_code` (
  `id_keys` int NOT NULL AUTO_INCREMENT,
  `game` varchar(32) NOT NULL,
  `package_id` int DEFAULT NULL,
  `user_key` varchar(32) DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `expired_date` datetime DEFAULT NULL,
  `max_devices` int DEFAULT NULL,
  `devices` mediumtext,
  `status` tinyint(1) DEFAULT '1',
  `registrator` varchar(32) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_keys`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ============================================
-- Table: modname
-- ============================================
CREATE TABLE IF NOT EXISTS `modname` (
  `id` int NOT NULL AUTO_INCREMENT,
  `modname` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

INSERT INTO `modname` (`id`, `modname`) VALUES
(1, 'NOCASHRANDI');

-- ============================================
-- Table: onoff
-- ============================================
CREATE TABLE IF NOT EXISTS `onoff` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci NOT NULL,
  `myinput` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

INSERT INTO `onoff` (`id`, `status`, `myinput`) VALUES
(11, 'on', 'NOCASHRANDI');

-- ============================================
-- Table: referral_code
-- ============================================
CREATE TABLE IF NOT EXISTS `referral_code` (
  `id_reff` int NOT NULL AUTO_INCREMENT,
  `code` varchar(128) DEFAULT NULL,
  `set_saldo` int DEFAULT NULL,
  `used_by` varchar(66) DEFAULT NULL,
  `created_by` varchar(66) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_reff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ============================================
-- Table: users
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
  `id_users` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(155) DEFAULT NULL,
  `username` varchar(66) NOT NULL,
  `level` int DEFAULT '2',
  `saldo` int DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `uplink` varchar(66) DEFAULT NULL,
  `password` varchar(155) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_users`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ============================================
-- Table: _ftext
-- ============================================
CREATE TABLE IF NOT EXISTS `_ftext` (
  `id` int NOT NULL AUTO_INCREMENT,
  `_status` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci NOT NULL,
  `_ftext` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

INSERT INTO `_ftext` (`id`, `_status`, `_ftext`) VALUES
(1, 'on', 'NOCASHRANDI');

-- ============================================
-- ADMIN ACCOUNT
-- Username: admin123
-- Password: admin123
-- ============================================
INSERT INTO `users` (`fullname`, `username`, `level`, `saldo`, `status`, `uplink`, `password`, `created_at`, `updated_at`) VALUES
('Admin', 'admin123', 1, 99999, 1, NULL, CONCAT('$', '2b$', '08$', 'i7C3yDDouWoURQVhoZ3OauU87C3Gg3sjkqgsUqiVyjGkJuBJ8RbrS'), NOW(), NOW());

-- ============================================
-- Table: getkey_links (GetKey Link System)
-- Admin tạo link getkey cố định, người dùng vào link nhấn Get Key là xong
-- ============================================
CREATE TABLE IF NOT EXISTS `getkey_links` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_account` varchar(255) NOT NULL COMMENT 'Admin username dùng để tạo key',
  `package_id` int NOT NULL COMMENT 'Package ID liên kết với bảng packages',
  `slug` varchar(64) NOT NULL UNIQUE COMMENT 'URL slug duy nhất cho link getkey',
  `name` varchar(255) NOT NULL COMMENT 'Tên link getkey',
  `price_per_hour` decimal(10,2) DEFAULT 0.00 COMMENT 'Giá mỗi giờ (VND) - 0 = free',
  `max_hours` int DEFAULT 720 COMMENT 'Số giờ tối đa mỗi key',
  `max_devices` int DEFAULT 1 COMMENT 'Số thiết bị tối đa mỗi key',
  `youmoney_token` varchar(255) DEFAULT NULL COMMENT 'API Token YouMoney (tùy chọn)',
  `status` tinyint(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
  `total_keys_created` int DEFAULT 0 COMMENT 'Tổng số key đã tạo từ link này',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_package` (`package_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Table: transactions (Recharge System)
-- ============================================
CREATE TABLE IF NOT EXISTS `transactions` (
  `id_transaction` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `type` enum('IN','OUT') NOT NULL DEFAULT 'IN',
  `description` text,
  `transaction_date` datetime NOT NULL,
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaction`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `transaction_date` (`transaction_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: invoices (Recharge System)
-- ============================================
CREATE TABLE IF NOT EXISTS `invoices` (
  `id_invoice` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `invoice_code` varchar(50) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `status` enum('pending','completed','expired','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'MBBank',
  `expired_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_invoice`),
  UNIQUE KEY `invoice_code` (`invoice_code`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
