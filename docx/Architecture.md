# Architecture — MJ Cheezain

## 1. Tech Stack

| Layer | Technology |
|---|---|
| Backend | **Laravel 12** (PHP 8.2+) — monolith, server-rendered Blade |
| Frontend | **Blade templates + Tailwind CSS (Play CDN)** + vanilla JS (fetch/AJAX) |
| Database | MySQL (query builder `DB::` heavily used; a few Eloquent models) |
| Fonts/Icons | Google Fonts (Poppins, Cinzel), Font Awesome 6 (CDN) |
| Dev server | `php artisan serve` → http://127.0.0.1:8000 |
| Hosting | Hostinger shared hosting (`repo.mjcheezain.com`, account `u425346958`) |
| CI/CD | GitHub Actions → FTP deploy (SamKirkland/FTP-Deploy-Action) on push to `main` |

**No build step.** There is no Vite/npm pipeline in production use — Tailwind runs from the CDN, JS is plain `<script>` in Blade files or `public/js/*.js`.

## 2. App Flow

```
Visitor
  └─ Home (/) ── browse categories/vendors/products ── product page ── add to cart
        │                                                            │
        └─ Auth popup (login/signup) ────────────┐                   ▼
                                                 │                 Cart (/cart) ── Checkout ── Order created
Roles after login:                               │
  Customer ── /customer/dashboard ◄──────────────┘
  │            orders / wishlist / addresses / profile / notifications / returns
  Vendor   ── /vendor/dashboard
  │            products CRUD / orders / withdraw / profile / returns+replacements
  Admin    ── /admin/dashboard
```

- Auth: `AuthController` (`POST /login`, `POST /signup`, `/logout`); customer & vendor routes live behind `auth` middleware in route groups (`routes/web.php` ~142 routes).
- Post-delivery: rate (`/rate-product`), replace (`/submit-replace-request` + tracking), return (`/customer/returns/create/{order}/{cart}` + `/customer/returns/track/{id}`).
- Frontend↔backend data: Blade renders initial page; dynamic parts (orders list, wishlist, addresses, favorites, tracking) call JSON endpoints via `fetch` with `X-CSRF-TOKEN`.

## 3. Folder & File Architecture

```
mjcheezain/
├── app/
│   ├── Http/Controllers/          # Fat controllers, mostly DB:: query builder
│   │   ├── AuthController.php     # login/signup/logout
│   │   ├── CustomerController.php # dashboard, profile, addresses, wishlist, notifications
│   │   ├── OrderController.php    # orders, get_orders JSON
│   │   ├── CartController.php / CheckoutController.php
│   │   ├── ProductController.php / SearchController.php / HomeController.php
│   │   ├── FavoriteController.php / ProductRatingController.php
│   │   ├── ReturnController.php / VendorReturnController.php / VendorReplacementController.php
│   │   ├── VendorController.php / BalanceController.php / SalesController.php
│   │   ├── AdminAuthController.php / ProfileImageController.php / AddressController.php
│   │   └── Vendor/                # vendor-specific sub-controllers
│   └── Models/                    # User, CustomerProfile, VendorProduct, Cart, etc.
├── routes/web.php                 # ALL routes (public, customer.*, vendor.*, admin)
├── resources/views/
│   ├── index.blade.php            # home page
│   ├── product.blade.php, cart.blade.php, checkout.blade.php
│   ├── layouts/structure.blade.php# public-site layout (@extends)
│   ├── home/                      # index/login/forgot
│   ├── products/                  # listing partials & category rows
│   ├── customer/                  # customer panel (standalone HTML pages, no layout)
│   │   ├── dashboard / orders / wishlist / addresses / profile / edit-profile / notifications
│   │   └── returns/create, returns/track
│   ├── components/
│   │   ├── header.blade.php       # main site header (mobile scroll animation)
│   │   ├── cosmetics/header...    # transparent cosmetics header
│   │   ├── auth-popup.blade.php   # login/signup modal (@once)
│   │   ├── mj-guide.blade.php     # 🤖 chatbot widget (@once; injected via global-nav + both mobile-navs + login page)
│   │   ├── footer.blade.php
│   │   └── customer/              # customer-panel shared components
│   │       ├── theme.blade.php    #  ⭐ brand tailwind config + base CSS (include in <head>)
│   │       ├── header.blade.php   #  ⭐ app-style header (mobile gradient + desktop bar)
│   │       ├── sidebar.blade.php  #  desktop sidebar + logout modal
│   │       └── mobile-nav.blade.php # bottom tab bar (5 tabs)
│   ├── vendor/                    # vendor panel views
│   ├── Admin/                     # admin panel views
│   └── footer/                    # footer info pages
├── app/Services/MjGuide/          # 🤖 MJ Guide chatbot backend
│   ├── ChatService.php            # Gemini→Grok failover + circuit breaker + system prompt
│   ├── GeminiProvider.php / GrokProvider.php
│   └── knowledge.md               # site knowledge base (cached 1h — cache:clear after edits)
├── public/
│   ├── js/img-fallback.js         # global image error fallback (included in ~39 views)
│   ├── js/mj-guide.js             # chatbot widget logic (localStorage history, 70-msg cap)
│   └── storage/ → storage/app/public (symlink: customer/vendor images)
├── claude-code-data/              # reference screenshots (ss1..ss9) — design refs
├── docx/                          # 📄 project documentation (this folder)
├── .github/workflows/deploy.yml   # FTP autodeploy to Hostinger
└── .env.production.example        # template for server .env
```

