CREATE TABLE IF NOT EXISTS `{prefix}vel_task_logs` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `task_id` bigint(20) NOT NULL,
    `message` text NOT NULL,
    `log_type` varchar(20) NOT NULL DEFAULT 'info',
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `task_id_idx` (`task_id`),
    KEY `created_at_idx` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `{prefix}vel_task_performance` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `task_id` bigint(20) NOT NULL,
    `execution_time` int(11) NOT NULL,
    `memory_usage` int(11) NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `task_id_idx` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;