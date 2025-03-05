CREATE TABLE IF NOT EXISTS `{prefix}vel_schedule_monitor` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `schedule_id` bigint(20) NOT NULL,
    `status` varchar(20) NOT NULL,
    `execution_time` int(11) NOT NULL DEFAULT 0,
    `memory_usage` bigint(20) NOT NULL DEFAULT 0,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `schedule_id_idx` (`schedule_id`),
    KEY `created_at_idx` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;