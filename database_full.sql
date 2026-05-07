-- =============================================
-- Full Database Schema for Recharge System
-- =============================================

-- Table: transactions
-- Purpose: Store all recharge/withdrawal transactions
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

-- Table: invoices
-- Purpose: Store recharge invoices with payment tracking
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

-- =============================================
-- End of Schema
-- =============================================
