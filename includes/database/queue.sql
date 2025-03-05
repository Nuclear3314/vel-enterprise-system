CREATE TABLE IF NOT EXISTS `{prefix}vel_queue` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `queue` varchar(50) NOT NULL DEFAULT 'default',
    `payload` longtext NOT NULL,
    `attempts` int(11) NOT NULL DEFAULT 0,
    `created_at` datetime NOT NULL,
    `reserved_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `queue_reserved_at_idx` (`queue`, `reserved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;