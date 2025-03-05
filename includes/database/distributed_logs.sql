CREATE TABLE IF NOT EXISTS {prefix}vel_distributed_logs (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    message text NOT NULL,
    level varchar(20) NOT NULL,
    context longtext,
    	race_id varchar(100) NOT NULL,
    created_at datetime NOT NULL,
    PRIMARY KEY (id),
    KEY level_idx (level),
    KEY created_at_idx (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
