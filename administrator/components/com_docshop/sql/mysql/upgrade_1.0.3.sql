-- Migration: allow guest checkout by making user_id nullable
-- and removing the FK constraint to #__users

-- Step 1: drop the foreign key (name may vary — use the constraint from install)
ALTER TABLE `#__docshop_orders`
    DROP FOREIGN KEY IF EXISTS `#__docshop_orders_ibfk_1`;

-- Step 2: make user_id nullable (guest = NULL instead of 0)
ALTER TABLE `#__docshop_orders`
    MODIFY COLUMN `user_id` INT(11) NULL DEFAULT NULL;
