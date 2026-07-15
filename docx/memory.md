# Memory — Work Log & Current State

> Living document. Update after every meaningful work session: what was completed (with date + commit), and what is currently being worked on.

## 🔄 Currently Working On

- **MJGuider chatbot — WORKING with real Gemini, NOT pushed/deployed (owner: "abhi push nahi").** Gemini key in local `.env`, live-tested OK. Pending: `GROK_API_KEY` (fallback abhi key-less → dono fail par polite fallback msg), real phone number for `knowledge.md`, owner ka widget UI browser test, phir push approval. ⚠ Server `.env` par bhi keys manually dalni hongi (deploy .env upload nahi karta) + `php artisan config:clear`.
- **⚠ GOTCHA (2026-07-15):** naye Gemini keys par 2.0/2.5 models ka free-tier quota **0** hai (429/404 dete hain) — is liye model `gemini-3.1-flash-lite` use ho raha hai (free tier OK, tested). GeminiProvider ab "thought" parts skip kar ke sirf visible text jorta hai.

## ✅ Completed

### 2026-07-15 — MJGuider widget UI revamp (owner feedback round 1)
- **Rename**: "MJ Guide" → **MJGuider** everywhere (FAB label, header, welcome msg, aria labels, system prompt self-name — AI tested: "Mera naam MJGuider hai"; docs sed-renamed too).
- **FAB hides when chat open** (fade+scale); window opens in the same bottom-right corner. ESC bhi close karta hai.
- **Desktop overlap fix**: `#back-to-top` (public/css/style.css) moved ABOVE the FAB — desktop `right:1.5rem; bottom:5.6rem`, mobile `bottom:9.6rem` (FAB 5.9rem ke oopar).
- **Mobile premium sheet**: full-width bottom sheet 91dvh, rounded-top 24px, drag-handle, dim backdrop (tap = close), slide-up cubic-bezier, body scroll-lock while open, safe-area input padding. **z-index 10000/9999** kyunke bottom navs z-[9999] hain (pehle sheet unke neeche thi!). Vendor drawer 10001 + page-loader ab bhi oopar.
- **Clear-chat fix**: two-tap trash (confusing tha) → inline confirm strip "Poori chat clear kar dein? [Clear karein][Cancel]".
- **Input compact**: explicit 42px height (font 16px iOS-safe), send 40px.
- **Custom scrollbar**: thin 5px pink thumb (webkit + Firefox scrollbar-color).
- **Extras**: suggestion chips on empty chat (Order track/Return/Contact/Vendor — tap = send), bubble entry animation, avatar online-dot, `#cartSummary` overlap fix (MutationObserver `mjg-lift` lifts FAB jab bar visible ho — product pages).
- Verified: JS/PHP syntax, view:cache, live server home/cosmetics/cart/login-user = 1 widget, AI self-name test pass.

### 2026-07-15 — MJGuider chatbot: FULL BUILD (Phases A–E)
- **Backend** (`app/Services/MjGuide/`): `GeminiProvider` (v1beta generateContent, 15s timeout), `GrokProvider` (x.ai chat/completions, 20s), `ChatService` (failover on 429/quota/5xx/timeout/empty, `Cache` 5-min circuit breaker `mj_guide_gemini_down`, provider logged to laravel.log, Urdu fallback reply when both fail). System prompt: MJ-Cheezain-only scope, mirrors user language, Rs. only, phone "coming soon" + support email.
- **Knowledge base**: `app/Services/MjGuide/knowledge.md` (contacts, login/signup at /login-user, order flow COD-only, customer+vendor panel guides with real URLs verified from routes/web.php, returns/replacements, info pages, "does NOT exist yet" list). Cached 1h — `php artisan cache:clear` after editing it.
- **API**: `POST /mj-guide/message` (`MjGuideController`, throttle:10,1, validates message ≤1000 + context ≤10 items role-whitelisted). Stateless — no DB, no migrations.
- **Config**: `config/services.php` → `mj_guide.enabled`, `gemini.key/model`, `grok.key/model`; `.env` + `.env.production.example` got placeholder block (keys EMPTY — owner fills).
- **Widget**: `components/mj-guide.blade.php` (@once; brand gradient FAB bottom-right, chat window 380px/bottom-sheet mobile, 15.5px msg text, 16px input (iOS no-zoom), two-tap clear button, typing dots, unread dot; z 9990/9991 — under auth-popup 9998, vendor drawer 10001, page-loader) + `public/js/mj-guide.js` (localStorage `mj_guide_history` 70-cap, last-10 context, textContent-only XSS-safe, welcome bubble when empty, 429/network notes not saved).
- **Injection**: `customer/global-nav.blade.php` (OUTSIDE @auth → guests too), `customer/mobile-nav.blade.php`, `vendor/mobile-nav.blade.php`, `home/login.blade.php` — @once collapses overlaps.
- **Verified**: php -l ×4, node --check, view:cache OK; live server: home/cosmetics/cart/contact/product/login-user = 1 widget each; tinker full-kernel renders (customer 67: dashboard/orders/wishlist/home; vendor 66: dashboard/products/orders/withdraw) all 200 with exactly 1 widget; endpoint 200 fallback reply (no keys), 422 invalid payload, failover logged.
- ⚠ Widget UI browser-click test NOT done (Chrome extension unavailable) — owner should open site, click MJGuider, send a message.

