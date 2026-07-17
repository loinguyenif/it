-- Migration: add version column to #__docshop_documents
-- Run this once against your existing database if you installed before version 1.0.1

ALTER TABLE `#__docshop_documents`
    ADD COLUMN IF NOT EXISTS `version` VARCHAR(255) NOT NULL DEFAULT '' AFTER `description`;
