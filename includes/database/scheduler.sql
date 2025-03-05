CREATE TABLE IF NOT EXISTS `{prefix}vel_scheduled_tasks` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `task_name` varchar(100) NOT NULL,
    `frequency` varchar(50) NOT NULL,
    `next_run` datetime NOT NULL,
    `last_run` datetime DEFAULT NULL,
    `status` varchar(20) NOT NULL DEFAULT 'active',
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `next_run_idx` (`next_run`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `{prefix}vel_task_dependencies` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `task_id` bigint(20) NOT NULL,
    `depends_on` bigint(20) NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `task_dependency_idx` (`task_id`, `depends_on`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;