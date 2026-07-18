-- =====================================================================
--  MJ Cheezain — WIPE ALL USER DATA  (keep admins + category config)
--  Date: 2026-07-18   |   v2 (DELETE-based, MariaDB/Hostinger safe)
--
--  NOTE: TRUNCATE MariaDB par FK-referenced tables (jaise `users`) pe
--  fail hota hai (#1701) chahe FK checks off ho. Isliye ye script
--  `DELETE FROM` use karta hai — same result, bilkul safe.
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
--
--  KAISE CHALAYEN (Hostinger):
--    1. phpMyAdmin -> apni database select karo
--    2. PEHLE "Export" tab se ek backup le lo (safety)
--    3. "Import" tab -> ye file choose karo -> Go
--
--  AGAR ERROR: "Table 'xxx' doesn't exist"  (mostly category_suggestions)
--    -> us ek DELETE line ko hata do aur dobara Import karo.
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ---- Accounts ------------------------------------------------------
DELETE FROM `users`;
DELETE FROM `vendor_basic_info`;
DELETE FROM `vendor_store_details`;
DELETE FROM `vendor_address`;
DELETE FROM `vendor_documents`;
DELETE FROM `vendor_payments`;
DELETE FROM `customer_profile`;
DELETE FROM `customer_addresses`;
DELETE FROM `customer_banner`;
DELETE FROM `customer_notes`;
DELETE FROM `customer_recent_activity`;

-- ---- Products & media rows -----------------------------------------
DELETE FROM `vendor_products`;
DELETE FROM `vendor_product_images`;
DELETE FROM `vendor_product_faults`;
DELETE FROM `vendor_product_cards`;
DELETE FROM `products`;
DELETE FROM `product_ratings`;

-- ---- Orders / cart / payments --------------------------------------
DELETE FROM `carts`;
DELETE FROM `orders`;
DELETE FROM `payments`;
DELETE FROM `favorites`;

-- ---- Wallet / withdrawals ------------------------------------------
DELETE FROM `vendor_balances`;
DELETE FROM `balance_transactions`;
DELETE FROM `withdrawal_requests`;

-- ---- Returns / replacements ----------------------------------------
DELETE FROM `return_requests`;
DELETE FROM `return_tracking`;
DELETE FROM `return_images`;
DELETE FROM `replacement_requests`;
DELETE FROM `replacement_tracking`;

-- ---- Notifications & misc ------------------------------------------
DELETE FROM `notifications`;
DELETE FROM `category_suggestions`;   -- agar exist na kare to ye line hata do

-- ---- Auto-parts products (category tables KEEP) --------------------
DELETE FROM `auto_parts_products`;
DELETE FROM `auto_parts_product_images`;
DELETE FROM `auto_parts_product_videos`;
DELETE FROM `auto_parts_product_faults`;

SET FOREIGN_KEY_CHECKS = 1;

-- Done. admin_users / site_categories / site_subcategories /
-- auto_parts_categories / auto_parts_subcategories untouched.
