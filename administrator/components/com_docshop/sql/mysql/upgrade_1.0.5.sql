-- Track how many times a file has been downloaded per order.
-- Used to enforce the 3-download limit for guests.
ALTER TABLE `#__docshop_orders`
    ADD COLUMN `download_count` TINYINT UNSIGNED NOT NULL DEFAULT 0
        AFTER `last_download`;
