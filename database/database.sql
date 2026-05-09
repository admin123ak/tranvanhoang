-- ============================================================
-- Full Database Setup for GetKey Auto System
-- Run this SQL to create all required tables
-- ============================================================

-- -----------------------------------------------------------
-- 1. GetKey Links Table
-- Admin tạo link getkey cố định, người dùng vào link nhấn Get Key là xong
-- -----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `getkey_links` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `admin_account` VARCHAR(255) NOT NULL COMMENT 'Admin username dùng để tạo key',
    `package_id` INT UNSIGNED NOT NULL COMMENT 'Package ID liên kết với bảng packages',
    `slug` VARCHAR(64) NOT NULL UNIQUE COMMENT 'URL slug duy nhất cho link getkey',
    `name` VARCHAR(255) NOT NULL COMMENT 'Tên link getkey',
    `price_per_hour` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Giá mỗi giờ (VND) - 0 = free',
    `max_hours` INT DEFAULT 720 COMMENT 'Số giờ tối đa mỗi key',
    `max_devices` INT DEFAULT 1 COMMENT 'Số thiết bị tối đa mỗi key',
    `youmoney_token` VARCHAR(255) DEFAULT NULL COMMENT 'API Token YouMoney (tùy chọn)',
    `status` TINYINT(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
    `total_keys_created` INT DEFAULT 0 COMMENT 'Tổng số key đã tạo từ link này',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_slug` (`slug`),
    INDEX `idx_package` (`package_id`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- -----------------------------------------------------------
-- 2. Packages Table (nếu chưa có)
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
-- Sample Data (Optional)
-- -----------------------------------------------------------

-- Sample GetKey Link:
-- INSERT INTO `getkey_links` (`admin_account`, `package_id`, `slug`, `name`, `price_per_hour`, `max_hours`, `max_devices`, `status`, `created_at`, `updated_at`)
-- VALUES ('admin123', 1, 'free-key-pubg', 'Free PUBG Key', 0, 720, 1, 1, NOW(), NOW());
