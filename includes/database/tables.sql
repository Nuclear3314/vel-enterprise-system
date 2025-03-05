-- AI 命令記錄表
CREATE TABLE `{prefix}vel_ai_commands` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `site_id` bigint(20) NOT NULL,
    `command` text NOT NULL,
    `status` varchar(20) NOT NULL,
    `created_at` datetime NOT NULL
);

-- 社交媒體排程表
CREATE TABLE `{prefix}vel_social_schedules` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `platform` varchar(50) NOT NULL,
    `content_type` varchar(20) NOT NULL,
    `schedule_time` datetime NOT NULL,
    `status` varchar(20) NOT NULL
);

-- 站點認證記錄表
CREATE TABLE `{prefix}vel_auth_logs` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `site_id` bigint(20) NOT NULL,
    `auth_time` datetime NOT NULL,
    `status` varchar(20) NOT NULL
);