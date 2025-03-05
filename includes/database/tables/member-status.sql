CREATE TABLE IF NOT EXISTS `{prefix}vel_member_registrations` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) NOT NULL,
    `site_id` bigint(20) NOT NULL,
    `registration_date` datetime NOT NULL,
    `role` varchar(50) NOT NULL,
    `status` varchar(20) NOT NULL DEFAULT 'active',
    PRIMARY KEY (`id`),
    KEY `user_site` (`user_id`, `site_id`)
) {charset_collate};