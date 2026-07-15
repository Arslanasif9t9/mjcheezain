# Memory — Work Log & Current State

> Living document. Update after every meaningful work session: what was completed (with date + commit), and what is currently being worked on.

## 🔄 Currently Working On

- *(nothing in progress — last task fully completed)*

## ✅ Completed

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
