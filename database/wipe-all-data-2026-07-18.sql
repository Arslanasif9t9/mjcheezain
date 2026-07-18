-- =====================================================================
--  MJ Cheezain — WIPE ALL USER DATA  (keep admins + category config)
--  Date: 2026-07-18   |   v3 — child-first DELETE order (FK-proof)
--
--  Ye script child tables ko PEHLE delete karta hai aur parent (users)
--  ko SABSE AAKHIR mein — isliye foreign-key checks ON hon ya OFF,
--  koi #1451 / #1701 error kabhi nahi aayega. Idempotent bhi hai
--  (adhe-adhoore delete ke baad dobara chalao to bhi safe).
--
--  KABHI TOUCH NAHI karta:
--    admin_users, admin_password_reset_tokens   <- admin logins/passwords
--    site_categories, site_subcategories        <- store category config
--    auto_parts_categories, auto_parts_subcategories
--
--  KAISE CHALAYEN (Hostinger phpMyAdmin):
--    1. Apni database select karo
--    2. "Import" tab -> ye file choose karo
--    3. (Recommended) neeche "Enable foreign key checks" ka TICK HATA do
--    4. Go
--
--  AGAR ERROR: "Table 'xxx' doesn't exist" (mostly category_suggestions)
--    -> us ek DELETE line ko hata do aur dobara Import karo.
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ---- 1) Sabse deep children (orders/products se judi) --------------
DELETE FROM `payments`;
DELETE FROM `product_ratings`;
DELETE FROM `return_images`;
DELETE FROM `return_tracking`;
DELETE FROM `replacement_tracking`;

-- ---- 2) Return / replacement requests ------------------------------
DELETE FROM `return_requests`;
DELETE FROM `replacement_requests`;

-- ---- 3) Orders / cart / wishlist -----------------------------------
DELETE FROM `orders`;
DELETE FROM `carts`;
DELETE FROM `favorites`;

-- ---- 4) Product media -> products ----------------------------------
DELETE FROM `vendor_product_images`;
DELETE FROM `vendor_product_faults`;
DELETE FROM `vendor_product_cards`;
DELETE FROM `vendor_products`;

-- ---- 5) Auto-parts products (category tables KEEP) -----------------
DELETE FROM `auto_parts_product_images`;
DELETE FROM `auto_parts_product_videos`;
DELETE FROM `auto_parts_product_faults`;
DELETE FROM `auto_parts_products`;

-- ---- 6) Customer satellite data ------------------------------------
DELETE FROM `customer_addresses`;
DELETE FROM `customer_banner`;
DELETE FROM `customer_notes`;
DELETE FROM `customer_recent_activity`;
DELETE FROM `customer_profile`;

-- ---- 7) Vendor satellite data + wallet -----------------------------
DELETE FROM `vendor_documents`;
DELETE FROM `vendor_payments`;
DELETE FROM `vendor_address`;
DELETE FROM `vendor_store_details`;
DELETE FROM `vendor_basic_info`;
DELETE FROM `balance_transactions`;
DELETE FROM `vendor_balances`;
DELETE FROM `withdrawal_requests`;

-- ---- 8) Misc -------------------------------------------------------
DELETE FROM `notifications`;
DELETE FROM `category_suggestions`;   -- agar exist na kare to ye line hata do
DELETE FROM `products`;

-- ---- 9) Parent account table SABSE AAKHIR --------------------------
DELETE FROM `users`;

SET FOREIGN_KEY_CHECKS = 1;

-- Done. admin_users / site_categories / site_subcategories /
-- auto_parts_categories / auto_parts_subcategories untouched.