### 2026-07-15 — MJGuider chatbot: complete planning + docs (NO code)
- New `docx/mj-guide.md` (full technical plan) + `docx/plan.html` (visual brand-themed plan page)
- Plan: floating bottom-right widget (all users incl. guests, injected via `customer/global-nav` which is on every page), Gemini→Grok silent failover with 5-min circuit breaker, system-prompt training + `knowledge.md` (MJ Cheezain-only scope), rate limits + `MJ_GUIDE_ENABLED` kill-switch
- **Owner revision (same day): history = browser localStorage ONLY, max 70 messages, NO database changes** (original DB-table plan dropped); AI ko old chat yaad rakhne ke liye last 10 messages har request ke sath context mein jate hain; server fully stateless
- `phases.md`: inserted as Phase 9 (Payments shifted to Phase 10); `PRD.md`: added §3.6

### 2026-07-15 — deploy exclude bug fixed — commit `77921c6` ⚠️ IMPORTANT GOTCHA
- Live error "Unable to locate component [vendor.mobile-nav]": deploy.yml excluded `**/vendor/**` which silently skipped `resources/views/vendor/**` AND `resources/views/components/vendor/**` from every FTP upload (vendor views on server were stale since pre-autodeploy days). Fixed: excludes anchored to repo root (`vendor/**`, `tests/**`, `storage/**`, `public/storage/**`). Deploy succeeded, live verified 200 + error gone.
- Lesson: never use `**/name/**` excludes for dirs whose name also appears inside resources/.

