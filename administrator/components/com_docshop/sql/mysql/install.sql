CREATE TABLE IF NOT EXISTS `#__docshop_documents` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` LONGTEXT,
    `youtube_url` VARCHAR(500) DEFAULT NULL,
    `file` VARCHAR(255) NOT NULL,
    `file_size` INT(11),
    `price` DECIMAL(10, 2) NOT NULL,
    `category_id` INT(11),
    `platform_id` INT(11) NULL,
    `download_count` INT(11) DEFAULT 0,
    `published` TINYINT(3) DEFAULT 1,
    `access` INT(11) DEFAULT 1,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `modified` DATETIME ON UPDATE CURRENT_TIMESTAMP,
    `created_by` INT(11),
    `alias` VARCHAR(255),
    UNIQUE KEY `alias` (`alias`),
    INDEX `published` (`published`),
    INDEX `category_id` (`category_id`),
    INDEX `platform_id` (`platform_id`),
    FOREIGN KEY (`platform_id`) REFERENCES `#__docshop_platforms`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__docshop_orders` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) NOT NULL,
    `document_id` INT(11) NOT NULL,
    `order_number` VARCHAR(50) UNIQUE,
    `paypal_transaction_id` VARCHAR(100),
    `amount` DECIMAL(10, 2) NOT NULL,
    `currency` VARCHAR(3) DEFAULT 'USD',
    `status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    `payment_method` VARCHAR(50) DEFAULT 'paypal',
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `modified` DATETIME ON UPDATE CURRENT_TIMESTAMP,
    `download_count` INT(11) DEFAULT 0,
    `last_download` DATETIME,
    INDEX `user_id` (`user_id`),
    INDEX `document_id` (`document_id`),
    INDEX `status` (`status`),
    FOREIGN KEY (`user_id`) REFERENCES `#__users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`document_id`) REFERENCES `#__docshop_documents`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__docshop_categories` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` LONGTEXT,
    `published` TINYINT(3) DEFAULT 1,
    `access` INT(11) DEFAULT 1,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `alias` VARCHAR(255) UNIQUE,
    INDEX `published` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__docshop_platforms` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` LONGTEXT,
    `published` TINYINT(3) DEFAULT 1,
    `access` INT(11) DEFAULT 1,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `alias` VARCHAR(255) UNIQUE,
    INDEX `published` (`published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

