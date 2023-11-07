CREATE TABLE `attendance`
(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lecture` INT(11) NOT NULL,
    `student` INT(11) NOT NULL,
    `attendance` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '0 - none, 1 - absent, 2 - present',
    `latitude` TEXT NOT NULL,
    `longitude` TEXT NOT NULL,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`)
);
