{{-- Vendor mobile app header (< md) — mirrors components/customer/header.blade.php.
     Desktop headers stay page-specific; this component only renders on mobile.
     Also ships the shared app-style utility classes so every vendor page gets them. --}}
@props(['title' => 'Dashboard', 'subtitle' => null, 'back' => null])

@php
    $vhUser = Auth::user();
    $vhInfo = DB::table('vendor_basic_info')->where('user_id', $vhUser->user_id ?? null)->first();
    $vhName = $vhInfo->full_name ?? $vhUser->full_name ?? 'Vendor';
    $vhAvatar = $vhInfo && ($vhInfo->profile_picture ?? null)
        ? asset('storage/vendor/profile/' . $vhInfo->profile_picture)
        : asset('storage/default_profile.webp');

    $vhUnread = DB::table('notifications')
        ->where('user_id', $vhUser->user_id ?? null)
        ->where('is_read', 0)
        ->count();

    $vhHour = (int) now()->format('H');
    $vhGreeting = $vhHour < 12 ? 'Good morning' : ($vhHour < 17 ? 'Good afternoon' : 'Good evening');
@endphp

<style>
    /* Shared app-style utilities (same names as the customer panel) */
    .brand-gradient { background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); }
    .brand-gradient-soft { background: linear-gradient(115deg, rgba(255, 125, 160, 0.12) 0%, rgba(255, 194, 117, 0.12) 100%); }
    .brand-text-gradient {
        background: linear-gradient(115deg, #E85D85 0%, #FF9F5A 100%);
        -webkit-background-clip: text; background-clip: text; color: transparent;
    }
    .brand-shadow { box-shadow: 0 6px 18px rgba(255, 125, 160, 0.30); }
    .app-card {
        background: #fff; border-radius: 1.25rem;
        box-shadow: 0 2px 12px rgba(232, 93, 133, 0.07);
        border: 1px solid rgba(232, 93, 133, 0.08);
    }
    @keyframes pageFadeUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .page-enter { animation: pageFadeUp 0.35s ease-out both; }
    body { -webkit-tap-highlight-color: transparent; }

    /* The header's own menu button replaces the old floating hamburger on mobile */
    @media (max-width: 767px) { #btn-side { display: none !important; } }
</style>

{{-- ============ MOBILE APP HEADER (< md) ============ --}}
<header class="md:hidden sticky top-0 z-40" id="vendorAppHeader">
    <div class="brand-gradient rounded-b-3xl px-4 pt-3 pb-5 relative overflow-hidden transition-all duration-300" id="vAppHeaderInner">
        {{-- Decorative circles --}}
        <div class="absolute -top-10 -right-10 w-36 h-36 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="absolute top-10 -left-8 w-24 h-24 bg-white/10 rounded-full pointer-events-none"></div>

        {{-- Top row: avatar + greeting | site + bell + menu --}}
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center min-w-0">
                @if ($back)
                    <a href="{{ $back }}"
                       class="w-9 h-9 mr-3 flex-shrink-0 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-sm text-white active:scale-90 transition-transform">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </a>
                @else
                    <a href="{{ route('vendor.profile') }}" class="flex-shrink-0 relative">
                        <img src="{{ $vhAvatar }}" alt="Profile"
                             class="w-10 h-10 rounded-full object-cover ring-2 ring-white/70 shadow-md">
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></span>
                    </a>
                @endif
                <div class="ml-3 min-w-0">
                    <p class="text-[11px] text-white/80 font-medium leading-tight">{{ $vhGreeting }} 👋</p>
                    <p class="text-sm font-bold text-white truncate leading-tight">{{ $vhName }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="/" title="View site"
                   class="w-9 h-9 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-sm text-white active:scale-90 transition-transform">
                    <i class="fas fa-store text-sm"></i>
                </a>
                <a href="{{ route('vendor.notifications') }}" title="Notifications"
                   class="w-9 h-9 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-sm text-white relative active:scale-90 transition-transform">
                    <i class="fas fa-bell text-sm"></i>
                    @if ($vhUnread > 0)
                        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-white text-[#E85D85] text-[10px] font-bold rounded-full flex items-center justify-center shadow">
                            {{ $vhUnread > 9 ? '9+' : $vhUnread }}
                        </span>
                    @endif
                </a>
                <button type="button" title="Menu" onclick="if (typeof navbarToggle === 'function') navbarToggle()"
                        class="w-9 h-9 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-sm text-white border-0 active:scale-90 transition-transform">
                    <i class="fas fa-bars text-sm"></i>
                </button>
            </div>
        </div>

        {{-- Title row --}}
        <div class="relative z-10 mt-4" id="vAppHeaderTitle">
            <h1 class="text-2xl font-extrabold text-white tracking-tight leading-none">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-[12px] text-white/85 mt-1 font-medium">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</header>

{{-- Compact-on-scroll behaviour --}}
<script>
    (function () {
        const header = document.getElementById('vAppHeaderInner');
        const titleRow = document.getElementById('vAppHeaderTitle');
        if (!header || !titleRow) return;

        let compact = false;
        const setCompact = (on) => {
            if (on === compact) return;
            compact = on;
            if (on) {
                titleRow.style.maxHeight = '0px';
                titleRow.style.opacity = '0';
                titleRow.style.marginTop = '0px';
                titleRow.style.overflow = 'hidden';
                header.classList.remove('pb-5');
                header.classList.add('pb-3');
            } else {
                titleRow.style.maxHeight = '80px';
                titleRow.style.opacity = '1';
                titleRow.style.marginTop = '1rem';
                header.classList.add('pb-5');
                header.classList.remove('pb-3');
            }
        };
        titleRow.style.transition = 'max-height 0.28s ease, opacity 0.22s ease, margin-top 0.28s ease';
        titleRow.style.maxHeight = '80px';

        window.addEventListener('scroll', () => {
            const y = window.scrollY || document.documentElement.scrollTop;
            if (y > 60) setCompact(true);
            else if (y < 20) setCompact(false);
        }, { passive: true });
    })();
</script>
