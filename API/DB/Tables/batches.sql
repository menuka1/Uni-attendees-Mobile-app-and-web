CREATE TABLE `batches`
(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `batch` TEXT NOT NULL,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`)
);
