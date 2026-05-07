-- Add packages table for game package management
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

-- Add package_id column to keys_code table
ALTER TABLE `keys_code` ADD COLUMN `package_id` int DEFAULT NULL AFTER `game`;

-- Insert default PUBG package
INSERT INTO `packages` (`package_name`, `package_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
('PUBG Mobile', 'com.tencent.ig', 'PUBG Mobile Global', 1, NOW(), NOW());
