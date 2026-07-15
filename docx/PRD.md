# PRD — MJ Cheezain (mjcheezain.com)

## 1. What We Are Building

**MJ Cheezain** is a multivendor e-commerce platform for the Pakistani market. Multiple vendors list and sell their products through one storefront; customers browse, order, track, review, return and replace products. The platform currently focuses on **Cosmetics** as its primary category, with **Auto Parts** planned as a second vertical (currently in "Coming Soon" mode).

The whole experience — especially on mobile — should feel like a **native shopping app**, not a website: gradient headers, bottom tab navigation, card-based layouts, smooth micro-animations.

- **Production URL:** https://repo.mjcheezain.com (Hostinger shared hosting)
- **Repo:** github.com/Arslanasif9t9/mjcheezain (push to `main` = autodeploy via FTP)
- **Currency:** PKR (always shown as `Rs.` — never `$`)

## 2. Target Users

| User | Who they are | What they need |
|---|---|---|
| **Customer** | Pakistani shoppers, mostly on mobile phones | Browse/search products, cart & checkout, order tracking, wishlist, addresses, ratings, returns/replacements — all in Urdu-market-friendly, app-like UI |
| **Vendor** | Small/medium sellers (cosmetics brands, shops) | Product management (create/edit/delete with images), order fulfilment, withdrawals, profile & store settings, return/replacement handling |
| **Admin** | Platform operator | Oversee vendors, products, orders, categories from an admin dashboard |

Primary device is a **low-to-mid-range Android phone** on average mobile data — mobile-first design and light pages matter.

## 3. Core Features

### 3.1 Storefront (public)
- Home page: hero slider (auto-scroll ping-pong carousel), Shop-by-Category grid, Featured Vendors grid, product row sliders (snap-scroll with buttons), masonry product listing (`/products/all`)
- Cosmetics landing with transparent header + full-screen hero (gold Cinzel "MJ" wordmark)
- Product detail page with images, ratings, related products
- Search (`/search-products`), biggest savings, browse by category
- Auth popup (login/signup modal available from both headers)
- Footer pages (about/policies) — brand themed, responsive

### 3.2 Customer Account (after login) — redesigned July 2026
- **Dashboard** — stat cards (total/active/completed orders), quick actions, recent orders (table on desktop, cards on mobile)
- **My Orders** — pill filter tabs, per-item status, track order timeline, cancel, rate product (stars + review), request replacement, request return
- **Wishlist** — saved products with stock status, sort/filter, remove
- **Addresses** — CRUD + set default shipping address
- **Profile** — cover/profile photo upload, stats, about info
- **Profile Settings** — edit name/email/phone/birthday/bio/photo
- **Notifications** — grouped by date, auto mark-as-read
- **Returns** — create return request (reason, details, photo upload) and track it through a 10-step timeline
- Shared app-like chrome: gradient mobile header (compact on scroll), desktop sidebar, bottom tab bar on mobile

### 3.3 Vendor Panel
- Dashboard, products CRUD (with images, faults), orders, withdraw/balance, profile edit (basic info, store detail, address), replacement & return request handling

### 3.4 Admin Panel
- Auth + dashboard (in `resources/views/Admin`), platform management

### 3.5 Commerce Flow
- Cart → Checkout → Order placement → fulfilment statuses (`Order Placed → Processing → Shipped → Delivered`, or `Cancelled`) → post-delivery actions (rate / replace / return)

### 3.6 MJGuider — AI Support Chatbot *(planned — see `mj-guide.md` / `plan.html`)*
- Floating "MJGuider" button (bottom-right, every page) opening a small brand-themed chat window with larger readable text (15–16px)
- Answers ONLY MJ Cheezain questions: contact info, login/registration help, orders, returns, vendor/customer feature guidance, page how-tos — refuses off-topic
- Available to everyone (guest, customer, vendor — no login needed); chat history persists across refresh/close in browser localStorage (max 70 messages, NO database changes)
- Backend: Gemini API first, automatic **silent** fallback to Grok API on quota/failure (user never notices)

## 4. Non-Goals (for now)
- Online payment gateway (orders are COD-style; no card/wallet integration yet)
- Native mobile apps (the web IS the app — PWA-like feel)
- Multi-language UI (English UI; market is Pakistan)
- Auto Parts vertical (kept behind `@if(false)` Coming Soon until ready)

## 5. Success Criteria
- Mobile experience indistinguishable from a shopping app (bottom nav, gradient headers, no horizontal scroll, tap-friendly targets)
- Every page follows the single brand theme (see `design.md`)
- Order lifecycle fully self-serve: a customer never needs to call to track/return/replace
- Push to `main` deploys to production without manual steps
