-- =====================================================================
-- MJ Cheezain — live DB update  (2026-08-12)
-- Run this in Hostinger phpMyAdmin on the LIVE database (u425346958_Arslan)
--
-- Adds the two new tables introduced in this session:
--   1) japan_products  -> /japan storefront + /japanadmin CRUD panel
--   2) site_settings   -> /admin/controls  (WhatsApp Buy Now toggle)
--
-- SAFE TO RE-RUN: everything uses IF NOT EXISTS / INSERT ... WHERE NOT EXISTS,
-- so running it twice will not duplicate or destroy anything.
-- No existing table is modified or dropped.
-- =====================================================================


-- ---------------------------------------------------------------------
-- 1) japan_products
--    Backs the /japan pages (JapanController) and the new /japanadmin panel.
--    NOTE: this table never had a migration on record — it existed only on
--    local. This is the exact same structure.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `japan_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `made_in` varchar(100) DEFAULT NULL,
  `conditionp` enum('New','Used','Refurbished') DEFAULT 'New',
  `selling_price` decimal(10,2) NOT NULL,
  `mrp` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ---------------------------------------------------------------------
-- 2) site_settings
--    Key/value store for the admin Controls page. Extensible — future
--    controls just add more rows, no schema change needed.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Seed the two control rows (OFF by default, blank number).
-- The admin Controls page will overwrite these when you hit Save.
INSERT INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'whatsapp_buy_now_enabled', '0', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `key` = 'whatsapp_buy_now_enabled');

INSERT INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'whatsapp_buy_now_number', '', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `key` = 'whatsapp_buy_now_number');


-- =====================================================================
-- DONE. Verify with:
--   SELECT * FROM site_settings;                  -- should show 2 rows
--   SELECT COUNT(*) FROM japan_products;          -- 0 (empty, add via /japanadmin)
-- =====================================================================
