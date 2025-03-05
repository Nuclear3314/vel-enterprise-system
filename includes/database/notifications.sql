CREATE TABLE IF NOT EXISTS `{prefix}vel_notification_logs` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `message` text NOT NULL,
    `channel` varchar(50) NOT NULL,
    `status` varchar(20) NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `channel_idx` (`channel`),
    KEY `created_at_idx` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;