-- ============================================================
-- MJ Cheezain — Hostinger deploy SQL (2026-07-16 mega sprint)
-- phpMyAdmin me poori file ek sath run karein (database select kar ke).
-- Dobara run karna SAFE hai (IF NOT EXISTS / WHERE NOT EXISTS guards).
-- ============================================================
SET NAMES utf8mb4;

-- 1) Vendor 'Other' category suggestions (admin review queue)
CREATE TABLE IF NOT EXISTS `category_suggestions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `category_name` VARCHAR(255) NOT NULL,
  `subcategory_name` VARCHAR(255) NULL,
  `product_id` BIGINT UNSIGNED NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `admin_notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  INDEX `cs_status_created_idx` (`status`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2) Admin-managed storefront categories
CREATE TABLE IF NOT EXISTS `site_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `emoji` VARCHAR(16) NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'active',
  `show_on_home` TINYINT(1) NOT NULL DEFAULT 0,
  `show_on_cosmetics` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT NOT NULL DEFAULT 0,
  `source` VARCHAR(255) NOT NULL DEFAULT 'core',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `site_subcategories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `ssc_cat_name_unique` (`category_id`, `name`),
  INDEX `ssc_category_idx` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3) Seed categories + subcategories (local DB se exact copy)
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Auto Parts & Accessories','🚗','active',1,0,0,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Auto Parts & Accessories');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Car Tools & Maintenance','🛠️','active',1,0,1,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Car Tools & Maintenance');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Perfumes & Fragrances','🧴','active',1,1,2,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Perfumes & Fragrances');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Fitness & Gym Equipment','🏋️','active',1,0,3,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Fitness & Gym Equipment');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Supplements','💊','active',1,0,4,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Supplements');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Gym Accessories','🎽','active',1,0,5,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Gym Accessories');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Women\'s Fashion','👜','active',1,0,6,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Women\'s Fashion');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Men\'s Accessories','👔','active',1,0,7,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Men\'s Accessories');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Clothing & Apparel','👕','active',1,0,8,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Clothing & Apparel');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Mobile Accessories','📱','active',1,0,9,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Mobile Accessories');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Home & Living','🏠','active',1,0,10,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Home & Living');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Gifts & General Items','🎁','active',1,0,11,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Gifts & General Items');
INSERT INTO `site_categories` (`name`,`emoji`,`status`,`show_on_home`,`show_on_cosmetics`,`sort_order`,`source`,`created_at`,`updated_at`)
SELECT 'Cosmetics','💄','active',1,1,12,'core',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `site_categories` WHERE `name` = 'Cosmetics');

INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Engine Parts', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Auto Parts & Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Engine Parts');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Body Parts', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Auto Parts & Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Body Parts');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Suspension & Steering', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Auto Parts & Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Suspension & Steering');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Brakes & Brake Parts', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Auto Parts & Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Brakes & Brake Parts');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Car Electronics', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Auto Parts & Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Car Electronics');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Interior Accessories', 5, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Auto Parts & Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Interior Accessories');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Exterior Accessories', 6, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Auto Parts & Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Exterior Accessories');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Tyres & Wheels', 7, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Auto Parts & Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Tyres & Wheels');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Car Cleaning', 8, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Auto Parts & Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Car Cleaning');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Mechanical Tools', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Car Tools & Maintenance' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Mechanical Tools');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Battery Chargers', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Car Tools & Maintenance' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Battery Chargers');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Car Jacks', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Car Tools & Maintenance' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Car Jacks');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Air Compressors', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Car Tools & Maintenance' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Air Compressors');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Diagnostic Tools', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Car Tools & Maintenance' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Diagnostic Tools');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Men Perfumes', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Perfumes & Fragrances' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Men Perfumes');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Women Perfumes', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Perfumes & Fragrances' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Women Perfumes');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Body Mists', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Perfumes & Fragrances' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Body Mists');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Fragrance Oils', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Perfumes & Fragrances' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Fragrance Oils');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Gift Sets', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Perfumes & Fragrances' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Gift Sets');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Dumbbells & Weights', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Dumbbells & Weights');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Barbells & Weight Plates', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Barbells & Weight Plates');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Kettlebells', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Kettlebells');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Weight Benches', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Weight Benches');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Power Racks & Squat Racks', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Power Racks & Squat Racks');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Treadmills', 5, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Treadmills');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Exercise Bikes', 6, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Exercise Bikes');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Cross Trainers / Ellipticals', 7, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Cross Trainers / Ellipticals');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Rowing Machines', 8, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Rowing Machines');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Steppers', 9, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Steppers');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Multi Gym Machines', 10, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Multi Gym Machines');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Smith Machines', 11, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Smith Machines');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Cable Machines', 12, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Cable Machines');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Resistance Bands', 13, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Resistance Bands');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Battle Ropes', 14, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Battle Ropes');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Medicine Balls', 15, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Medicine Balls');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Slam Balls', 16, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Slam Balls');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Pull-Up Bars', 17, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Pull-Up Bars');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Push-Up Bars', 18, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Push-Up Bars');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Ab Rollers', 19, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Ab Rollers');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Gym Rings', 20, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Gym Rings');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Yoga Mats', 21, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Yoga Mats');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Yoga Blocks', 22, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Yoga Blocks');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Yoga Straps', 23, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Yoga Straps');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Foam Rollers', 24, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Foam Rollers');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Punching Bags', 25, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Punching Bags');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Boxing Gloves', 26, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Boxing Gloves');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Hand Wraps', 27, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Hand Wraps');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Skipping Ropes', 28, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Skipping Ropes');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Gym Gloves', 29, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Gym Gloves');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Weightlifting Belts', 30, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Weightlifting Belts');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Wrist / Knee / Elbow Supports', 31, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Wrist / Knee / Elbow Supports');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Lifting Straps', 32, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Fitness & Gym Equipment' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Lifting Straps');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Protein Supplements', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Supplements' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Protein Supplements');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Mass Gainers', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Supplements' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Mass Gainers');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Creatine', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Supplements' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Creatine');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Pre-Workout Supplements', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Supplements' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Pre-Workout Supplements');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Vitamins & Minerals', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Supplements' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Vitamins & Minerals');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Water Bottles', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Gym Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Water Bottles');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Shakers', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Gym Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Shakers');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Gym Bags', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Gym Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Gym Bags');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Gym Towels', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Gym Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Gym Towels');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Handbags', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Women\'s Fashion' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Handbags');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Clutches & Wallets', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Women\'s Fashion' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Clutches & Wallets');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Shoulder Bags', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Women\'s Fashion' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Shoulder Bags');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Crossbody Bags', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Women\'s Fashion' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Crossbody Bags');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Women Jewelry', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Women\'s Fashion' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Women Jewelry');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Scarves & Shawls', 5, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Women\'s Fashion' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Scarves & Shawls');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Hair Accessories', 6, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Women\'s Fashion' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Hair Accessories');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Watches', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Men\'s Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Watches');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Bracelets', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Men\'s Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Bracelets');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Chains', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Men\'s Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Chains');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Rings', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Men\'s Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Rings');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Sunglasses', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Men\'s Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Sunglasses');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Wallets', 5, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Men\'s Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Wallets');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Men Clothing', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Clothing & Apparel' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Men Clothing');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Women Clothing', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Clothing & Apparel' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Women Clothing');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Kids Clothing', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Clothing & Apparel' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Kids Clothing');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Footwear', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Clothing & Apparel' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Footwear');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Mobile Covers', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Mobile Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Mobile Covers');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Chargers', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Mobile Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Chargers');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Handsfree & Earphones', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Mobile Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Handsfree & Earphones');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Power Banks', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Mobile Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Power Banks');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Screen Protectors', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Mobile Accessories' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Screen Protectors');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Decoration Items', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Home & Living' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Decoration Items');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'LED Lights', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Home & Living' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'LED Lights');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Clocks', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Home & Living' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Clocks');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Wall Frames', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Home & Living' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Wall Frames');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Artificial Flowers', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Home & Living' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Artificial Flowers');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Keychains', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Gifts & General Items' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Keychains');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Mugs', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Gifts & General Items' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Mugs');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Gift Boxes', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Gifts & General Items' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Gift Boxes');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Custom Printed Items', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Gifts & General Items' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Custom Printed Items');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Souvenirs', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Gifts & General Items' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Souvenirs');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Skincare', 0, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Cosmetics' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Skincare');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Makeup', 1, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Cosmetics' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Makeup');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Hair Care', 2, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Cosmetics' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Hair Care');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Nail Care', 3, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Cosmetics' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Nail Care');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Body Care', 4, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Cosmetics' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Body Care');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Fragrances', 5, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Cosmetics' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Fragrances');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Beauty Tools', 6, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Cosmetics' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Beauty Tools');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Men\'s Grooming', 7, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Cosmetics' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Men\'s Grooming');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Whitening', 8, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Cosmetics' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Whitening');
INSERT INTO `site_subcategories` (`category_id`,`name`,`sort_order`,`created_at`,`updated_at`)
SELECT c.`id`, 'Lotion', 9, NOW(), NOW() FROM `site_categories` c
WHERE c.`name` = 'Cosmetics' AND NOT EXISTS (SELECT 1 FROM `site_subcategories` x WHERE x.`category_id` = c.`id` AND x.`name` = 'Lotion');

-- 4) Naye admin accounts (passwords bcrypt-hashed — plaintext kahin nahi)
INSERT INTO `admin_users` (`username`,`email`,`password_hash`,`created_at`,`updated_at`)
SELECT 'arslanadmin@gmail.com','arslanadmin@gmail.com','$2y$12$QayFuLCOiSEGJT4pORO03e74CaMJH3NwoU23T2mqifG0MW2Hcb.F6',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `admin_users` WHERE `username` = 'arslanadmin@gmail.com');
INSERT INTO `admin_users` (`username`,`email`,`password_hash`,`created_at`,`updated_at`)
SELECT 'mjcheezain@gmail.com','mjcheezain@gmail.com','$2y$12$CN2MHOjHXxK.tfx35CmclOOAkvTfGdKwKiCW0eRxsxP0IIQpauVtK',NOW(),NOW()
WHERE NOT EXISTS (SELECT 1 FROM `admin_users` WHERE `username` = 'mjcheezain@gmail.com');

-- 5) (Optional lekin recommended) Purane weak admin ka password bhi hash ho jayega
--    jab wo pehli bar login karega — koi query zaroori nahi.
-- DONE.