### 2026-07-15 (batch 6) — site-wide blue purge (3 parallel agents) — commit `d2f25db`
- **Vendor nav everywhere**: `<x-vendor.mobile-nav />` added to all 10 standalone vendor pages; global-nav now shows vendor tab bar on EVERY page (incl. home) for vendors, customer bar everywhere for customers.
- **Customer notifications blue icons**: DB-stored `icon_color`/`dot_color` classes are brandized in the view via a `$brandizeClasses` str_replace closure (DB values found: `bg-blue-100 text-blue-600`, `bg-blue-500`); JS dot toggle uses `bg-[#FF7DA0]` now. Shipped badges → purple across customer pages.
- **Public sweep** (agent): product-auto (19 fixes, primary-blue token), coming-soon, checkout KNET tile, guest-menu, logout-modal, alert component, privacy-policy socials, autoparts info boxes, fav.js, style.css.
- **Vendor sweep** (agent): orders status styles, withdraw/balance primary token scales → pink, notifications sidebar accents, new_product GST-calc + rgba(59,130,246) tints, returns/replacements status classes → purple/pink, recent-sold shipped badge purple.
- **Auth popup loader fix**: page-loader click/submit handlers now defer via setTimeout(0) and check `e.defaultPrevented` AFTER all handlers — no more 12s stuck loader when the auth popup intercepts /login-user links. Failsafe 12s → 8s.
- **Drawer wordmark**: logo image removed; serif italic "MJ Cheezain" + BROWSE kicker + underline accent.
- Verified: compile OK, node checks OK, customer + vendor renders 200, vendor nav present on home & panel pages. NO blue/indigo remains anywhere except sanctioned aliases (umart-blue/primary-blue both = #E85D85).

### 2026-07-15 (batch 5) — Vendor panel brand redesign — commit `4f846ab`
- **Sidebar** (`components/vendor/sidebar.blade.php`): dark gray-900/red → light brand (gradient "MJ Vendor Center" header, gradient-soft profile chip, `.v-nav-active` brand active state, View Site gradient button). z-[10001] so mobile drawer sits above bottom nav. Same props/IDs preserved (btn-side, aside, navbarToggle, setActive, noti-num).
- **New vendor mobile bottom nav** (`components/vendor/mobile-nav.blade.php`): 5 tabs (Dashboard/Products/Orders incl. returns+replacements/Withdraw/Profile), served via `customer/global-nav` (vendor branch, only on vendor/* paths).
- **Dashboard**: chart bars → brand pink, balance card gradient strip + gradient Withdraw button, stats icon chips rethemed, top-categories gradient bars, progress-bar gradient, warm `#FFF6F0` main bg.
- **Products**: tab active = `.tab-active-v` brand (JS updated), search/Add Product/table accents brand, `$` → `Rs.`.
- **new_product + create-auto** (4,600 lines): `--primary` CSS token #3b82f6 → #E85D85, full blue→pink sweep, warm bg.
- **Orders + returns/index+show + replacements/index+show**: upgraded from Tailwind 2.2.19 CSS → Play CDN (needed for arbitrary values), full blue→brand sweep, `Rs.`, gradient buttons, responsive mains.
- **Profile/edit-profile**: tab green → brand (also in `public/js/vendor_edit-profile.js`), inputs pink focus, gradient step/save buttons, toggle switch #E85D85.
- **Withdraw/balance-details/notifications**: blue→brand sweep, warm backgrounds.
- `page-loader.js` added to all 10 standalone vendor pages.
- Verified: all 11 vendor routes HTTP 200 with real vendor login; blades compile.
- Note: `/vendor/balance/details` has pre-existing `$user` undefined warning in sidebar include (view doesn't pass $user) — not introduced, works with warning.

### 2026-07-15 (batch 4) — MJ page loader + cart bar close — commit `c966c60`
- `public/js/page-loader.js`: branded page-transition overlay — spinning brand-gradient ring around "MJ" monogram, top indeterminate gradient bar, "LOADING…" animated dots. Fires instantly on link clicks / form submits that navigate (skips new-tab/hash/external/data-no-loader); hides on pageshow (bfcache-safe); 12s failsafe. Included in: layouts/structure (head), layouts/app, checkout, brands/autoparts, and customer theme component (all customer pages).
- Product page `#cartSummary`: small floating X (top-right of bar) to dismiss it.

### 2026-07-15 (batch 3) — UX fixes: header scroll, checkout, global nav
- **Mobile header scroll** (`site-header.blade.php`): now direction-aware — any upward scroll instantly restores the FULL header; collapses only while scrolling down past 70px (no more half-hidden state).
- **Product page view-cart bar** (`#cartSummary`): removed the slideUp keyframe that fought the CSS transition over `transform` (cause of half-hidden bar); `min-h` + safe-area bottom padding.
- **Store button**: removed from site-header (mobile + desktop); now a gradient "Visit Store" pill on the product page main image (bottom-left).
- **Description read-more** (`product.blade.php`): 5-line clamp + white gradient fade + pink "Read more ▾ / Read less ▴" toggle with smooth max-height expand; auto-hides for short text.
- **Guest Buy Now / checkout**: raw "please login" text with hardcoded arslan.mjcheezain.com replaced by proper `redirect('/login-user?type=customer-login&page=…')` in CartController::buy and CheckoutController::checkout (verified 302). Also killed last hardcoded domain in cart.blade.php.
- **Vendor store page** (`vendor-products.blade.php`): brand gradient store hero (avatar ring, OFFICIAL STORE chip, rating + product count pills), rethemed empty state, removed dead list-view CSS/JS.
- **Global customer bottom nav** (`components/customer/global-nav.blade.php`): customer tab bar now on EVERY page — included in layouts/structure (cart, checkout, product, cosmetics, listings, vendor store), layouts/app (home), and autoparts. Pushes `#cartSummary` above itself on mobile; adds spacer so it never covers content.
- **Checkout redesign**: compact numbered sections (1 Address / 2 Payment / 3 Contact), slim address+payment option rows with checked highlight, merged billing checkbox + order notes into contact card, compact summary rows (thumb + qty badge + one meta line), trust badges strip merged into summary card. All form IDs/JS preserved.
- Verified: blades compile; checkout/home/cart/product/vendor-store all HTTP 200; guest buy/checkout 302 redirect.

### 2026-07-15 — ss10 product cards + header avatar + product page fixes
- **Shared ss10-style product card** (`public/js/product-card.js`, `window.buildProductCard(product, imgUrl, 'slider'|'grid')`): big 16/10 image, bold title, meta row (pin icon + category • rating), Rs. price + strikethrough MRP, gradient discount ribbon, gradient **Quick View** button at card end. Hover lift injected once.
- Wired everywhere: `category_fetch.js` + `category_fetch_v2.js` (home/cosmetics/related sliders), `products/product-list.blade.php` (masonry → uniform ss10 grid), `search.js` (results grid; also killed hardcoded arslan.mjcheezain.com domain + $ currency), `vendor-products.blade.php` (list rows → ss10 grid, Blade version of same card), `products/biggest-savings.blade.php` (unused but consistent).
- `product-card.js` script tag added before category_fetch includes in: layouts/app, brands/cosmetics (×2 branches), brands/autoparts, product, product-auto.
- **Mobile header**: logged-in "Account" text → profile picture (w-8 ring) with richer dropdown (avatar+name header, Dashboard, Logout) in `components/site-header.blade.php` — covers all headers (cosmetics/transparent/main reuse it).
- **Single product page** (`product.blade.php`): red discount badge now inline right after the product title (was absolutely positioned separately); all blue accents → brand (`primary-blue` token redefined #3b82f6 → #E85D85, image borders pink-200, slider arrows #E85D85, sold-by/tab/review-avatar/info icons rethemed).
- Verified: blades compile, node --check on all 4 JS files, product/home/vendor-products pages render HTTP 200.

### 2026-07-15 — Project documentation (`docx/`)
- Created `docx/` folder with PRD.md, Architecture.md, rules.md, phases.md, design.md, memory.md

### 2026-07-15 — Customer panel mobile-app redesign — commit `dc7b0cd`
- **New shared components** (`resources/views/components/customer/`):
  - `theme.blade.php` — brand tailwind config, Poppins, `.app-card`/`.brand-gradient*` utility classes
  - `header.blade.php` — app-style header: mobile gradient (avatar, greeting, store+bell buttons, compact-on-scroll) + desktop sticky bar; optional `back` prop
  - `sidebar.blade.php` rethemed blue → brand gradient, added Continue Shopping button, fixed notifications icon
- **All 9 customer pages** rethemed + responsive (standard skeleton, `min-h-screen` body scroll, `pb-28` for bottom nav):
  - `dashboard` — full rewrite: stat cards, quick actions, desktop table + mobile order cards
  - `orders` — pill filter tabs (text-based badge filtering), app-cards, all modals rethemed, `$` → `Rs.`, removed broken `header button` alert handler
  - `wishlist` — full rewrite: app-card grid, fixed hardcoded `arslan.mjcheezain.com` image domain → relative, favToggle now removes card + refreshes
  - `addresses` — brand form inputs, JS container selectors fixed to `#address-con`
  - `profile` — brand cover/avatar chrome, 2-col mobile stats, fixed broken `<!-- Top Navigation --` comment
  - `edit-profile` — brand inputs/buttons, back-arrow header
  - `notifications` — brand date-group headers, unread `bg-pink-50`, null-guards on checkbox/`#noti-num` (were throwing)
  - `returns/create` + `returns/track` — standalone pages rethemed, `$` → `Rs.`
- Verified: `view:cache` compiles; all 9 pages server-rendered OK with real data (user_id 67) via tinker
- **Pushed & deployed 2026-07-15** (with docs commit `07b40bb`) — GitHub Actions deploy run succeeded

### 2026-07-14 — Cosmetics + footer polish — commit `837ae79`
- Cosmetics transparent header + full-screen hero; footer pages rethemed & mobile responsive

### 2026-07-14 — Mobile home UX round — commits `70a33d9`, `760ff15`, `a5dd6c8`
- Smooth compact header scroll animation (0.22s, hysteresis 70/25px, brand text left / icons right)
- Restored Category + Vendor sections to grids (user rejected sliders — locked decision)
- Hero tall cards (h-[430px]) with auto-scroll ping-pong carousel

### 2026-07-14 — Autodeploy pipeline — commits `be4ba19`…`b64ff30`
- GitHub Actions FTP deploy to Hostinger working (FTP IP 82.25.113.189, 120s timeout, IP-literal DNS skip)
- Production error handling + env-based DB config; `.env.production.example`

### Earlier — commit `a78e542` "mobile UX overhaul" and before
- Auth popup component (@once in both headers), brand theming (pink #E85D85, gradient #FF7DA0→#FFC275) on product/cart/checkout, ss4-style masonry listing + `/products/all`, global `img-fallback.js` in 39 views, autoparts Coming Soon gating

## 📌 Standing Notes (read before working)

- **Never push without explicit user approval** (push → live deploy)
- Locked home layouts: Category/Vendor = grids; hero cards = auto-scroll slider (see `rules.md §2`)
- User runs app with `php artisan serve` at http://127.0.0.1:8000; test customer data exists (user_id 67)
- Currency: `Rs.` only
- Next likely phase: **vendor panel retheme** (see `phases.md` Phase 6)
