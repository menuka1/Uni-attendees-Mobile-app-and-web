CREATE TABLE `lectures`
(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `lecture` TEXT NOT NULL,
    `lecturer` INT(11) NOT NULL,
    `batch` INT(11) NOT NULL,
    `day` DATE NOT NULL,
    `from` TIME NOT NULL,
    `to` TIME NOT NULL,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`)
);