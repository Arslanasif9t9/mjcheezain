# Rules — What To Do, What To Avoid

Ground rules for anyone (human or AI) working in this repo.

## 1. Libraries & Dependencies

**Use (already in the project):**
- Laravel 12 built-ins (query builder, Blade, validation, storage)
- Tailwind CSS via Play CDN (`https://cdn.tailwindcss.com`) with per-page `tailwind.config` or the shared `<x-customer.theme />`
- Font Awesome 6 (CDN) for icons
- Google Fonts: Poppins (body), Cinzel (MJ luxury wordmark)
- Vanilla JS + `fetch()` for AJAX

**Avoid:**
- ❌ Adding npm/Vite build steps — production has no build pipeline; everything must work served as-is
- ❌ New JS frameworks (React/Vue/Alpine/jQuery) — the codebase is plain JS; keep it that way
- ❌ New composer packages without a strong reason (shared hosting; `vendor/` is not auto-deployed)
- ❌ Old Tailwind 2.2.19 CSS link (`cdn.jsdelivr.net/npm/tailwindcss@2.2.19`) — legacy; use `<x-customer.theme />` on customer pages instead
- ❌ CDN-loading anything replaceable by the shared theme component

## 2. UI / Theme Rules

- **Single source of truth:** brand colors, fonts, `app-card`, `brand-gradient` etc. live in `resources/views/components/customer/theme.blade.php` (customer panel) and per-page tailwind config on storefront pages. See `design.md`.
- Currency is **`Rs.`** everywhere. Never render `$`.
- Mobile-first: every new page/section must be checked at 360–414px width. No horizontal body scroll ever; wide tables get `overflow-x-auto` wrappers or a mobile card alternative.
- Customer pages must follow the standard skeleton in `Architecture.md §4` (theme → sidebar → header → main → mobile-nav).
- **Layout decisions the user has locked (do NOT change):**
  - Shop-by-Category and Featured Vendors on home = **grids**, never sliders
  - Hero bottom cards = tall Amazon-style cards **with** auto-scroll ping-pong carousel
  - Product row sections = horizontal snap-scroll sliders with buttons
- Keep `img-fallback.js` as the first script in every page's `<head>`.
- Images: relative `/storage/...` paths or `asset()` — never hardcode `https://arslan.mjcheezain.com` or any domain.

## 3. Backend Rules

- Follow the existing pattern: controller methods fetch with `DB::table()`, return `view()` with `compact()`, and JSON endpoints return `response()->json()`.
- Every customer view must receive `$basic_info`; every AJAX page needs `<meta name="csrf-token">` and requests must send `X-CSRF-TOKEN`.
- Don't rename existing routes/endpoints — Blade JS calls them by hardcoded path (`/customer/get_orders`, `/favorites/toggle`, `/wishlist/get`, `/rate-product`, etc.).
- Auth boundaries: customer routes under `Route::prefix('customer')`, vendor under `vendor`, admin under admin group — never expose one role's data to another.

## 4. Error Handling

- **JS:** every `fetch()` chain needs a `.catch()` (or try/catch with async/await) that surfaces a user-visible message via the page's `showNotification()` helper — not just `console.error`.
- **JS:** guard DOM lookups that can legitimately be null (`if (el) ...`) — several pages render elements conditionally (e.g. `#noti-num` only when unread > 0). One uncaught null deref kills every later listener in the same handler.
- **PHP:** production must never show stack traces — friendly error handling was added in commit `be4ba19`; keep `APP_DEBUG=false` on the server.
- **Empty states:** every list (orders, wishlist, addresses, notifications) must render a designed empty state with a call-to-action, never a blank area.
- Validate on the server even when the client validates.

## 5. Boundaries for AI (Claude / any assistant)

- ❌ **Never `git push` without the user explicitly saying so.** Push = production deploy. Commit locally and wait.
- ❌ Never use EIA / expertintuitiveadvisor MCP tools in this project.
- ❌ Don't touch the deploy workflow (`.github/workflows/deploy.yml`) or server `.env` unless the task is specifically about deployment.
- ❌ Don't convert the locked layouts (rules §2) back to other patterns, even if it "looks better".
- ❌ Don't delete/replace working JS features while rethemeing — preserve modals, tracking timelines, rating flows exactly; retheme colors/classes only.
- ✅ Preferred workflow: read the full file → targeted edits (or clean rewrite when the page is small) → `php artisan view:cache` to check Blade compiles → render pages with real data (tinker) or browser → commit with a descriptive message.
- ✅ Keep `docx/memory.md` updated after each meaningful work session (what was completed, what's in progress).
- ✅ Speak to the user in Roman Urdu/English mix (as they do); write code and docs in English.

## 6. Git Conventions

- Branch: work directly on `main` locally (user's habit), but **commit ≠ push**.
- Commit messages: `feat:` / `fix:` / `chore:` prefix + one-line summary + bullet body for multi-file changes.
- Never amend published commits; never force-push.
