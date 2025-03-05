CREATE TABLE IF NOT EXISTS `{prefix}vel_user_relations` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `main_user_id` bigint(20) NOT NULL,
    `local_user_id` bigint(20) NOT NULL,
    `site_id` bigint(20) NOT NULL,
    `created_at` datetime NOT NULL,
    `status` varchar(20) NOT NULL DEFAULT 'active',
    PRIMARY KEY (`id`),
    UNIQUE KEY `site_user` (`site_id`, `local_user_id`),
    KEY `main_user_id` (`main_user_id`)
) {charset_collate};