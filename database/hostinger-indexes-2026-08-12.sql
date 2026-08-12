-- =====================================================================
-- MJ Cheezain — live DB performance indexes  (2026-08-12, part 3)
-- Run in Hostinger phpMyAdmin on the LIVE database (u425346958_Arslan)
--
-- WHY: the live tables were checked with SHOW INDEX and carry almost no
-- indexes — `vendor_products`, `carts` and `japan_products` had nothing but
-- their PRIMARY key. Every product listing filters position='approved' and
-- joins a carts.status='delivered' GROUP BY subquery, so those were full
-- table scans + filesorts on every page view. `carts` also grows a row on
-- every guest add-to-cart, so this gets worse as traffic grows.
--
-- SAFE: only adds indexes. No data is read, changed or deleted.
--
-- ⚠ NOT re-runnable as-is: MySQL/MariaDB has no "CREATE INDEX IF NOT EXISTS".
--    If you run it twice you'll get "Duplicate key name" errors — those are
--    harmless (they just mean the index already exists), you can ignore them.
-- =====================================================================


-- ---------------------------------------------------------------------
-- vendor_products — every storefront listing filters position='approved'
-- ---------------------------------------------------------------------
ALTER TABLE `vendor_products` ADD INDEX `vp_position_category_idx` (`position`, `category`);
ALTER TABLE `vendor_products` ADD INDEX `vp_position_rating_idx`   (`position`, `rating`, `updated_at`);
ALTER TABLE `vendor_products` ADD INDEX `vp_user_position_idx`     (`user_id`, `position`);


-- ---------------------------------------------------------------------
-- vendor_product_images — every listing joins on product_id + is_primary
-- ---------------------------------------------------------------------
ALTER TABLE `vendor_product_images` ADD INDEX `vpi_product_primary_idx` (`product_id`, `is_primary`);


-- ---------------------------------------------------------------------
-- carts — the "sold count" subquery scans this on EVERY product listing
-- ---------------------------------------------------------------------
ALTER TABLE `carts` ADD INDEX `carts_status_product_idx` (`status`, `product_id`);
ALTER TABLE `carts` ADD INDEX `carts_session_order_idx`  (`session_id`, `order_id`);
ALTER TABLE `carts` ADD INDEX `carts_user_order_idx`     (`user_id`, `order_id`);


-- ---------------------------------------------------------------------
-- japan_products — /japan listing + brand filter
-- ---------------------------------------------------------------------
ALTER TABLE `japan_products` ADD INDEX `jp_status_id_idx`    (`status`, `id`);
ALTER TABLE `japan_products` ADD INDEX `jp_status_brand_idx` (`status`, `brand`);


-- =====================================================================
-- DONE. Check with, for example:
--   SHOW INDEX FROM vendor_products;
-- =====================================================================
