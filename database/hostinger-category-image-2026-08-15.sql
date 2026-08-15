-- =====================================================================
-- MJ Cheezain — live DB update (2026-08-15)
-- Run in Hostinger phpMyAdmin on the LIVE database (u425346958_Arslan)
--
-- Adds ONE column so the admin can set the home page "Shop by Category"
-- tile picture from /admin/categories. Nothing else changes.
--
-- If you run it twice you'll get "Duplicate column name 'image'" —
-- that's harmless, it just means the column is already there.
-- =====================================================================

ALTER TABLE `site_categories` ADD COLUMN `image` VARCHAR(255) NULL AFTER `emoji`;

-- Check with:  SHOW COLUMNS FROM site_categories LIKE 'image';
