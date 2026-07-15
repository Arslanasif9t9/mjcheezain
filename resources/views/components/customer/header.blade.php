@props(['title' => 'Dashboard', 'subtitle' => null, 'basic_info' => null, 'back' => null])

@php
    $firstName = $basic_info->first_name ?? 'there';
    $avatarSrc = $basic_info && ($basic_info->profile_image ?? null)
        ? asset('storage/customer/profile/' . $basic_info->profile_image)
        : asset('storage/default_profile.webp');

    $headerUnread = 0;
    if (Auth::check()) {
        $headerUnread = DB::table('notifications')
            ->where('user_id', Auth::id())
            ->where('is_read', 0)
            ->count();
    }

    $hour = (int) now()->format('H');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
@endphp

{{-- ============ MOBILE APP HEADER (< md) ============ --}}
<header class="md:hidden sticky top-0 z-40" id="customerAppHeader">
    <div class="brand-gradient rounded-b-3xl px-4 pt-3 pb-5 relative overflow-hidden transition-all duration-300" id="appHeaderInner">
        {{-- Decorative circles --}}
        <div class="absolute -top-10 -right-10 w-36 h-36 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="absolute top-10 -left-8 w-24 h-24 bg-white/10 rounded-full pointer-events-none"></div>

        {{-- Top row: avatar + greeting | shop + bell --}}
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center min-w-0">
                @if ($back)
                    <a href="{{ $back }}"
                       class="w-9 h-9 mr-3 flex-shrink-0 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-sm text-white active:scale-90 transition-transform">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </a>
                @else
                    <a href="/customer/profile" class="flex-shrink-0 relative">
                        <img src="{{ $avatarSrc }}" alt="Profile"
                             class="w-10 h-10 rounded-full object-cover ring-2 ring-white/70 shadow-md">
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></span>
                    </a>
                @endif
                <div class="ml-3 min-w-0">
                    <p class="text-[11px] text-white/80 font-medium leading-tight">{{ $greeting }} 👋</p>
                    <p class="text-sm font-bold text-white truncate leading-tight">{{ $firstName }} {{ $basic_info->last_name ?? '' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="/" title="Continue shopping"
                   class="w-9 h-9 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-sm text-white active:scale-90 transition-transform">
                    <i class="fas fa-store text-sm"></i>
                </a>
                <a href="/customer/notifications" title="Notifications"
                   class="w-9 h-9 flex items-center justify-center rounded-full bg-white/20 backdrop-blur-sm text-white relative active:scale-90 transition-transform">
                    <i class="fas fa-bell text-sm"></i>
                    @if ($headerUnread > 0)
                        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-white text-brand text-[10px] font-bold rounded-full flex items-center justify-center shadow">
                            {{ $headerUnread > 9 ? '9+' : $headerUnread }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        {{-- Title row --}}
        <div class="relative z-10 mt-4" id="appHeaderTitle">
            <h1 class="text-2xl font-extrabold text-white tracking-tight leading-none">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-[12px] text-white/85 mt-1 font-medium">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</header>

{{-- ============ DESKTOP HEADER (md+) ============ --}}
<header class="hidden md:flex items-center justify-between px-6 lg:px-8 py-4 bg-white/90 backdrop-blur-md border-b border-pink-100 sticky top-0 z-40">
    <div class="flex items-center min-w-0">
        @if ($back)
            <a href="{{ $back }}"
               class="w-9 h-9 mr-3 flex items-center justify-center rounded-full brand-gradient-soft text-brand hover:scale-105 transition-transform">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
        @endif
        <div class="min-w-0">
            <h1 class="text-xl font-bold text-gray-800 truncate">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-3 flex-shrink-0">
        <a href="/"
           class="hidden lg:inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-white brand-gradient brand-shadow hover:-translate-y-0.5 hover:shadow-xl transition-all">
            <i class="fas fa-store text-xs"></i> Continue Shopping
        </a>
        <a href="/customer/notifications"
           class="w-10 h-10 flex items-center justify-center rounded-full brand-gradient-soft text-brand relative hover:scale-105 transition-transform">
            <i class="fas fa-bell"></i>
            @if ($headerUnread > 0)
                <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-brand text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow">
                    {{ $headerUnread > 9 ? '9+' : $headerUnread }}
                </span>
            @endif
        </a>
        <a href="/customer/profile" class="flex items-center gap-2 group">
            <img src="{{ $avatarSrc }}" alt="Profile"
                 class="w-10 h-10 rounded-full object-cover ring-2 ring-pink-200 group-hover:ring-brand transition-all">
            <div class="hidden xl:block text-left">
                <p class="text-sm font-semibold text-gray-800 leading-tight">{{ $firstName }} {{ $basic_info->last_name ?? '' }}</p>
                <p class="text-[11px] text-brand font-medium leading-tight">Gold Member</p>
            </div>
        </a>
    </div>
</header>

{{-- Compact-on-scroll behaviour for the mobile app header --}}
<script>
    (function () {
        const header = document.getElementById('appHeaderInner');
        const titleRow = document.getElementById('appHeaderTitle');
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
