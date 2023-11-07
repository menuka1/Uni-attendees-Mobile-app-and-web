CREATE TABLE `students`
(
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `student_id` VARCHAR(100) NOT NULL,
    `batch` INT(11) NOT NULL,
    `password` VARCHAR(50) NOT NULL,
    `deleted_at` DATETIME NULL,
    PRIMARY KEY (`id`)
);