## 4. Customer Panel Page Pattern (standard since July 2026 redesign)

Every `resources/views/customer/*.blade.php` page is a standalone HTML document following this skeleton:

```blade
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">   {{-- if page does AJAX --}}
    <x-customer.theme />                                     {{-- fonts + tailwind + brand CSS --}}
    <style>/* page-specific styles only */</style>
</head>
<body>
    <div class="flex min-h-screen">
        <x-customer.sidebar :basic_info="$basic_info"/>
        <div class="flex flex-col flex-1 min-w-0">
            <x-customer.header title="..." subtitle="..." :basic_info="$basic_info" back="/optional/url" />
            <main class="flex-1 p-4 md:p-6 lg:p-8 pb-28 md:pb-8 page-enter">
                {{-- app-card sections --}}
            </main>
            <x-customer.mobile-nav />
        </div>
    </div>
</body>
```

Key contract points:
- Body scrolls normally (`min-h-screen`, **not** `h-screen overflow-hidden`) — the mobile header's compact-on-scroll JS listens on `window` scroll.
- `pb-28` on `<main>` reserves space for the bottom tab bar on mobile.
- Every controller method passes `$basic_info` (row from `customer_profile`) — all three shared components need it.

## 5. Data Layer Notes

Main tables used directly via `DB::table()`: `users`, `customer_profile`, `customer_addresses`, `orders`, `carts`, `vendor_products`, `vendor_product_images`, `favorites`, `notifications`, `product_ratings`, `return_requests`, `return_tracking`, `replacement_requests`.

- `carts` rows carry per-item `status` (`processing/shipping/delivered/cancelled`) — order status is per line item.
- Product images resolve to `/storage/vendor/products/images/{file}`; customer avatars to `/storage/customer/profile/{file}`; banners to `/storage/customer/banner/{file}`. Always use **relative** `/storage/...` or `asset()` — never hardcode a domain.

## 6. Deployment Pipeline

1. Push to `main` on GitHub.
2. `.github/workflows/deploy.yml` runs → FTP upload to `82.25.113.189` `/public_html/` (only the hPanel FTP IP works; timeout 120s; DNS check skipped for IP literals).
3. Excluded from upload: `.env`, `vendor/`, `storage/` — the server keeps its own. If `composer.json` changes, run `composer install` on the server manually (SSH port 65002 available as fallback).
4. After config changes on server: `php artisan config:clear`.
