-- Migration: add view_count column to #__docshop_documents
-- download_count is kept — it is incremented only on successful payment/download
-- view_count is incremented every time the detail page is loaded

ALTER TABLE `#__docshop_documents`
    ADD COLUMN IF NOT EXISTS `view_count` INT(11) NOT NULL DEFAULT 0 AFTER `download_count`;
