# Design System — MJ Cheezain

## 1. Brand Colors

| Token | Hex | Usage |
|---|---|---|
| `brand` | `#E85D85` | Primary brand pink — links, active states, icons, accents |
| `brand-light` | `#FF7DA0` | Gradient start, active tab indicators, mobile-nav active |
| `brand-peach` | `#FFC275` | Gradient end |
| `light-bg` | `#FFF6F0` | Page background (warm off-white) |
| `star-yellow` | `#FFC700` | Rating stars (storefront) |
| Gold accent | Cinzel gold | Cosmetics hero "MJ" wordmark only |

**Signature gradient (buttons, headers, active pills):**
```css
background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
box-shadow: 0 6px 18px rgba(255, 125, 160, 0.30);   /* .brand-shadow */
```

**Soft tint (icon chips, section headers, hover backgrounds):**
```css
background: linear-gradient(115deg, rgba(255,125,160,.12), rgba(255,194,117,.12));  /* .brand-gradient-soft */
```

### Functional colors (keep semantic, don't brand-wash these)
| Meaning | Classes |
|---|---|
| Success / Delivered / In Stock | emerald/green (`bg-emerald-100 text-emerald-700`) |
| Warning / Shipped / Limited | amber/orange |
| Error / Cancelled / Out of Stock | red |
| Replacement flows | purple |
| Info / default order status | pink brand tint (`bg-pink-100 text-[#E85D85]`) |

## 2. Fonts

| Font | Where | Weights |
|---|---|---|
| **Poppins** | Everything (body, headings, buttons) | 300–800 |
| **Cinzel** | Luxury "MJ" wordmark on cosmetics hero only | 700 |

Loaded from Google Fonts. `theme.blade.php` sets `body { font-family: 'Poppins', sans-serif; }`.

## 3. Typography Scale (customer panel conventions)

| Element | Mobile | Desktop |
|---|---|---|
| Page title (in header) | `text-2xl font-extrabold` white | `text-xl font-bold` gray-800 |
| Header subtitle | `text-[12px] text-white/85` | `text-xs text-gray-500` |
| Section heading | `text-sm font-bold` | `text-base–xl font-bold` |
| Card title | `text-sm font-bold` | `text-base–lg font-bold` |
| Body / meta | `text-[11px] text-gray-400` | `text-sm text-gray-500` |
| Stat number | `text-xl font-extrabold` | `text-2xl font-extrabold` |
| Prices | `font-extrabold text-gray-900`, prefix `Rs.` | same |

## 4. Core Components & Shapes

All defined in `resources/views/components/customer/theme.blade.php`:

| Class | What it gives |
|---|---|
| `.app-card` | White card, `border-radius:1.25rem`, soft pink shadow `rgba(232,93,133,.07)`, hairline pink border |
| `.brand-gradient` | Signature 115° pink→peach gradient |
| `.brand-gradient-soft` | 12%-opacity tint of the gradient |
| `.brand-text-gradient` | Gradient-clipped text |
| `.brand-shadow` | Pink glow shadow for gradient buttons |
| `.page-enter` | 0.35s fade-up entrance animation for `<main>` |
| `.no-scrollbar` | Hidden scrollbar on horizontal scrollers |
| `.pb-safe` | Bottom safe-area padding (mobile nav) |
| `.sidebar-item.active` | Soft gradient bg + `#E85D85` text + 3px right border |

**Radii language:** cards `rounded-2xl`/1.25rem · inputs `rounded-xl` · buttons & pills `rounded-full` · icon chips `rounded-xl`/`rounded-full`.

**Buttons:**
- Primary: `brand-gradient brand-shadow text-white rounded-full font-semibold` + `hover:-translate-y-0.5` or `hover:opacity-90`
- Secondary/outline: white bg, `border-pink-200 text-brand rounded-xl`, `hover:bg-pink-50`
- Destructive: white bg, `border-red-200 text-red-500`, `hover:bg-red-50`

**Inputs:** `border border-pink-100 rounded-xl bg-white focus:ring-2 focus:ring-pink-300 focus:border-pink-300` (never blue focus rings).

## 5. App Chrome (customer panel)

- **Mobile header** (`<x-customer.header>`): gradient block, rounded-b-3xl, decorative white/10 circles, avatar with green online dot, time-based greeting ("Good morning 👋"), store + bell icon buttons (white/20 backdrop-blur circles), big white page title. Collapses to compact on scroll >60px (title row folds away), expands back <20px.
- **Desktop header**: sticky `bg-white/90 backdrop-blur`, title + optional back arrow left; "Continue Shopping" gradient pill, bell with badge, avatar right.
- **Sidebar** (desktop only): gradient brand header, profile chip (soft gradient), nav items with `w-5` fixed-width icons, gradient "Continue Shopping" button, red logout with confirm modal.
- **Bottom tab bar** (mobile): 5 tabs — Home, Orders, Wishlist, Alerts (unread badge), Profile. Active = `text-[#FF7DA0]` + pulsing dot.

## 6. Motion

- Page entrance: `.page-enter` fade-up 0.35s
- Header compact/expand: 0.22–0.3s ease
- Cards: `hover:-translate-y-1`/`-translate-y-0.5` + shadow lift, `active:scale-[0.98]` on mobile taps
- Hero carousel: 3.5s auto-advance ping-pong, pauses on touch/wheel, resumes after 5s
- Keep all animations under 0.4s; never animate layout on scroll except the header

## 7. Reference Screenshots

`claude-code-data/` in repo root: ss7 = tall hero cards · ss8/ss9 = grid sections (locked layouts) · ss5/ss6 = Hostinger FTP settings.
