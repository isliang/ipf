CREATE TABLE `cache_tag` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'primary key',
    `database` varchar(50) NOT NULL DEFAULT '' COMMENT 'database名',
    `table` varchar(50) NOT NULL DEFAULT '' COMMENT 'table名',
    `update_time` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `database`(`database`),
    UNIQUE KEY `table`(`database`,`table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据库缓存信息表';