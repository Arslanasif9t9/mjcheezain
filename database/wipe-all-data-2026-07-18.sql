-- =====================================================================
--  MJ Cheezain — WIPE ALL USER DATA  (keep admins + category config)
--  Date: 2026-07-18
--
--  Ye script SIRF user-generated data hataata hai:
--    accounts (vendor+customer), products, images/videos rows, carts,
--    orders, payments, notifications, returns, replacements, wallets,
--    withdrawals, favorites, category suggestions, auto-parts products.
--
--  KABHI TOUCH NAHI karta (safe):
--    admin_users, admin_password_reset_tokens   <- admin logins/passwords
--    site_categories, site_subcategories        <- store category config
--    auto_parts_categories, auto_parts_subcategories
--    migrations, cache, cache_locks             <- framework plumbing
--
--  KAISE CHALAYEN (Hostinger):
--    1. phpMyAdmin kholo -> DB "u425346958_DB" select karo
--    2. PEHLE "Export" tab se ek backup le lo (safety — TRUNCATE undo nahi hota)
--    3. "Import" tab -> ye file choose karo -> Go
--
--  AGAR ERROR: "Table 'xxx' doesn't exist"  (mostly category_suggestions,
--  jo pending deploy import ke bina server par na ho) -> us ek TRUNCATE
--  line ko hata do aur dobara Import karo. Baaki sab tables live site
--  use karti hai, maujood hongi.
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ---- Accounts ------------------------------------------------------
TRUNCATE TABLE `users`;
TRUNCATE TABLE `vendor_basic_info`;
TRUNCATE TABLE `vendor_store_details`;
TRUNCATE TABLE `vendor_address`;
TRUNCATE TABLE `vendor_documents`;
TRUNCATE TABLE `vendor_payments`;
TRUNCATE TABLE `customer_profile`;
TRUNCATE TABLE `customer_addresses`;
TRUNCATE TABLE `customer_banner`;
TRUNCATE TABLE `customer_notes`;
TRUNCATE TABLE `customer_recent_activity`;

-- ---- Products & media rows -----------------------------------------
TRUNCATE TABLE `vendor_products`;
TRUNCATE TABLE `vendor_product_images`;
TRUNCATE TABLE `vendor_product_faults`;
TRUNCATE TABLE `vendor_product_cards`;
TRUNCATE TABLE `products`;
TRUNCATE TABLE `product_ratings`;

-- ---- Orders / cart / payments --------------------------------------
TRUNCATE TABLE `carts`;
TRUNCATE TABLE `orders`;
TRUNCATE TABLE `payments`;
TRUNCATE TABLE `favorites`;

-- ---- Wallet / withdrawals ------------------------------------------
TRUNCATE TABLE `vendor_balances`;
TRUNCATE TABLE `balance_transactions`;
TRUNCATE TABLE `withdrawal_requests`;

-- ---- Returns / replacements ----------------------------------------
TRUNCATE TABLE `return_requests`;
TRUNCATE TABLE `return_tracking`;
TRUNCATE TABLE `return_images`;
TRUNCATE TABLE `replacement_requests`;
TRUNCATE TABLE `replacement_tracking`;

-- ---- Notifications & misc ------------------------------------------
TRUNCATE TABLE `notifications`;
TRUNCATE TABLE `category_suggestions`;   -- agar exist na kare to ye line hata do

-- ---- Auto-parts products (category tables KEEP) --------------------
TRUNCATE TABLE `auto_parts_products`;
TRUNCATE TABLE `auto_parts_product_images`;
TRUNCATE TABLE `auto_parts_product_videos`;
TRUNCATE TABLE `auto_parts_product_faults`;

SET FOREIGN_KEY_CHECKS = 1;

-- Done. admin_users / site_categories / site_subcategories /
-- auto_parts_categories / auto_parts_subcategories untouched.
