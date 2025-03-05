-- 會員整合表
CREATE TABLE IF NOT EXISTS `{prefix}vel_integrated_members` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `main_user_id` bigint(20) NOT NULL,
    `site_id` bigint(20) NOT NULL,
    `local_user_id` bigint(20) NOT NULL,
    `status` varchar(20) NOT NULL DEFAULT 'active',
    PRIMARY KEY (`id`),
    KEY `main_user_id` (`main_user_id`),
    KEY `site_id` (`site_id`)
) {charset_collate};

-- 寄放點設定表
CREATE TABLE IF NOT EXISTS `{prefix}vel_storage_settings` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `site_id` bigint(20) NOT NULL,
    `location_name` varchar(255) NOT NULL,
    `address` text NOT NULL,
    `is_public` tinyint(1) NOT NULL DEFAULT '0',
    `storage_enabled` tinyint(1) NOT NULL DEFAULT '0',
    `operating_hours` text,
    PRIMARY KEY (`id`),
    KEY `site_id` (`site_id`)
) {charset_collate};