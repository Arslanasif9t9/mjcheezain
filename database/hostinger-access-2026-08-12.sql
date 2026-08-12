-- =====================================================================
-- MJ Cheezain — live DB update  (2026-08-12, part 2: Account Access)
-- Run this in Hostinger phpMyAdmin on the LIVE database (u425346958_Arslan)
--
-- Adds the 6 switches behind the new "Account Access" section on
-- /admin/controls  (customer + vendor: sign-in, registration, force logout).
--
-- SAFE TO RE-RUN: every statement is IF NOT EXISTS / WHERE NOT EXISTS.
-- Nothing is dropped, no existing row is overwritten — if you have already
-- changed a switch from the admin panel, this will NOT undo your choice.
--
-- Self-contained: it also creates site_settings and re-seeds the two
-- WhatsApp keys, so it works even if the earlier file was never run.
-- =====================================================================


-- ---------------------------------------------------------------------
-- site_settings (created by the earlier 2026-08-12 file; here for safety)
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


-- ---------------------------------------------------------------------
-- WhatsApp ordering (from the earlier file — skipped if already present)
-- ---------------------------------------------------------------------
INSERT INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'whatsapp_buy_now_enabled', '0', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `key` = 'whatsapp_buy_now_enabled');

INSERT INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'whatsapp_buy_now_number', '', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `key` = 'whatsapp_buy_now_number');


-- ---------------------------------------------------------------------
-- Account Access switches
--   '1' = allowed / on      '0' = blocked / off
-- Defaults below keep the site exactly as it is today: customers and
-- vendors can sign in and register, nobody is signed out.
-- Change them from /admin/controls, not here.
-- ---------------------------------------------------------------------

-- Customers
INSERT INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'customer_login_enabled', '1', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `key` = 'customer_login_enabled');

INSERT INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'customer_register_enabled', '1', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `key` = 'customer_register_enabled');

INSERT INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'customer_force_logout', '0', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `key` = 'customer_force_logout');

-- Vendors
INSERT INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'vendor_login_enabled', '1', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `key` = 'vendor_login_enabled');

INSERT INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'vendor_register_enabled', '1', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `key` = 'vendor_register_enabled');

INSERT INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`)
SELECT 'vendor_force_logout', '0', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_settings` WHERE `key` = 'vendor_force_logout');


-- =====================================================================
-- DONE. Check with:
--   SELECT `key`, `value` FROM site_settings ORDER BY `key`;
-- You should see 8 rows.
-- =====================================================================
