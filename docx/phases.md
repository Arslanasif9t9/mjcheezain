# Phases — Delivery Roadmap

Status legend: ✅ done · 🔄 in progress · ⏳ planned

## Phase 1 — Foundation & Auth ✅
- Laravel 12 project, MySQL schema (users, customer_profile, vendor tables, orders/carts)
- Login / signup (`AuthController`), role-based redirects (customer / vendor / admin)
- Auth popup modal usable from any page (`components/auth-popup.blade.php`)
- Forgot-password page

## Phase 2 — Storefront ✅
- Home page: hero slider (auto-scroll ping-pong), Shop-by-Category grid, Featured Vendors grid, product row sliders, masonry `/products/all`
- Product detail page, related products, ratings display
- Search, biggest savings, category browsing
- Cosmetics landing: transparent header + full-screen hero, gold Cinzel MJ wordmark
- Auto Parts vertical scaffolded but behind Coming Soon (`@if(false)`)
- Global image fallback (`img-fallback.js`), footer pages rethemed & responsive
- Mobile home UX: smooth compact header scroll animation (0.22s, hysteresis 70/25px)

## Phase 3 — Commerce Core ✅
- Cart (add/update/clear), brand-themed cart page
- Checkout flow + order creation (brand pink theme)
- Order fulfilment statuses per line item

## Phase 4 — Deployment ✅
- GitHub Actions FTP autodeploy to Hostinger (`repo.mjcheezain.com`), working since 2026-07-14
- Production env hardening: env-based DB config, friendly error pages, `.env.production.example`

## Phase 5 — Customer Dashboard (app-like redesign) ✅ *(completed 2026-07-15, commit `dc7b0cd`)*
- Shared components: `theme.blade.php` (brand system), app-style `header.blade.php` (mobile gradient + compact-on-scroll, desktop bar), rethemed sidebar, bottom tab bar
- Redesigned + responsive: dashboard, orders (pill tabs, Rs. currency, rethemed modals), wishlist, addresses, profile, edit-profile, notifications, returns create/track
- All legacy blue theme removed from customer panel

## Phase 6 — Vendor Panel Polish ⏳
- Apply the same brand theme + mobile-app treatment to vendor views (dashboard, products CRUD, orders, withdraw, profile-edit)
- Vendor return/replacement handling screens retheme

## Phase 7 — Admin Panel ⏳
- Retheme + harden admin dashboard (`resources/views/Admin`)
- Vendor approval / product moderation flows

## Phase 8 — Auto Parts Launch ⏳
- Remove Coming Soon gate, category data, autoparts-specific theming

## Phase 9 — Payments & Growth ⏳
- Online payment gateway (JazzCash/Easypaisa/cards) — currently COD only
- Email notifications (order confirmations, return updates) via `resources/views/emails`
- Performance pass: replace CDN Tailwind with compiled CSS if/when a build step becomes acceptable

> Note: page-level order of work inside any phase = login → dashboard → lists → forms → edge flows (returns/replacements last), matching how customers actually move through the product.
