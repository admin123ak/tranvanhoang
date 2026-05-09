-- ============================================================
-- API Tokens Table Migration
-- Run this SQL to create the api_tokens table for auto key generation
-- ============================================================

CREATE TABLE IF NOT EXISTS `api_tokens` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `token` VARCHAR(64) NOT NULL UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `admin_account` VARCHAR(255) NOT NULL COMMENT 'Admin username to use for auto key generation',
    `status` TINYINT(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    INDEX `idx_token` (`token`),
    INDEX `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Add price configuration to packages table (if not exists)
-- ============================================================

ALTER TABLE `keys_code`
    ADD COLUMN IF NOT EXISTS `price_per_hour` DECIMAL(10,2) DEFAULT 1000.00 COMMENT 'Price per hour in VND' AFTER `max_devices`;

-- ============================================================
-- Optional: Insert a sample API token for testing
-- Replace 'admin123' with your actual admin username
-- Replace the token with a secure one you generate
-- ============================================================

-- INSERT INTO `api_tokens` (`user_id`, `token`, `name`, `admin_account`, `status`, `created_at`, `updated_at`)
-- VALUES (1, 'your_secure_token_here_64_chars', 'Test API Token', 'admin123', 1, NOW(), NOW());
