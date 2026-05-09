-- ============================================
-- Create missing tables for Bank Accounts and Key Pricing
-- Import this in phpMyAdmin or run manually
-- ============================================

CREATE TABLE IF NOT EXISTS `bank_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `api_token` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `key_pricing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `duration_hours` int(11) NOT NULL,
  `price` bigint(20) NOT NULL DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `duration_hours` (`duration_hours`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data for Key Pricing (optional)
INSERT IGNORE INTO `key_pricing` (`duration_hours`, `price`, `description`) VALUES
(24, 10000, '1 ngày'),
(168, 50000, '7 ngày'),
(720, 150000, '30 ngày');

-- Insert sample Bank Account (optional)
INSERT IGNORE INTO `bank_accounts` (`bank_name`, `account_number`, `account_name`, `api_token`) VALUES
('MBBank', '0868641019', 'TRẦN VĂN HOÀNG', 'MB_FREE_021FA4D804026B08');
