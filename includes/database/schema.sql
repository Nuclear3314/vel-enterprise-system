-- 安全日誌表
CREATE TABLE IF NOT EXISTS `{prefix}vel_security_logs` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `event_type` varchar(50) NOT NULL,
    `message` text NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `user_id` bigint(20) DEFAULT NULL,
    `created_at` datetime NOT NULL,
    `severity` tinyint(4) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `event_type` (`event_type`),
    KEY `user_id` (`user_id`)
) {charset_collate};

-- 物流寄存點表
CREATE TABLE IF NOT EXISTS `{prefix}vel_storage_locations` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `address` text NOT NULL,
    `capacity` int(11) NOT NULL DEFAULT '0',
    `current_usage` int(11) NOT NULL DEFAULT '0',
    `status` varchar(20) NOT NULL DEFAULT 'active',
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
) {charset_collate};

-- AI 操作記錄表
CREATE TABLE IF NOT EXISTS `{prefix}vel_ai_logs` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) NOT NULL,
    `command` text NOT NULL,
    `response` longtext,
    `execution_time` float NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) {charset_collate};

CREATE TABLE IF NOT EXISTS `{prefix}vel_db_version` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `version` varchar(20) NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
) {charset_collate};

-- VEL Enterprise System 資料庫結構
CREATE TABLE IF NOT EXISTS `{prefix}vel_logs` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `type` varchar(50) NOT NULL,
    `message` text NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `{prefix}vel_settings` (
    `setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
    `setting_name` varchar(191) NOT NULL,
    `setting_value` longtext NOT NULL,
    `autoload` varchar(20) NOT NULL DEFAULT 'yes',
    PRIMARY KEY (`setting_id`),
    UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;