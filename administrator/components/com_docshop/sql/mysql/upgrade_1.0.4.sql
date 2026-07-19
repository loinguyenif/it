-- Add paypal_payment_id column to store the PayPal payment ID for idempotency checks.
-- This prevents duplicate orders when the confirm URL is hit more than once
-- (back button, browser retry, double-redirect from PayPal).

ALTER TABLE `#__docshop_orders`
    ADD COLUMN `paypal_payment_id` VARCHAR(100) NULL DEFAULT NULL
        COMMENT 'PayPal payment ID (PAY-xxx) used for idempotency'
        AFTER `order_number`,
    ADD UNIQUE INDEX `uidx_paypal_payment_id` (`paypal_payment_id`);
