CREATE TABLE IF NOT EXISTS `{prefix}vel_tasks` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text,
    `priority` varchar(20) NOT NULL DEFAULT 'normal',
    `status` varchar(20) NOT NULL DEFAULT 'pending',
    `attempts` int(11) NOT NULL DEFAULT 0,
    `max_attempts` int(11) NOT NULL DEFAULT 3,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    `next_attempt_at` datetime DEFAULT NULL,
    `completed_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `status_priority_idx` (`status`, `priority`),
    KEY `next_attempt_idx` (`next_attempt_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;