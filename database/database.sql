-- ============================================================
-- Full Database Setup for API Auto Key System
-- Run this SQL to create all required tables
-- ============================================================

-- -----------------------------------------------------------
-- 1. API Tokens Table
-- Lưu trữ API token để xác thực người dùng gọi API
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_tokens` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `token` VARCHAR(64) NOT NULL UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `admin_account` VARCHAR(255) NOT NULL COMMENT 'Admin username used for auto key generation',
    `status` TINYINT(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_token` (`token`),
    INDEX `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------------
-- 2. API Config Table
-- Cấu hình package, giá tiền, admin account cho getkey tự động
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_config` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `admin_account` VARCHAR(255) NOT NULL COMMENT 'Admin username used for key generation',
    `package_id` INT UNSIGNED NOT NULL COMMENT 'Package ID linked to packages table',
    `price_per_hour` DECIMAL(10,2) DEFAULT 1000.00 COMMENT 'Price per hour in VND',
    `min_hours` INT DEFAULT 1 COMMENT 'Minimum hours allowed',
    `max_hours` INT DEFAULT 8760 COMMENT 'Maximum hours allowed (1 year)',
    `max_devices` INT DEFAULT 5 COMMENT 'Maximum devices per key',
    `status` TINYINT(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_package` (`package_id`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------------
-- 3. Packages Table (if not exists)
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `packages` (
    `id_package` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `package_name` VARCHAR(255) NOT NULL,
    `package_id` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT,
    `status` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------------
-- 4. Users Table (if not exists - reference)
-- -----------------------------------------------------------
-- Bảng users đã tồn tại, chỉ cần đảm bảo có cột saldo và level

-- -----------------------------------------------------------
-- Sample Data (Optional - uncomment to insert)
-- -----------------------------------------------------------

-- Sample API Config:
-- INSERT INTO `api_config` (`admin_account`, `package_id`, `price_per_hour`, `min_hours`, `max_hours`, `max_devices`, `status`, `created_at`, `updated_at`)
-- VALUES ('admin123', 1, 1000.00, 1, 8760, 5, 1, NOW(), NOW());
