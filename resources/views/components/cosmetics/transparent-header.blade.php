@props(['user' => null, 'vendor' => null, 'profile' => null, 'dashboardPage' => null, 'imgPath' => null])

{{-- Cosmetics-only transparent header: overlays the hero video (fixed, see-through
     at the top of the page) and turns solid once the user scrolls, so text stays
     readable over normal page content. Other pages keep x-cosmetics.header. --}}

<div id="cosmetics-header-wrap">
    <x-site-header :user="$user" :vendor="$vendor" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
</div>

<style>
    /* The header floats over the hero video instead of pushing it down */
    #cosmetics-header-wrap header {
        position: fixed !important;
        transition: background .35s ease, border-color .35s ease;
    }

    /* ---------- Transparent state (top of page, video visible behind) ---------- */
    #cosmetics-header-wrap:not(.cos-solid) header {
        background: linear-gradient(to bottom, rgba(0, 0, 0, .55) 0%, rgba(0, 0, 0, .25) 65%, rgba(0, 0, 0, 0) 100%) !important;
        border-color: transparent !important;
        box-shadow: none !important;
    }

    /* Desktop nav top-level links/buttons go white (dropdown panels stay white
       with dark text, so only direct children are targeted) */
    #cosmetics-header-wrap:not(.cos-solid) header nav > a,
    #cosmetics-header-wrap:not(.cos-solid) header nav > div > button {
        color: #fff;
    }
    #cosmetics-header-wrap:not(.cos-solid) header nav > a:hover,
    #cosmetics-header-wrap:not(.cos-solid) header nav > div > button:hover {
        color: rgba(255, 255, 255, .75);
    }

    /* Desktop guest Sign Up / Login buttons */
    #cosmetics-header-wrap:not(.cos-solid) header .group > button {
        color: #fff;
    }
    #cosmetics-header-wrap:not(.cos-solid) header .group > button:hover {
        color: rgba(255, 255, 255, .75);
    }

    /* Desktop search box becomes frosted glass over the video */
    #cosmetics-header-wrap:not(.cos-solid) header div[class*="max-w-lg"] {
        background: rgba(255, 255, 255, .15);
        box-shadow: none;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    #cosmetics-header-wrap:not(.cos-solid) header div[class*="max-w-lg"] input {
        color: #fff;
    }
    #cosmetics-header-wrap:not(.cos-solid) header div[class*="max-w-lg"] input::placeholder {
        color: rgba(255, 255, 255, .7);
    }
    #cosmetics-header-wrap:not(.cos-solid) header div[class*="max-w-lg"] svg {
        color: rgba(255, 255, 255, .85);
    }

    /* ---------- Cosmetics-only compact mobile header ---------- */
    /* The full-width search bar row is removed entirely; search lives as an
       icon in the single top row, so the header never grows a second line. */
    #cosmetics-header-wrap #mobile-search-wrapper {
        display: none !important;
    }
    /* Keep the search icon always visible (site-header JS only shows it on
       scroll via inline styles — !important wins over those), except while
       the inline search field is open. */
    #cosmetics-header-wrap:not(.cos-search-open) #mobile-search-trigger {
        max-width: 50px !important;
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    /* Mobile: hamburger, search trigger and auth links go white over the video */
    #cosmetics-header-wrap:not(.cos-solid) #mobile-menu-btn,
    #cosmetics-header-wrap:not(.cos-solid) #mobile-search-trigger,
    #cosmetics-header-wrap:not(.cos-solid) #mobile-auth-wrapper button {
        color: #fff;
    }
    #cosmetics-header-wrap:not(.cos-solid) #mobile-auth-wrapper span {
        color: rgba(255, 255, 255, .6);
    }
</style>

<script>
    // Toggle solid header once the page scrolls away from the hero video
    (function () {
        const wrap = document.getElementById('cosmetics-header-wrap');
        if (!wrap) return;
        let solid = null;
        function updateCosmeticsHeader() {
            const next = (window.scrollY || document.documentElement.scrollTop || 0) > 40;
            if (next === solid) return;
            solid = next;
            wrap.classList.toggle('cos-solid', next);
        }
        window.addEventListener('scroll', updateCosmeticsHeader, { passive: true });
        updateCosmeticsHeader();

        // Track the inline mobile search open/close so the search icon can hide
        // while the input is expanded (the icon is otherwise forced visible).
        if (typeof window.expandMobileSearch === 'function') {
            const origExpand = window.expandMobileSearch;
            window.expandMobileSearch = function () {
                wrap.classList.add('cos-search-open');
                origExpand();
            };
        }
        if (typeof window.collapseMobileSearch === 'function') {
            const origCollapse = window.collapseMobileSearch;
            window.collapseMobileSearch = function () {
                wrap.classList.remove('cos-search-open');
                origCollapse();
            };
        }
    })();
</script>
