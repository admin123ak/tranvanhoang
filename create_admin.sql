-- ============================================
-- Full Database Setup for Panel
-- Admin Login: admin123 / admin123
-- ============================================

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
-- Table: keys_code
-- ============================================
CREATE TABLE IF NOT EXISTS `keys_code` (
  `id_keys` int NOT NULL AUTO_INCREMENT,
  `game` varchar(32) NOT NULL,
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
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(155) DEFAULT NULL,
  `level` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1',
  `saldo` int NOT NULL DEFAULT '0',
  `uplink` varchar(25) DEFAULT NULL,
  `user_ip` varchar(128) DEFAULT NULL,
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
-- ADMIN ACCOUNT (username: admin123, password: admin123)
-- ============================================
INSERT INTO `users` (`username`, `password`, `fullname`, `level`, `status`, `saldo`, `uplink`, `user_ip`, `created_at`, `updated_at`)
VALUES (
  'admin123',
  '$2b$08$i7C3yDDouWoURQVhoZ3OauU87C3Gg3sjkqgsUqiVyjGkJuBJ8RbrS',
  'Admin',
  1,
  1,
  99999,
  '',
  '',
  NOW(),
  NOW()
);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
