CREATE TABLE IF NOT EXISTS {prefix}vel_log_analysis (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    nalysis_type varchar(50) NOT NULL,
    esults longtext NOT NULL,
    created_at datetime NOT NULL,
    PRIMARY KEY (id),
    KEY nalysis_type_idx (nalysis_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
