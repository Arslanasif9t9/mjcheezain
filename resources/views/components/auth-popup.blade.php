{{--
    Premium Auth Popup (ss2-style bottom-to-center sheet)
    ------------------------------------------------------
    Self-contained: own CSS (ap- prefix, no Tailwind dependency) + JS.
    It NEVER talks to the backend itself — it only intercepts clicks on
    existing /login-user links and re-offers them as pretty buttons that
    navigate to the exact same URLs (type + page params preserved).
    If anything at all goes wrong, the original link navigation happens
    normally, so the login flow can never break.
--}}

<style>
    #ap-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(20, 10, 15, 0.55);
        -webkit-backdrop-filter: blur(4px);
        backdrop-filter: blur(4px);
        z-index: 9998;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    }
    #ap-backdrop.ap-open {
        opacity: 1;
        pointer-events: auto;
    }

    #ap-panel {
        position: fixed;
        left: 50%;
        top: 50%;
        width: min(92vw, 400px);
        max-height: 92vh;
        max-height: 92dvh;
        overflow-y: auto;
        overflow-x: hidden;
        background: #ffffff;
        border-radius: 26px;
        z-index: 9999;
        box-shadow: 0 30px 70px rgba(60, 15, 35, 0.35);
        /* start: below the screen, slightly shrunk — animates up to dead center */
        transform: translate(-50%, 65vh) scale(0.96);
        opacity: 0;
        pointer-events: none;
        transition: transform 0.6s cubic-bezier(0.34, 1.4, 0.64, 1), opacity 0.35s ease;
        font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    #ap-panel::-webkit-scrollbar { display: none; }
    #ap-panel.ap-open {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
        pointer-events: auto;
    }

    /* ===== Gradient hero (brand theme) ===== */
    .ap-hero {
        position: relative;
        background: linear-gradient(135deg, #FF7DA0 0%, #FFC275 100%);
        padding: 22px 20px 96px 20px;
        text-align: center;
        overflow: hidden;
    }
    .ap-hero::after {
        /* soft white curve at the bottom of the gradient, like the login page */
        content: '';
        position: absolute;
        left: -10%;
        right: -10%;
        bottom: -34px;
        height: 64px;
        background: #ffffff;
        border-radius: 50% 50% 0 0;
    }
    .ap-close {
        position: absolute;
        top: 12px;
        right: 14px;
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        color: #3d2430;
        font-size: 16px;
        font-weight: 700;
        line-height: 1;
        cursor: pointer;
        transition: background 0.25s ease, transform 0.25s ease;
        z-index: 2;
    }
    .ap-close:hover { background: rgba(255, 255, 255, 0.45); transform: rotate(90deg); }

    .ap-brand {
        font-family: 'PT Serif', 'Playfair Display', Georgia, serif;
        font-style: italic;
        font-weight: 800;
        font-size: 26px;
        color: #2B2130;
        letter-spacing: 0.02em;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.35);
    }
    .ap-tagline {
        margin-top: 6px;
        font-size: 12.5px;
        font-weight: 500;
        color: rgba(43, 33, 48, 0.85);
    }
    .ap-tagline b {
        display: block;
        font-size: 17px;
        font-weight: 700;
        color: #2B2130;
        margin-top: 2px;
        line-height: 1.35;
    }

    /* ===== Globe illustration straddling the gradient/white seam ===== */
    .ap-globe-wrap {
        position: relative;
        margin-top: -78px;
        display: flex;
        justify-content: center;
        pointer-events: none;
        z-index: 1;
    }
    .ap-globe {
        width: 168px;
        height: auto;
        filter: drop-shadow(0 16px 20px rgba(60, 20, 35, 0.25));
    }
    @media (prefers-reduced-motion: no-preference) {
        #ap-panel.ap-open .ap-globe {
            animation: ap-float 6s cubic-bezier(0.22, 1, 0.36, 1) infinite;
        }
    }
    @keyframes ap-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-7px); }
    }

    /* ===== Actions ===== */
    .ap-body {
        padding: 14px 22px calc(22px + env(safe-area-inset-bottom)) 22px;
        text-align: center;
    }
    .ap-btn {
        display: block;
        width: 100%;
        padding: 14px 18px;
        border-radius: 999px;
        font-size: 13.5px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.3s ease, background-position 0.5s ease;
        margin-top: 12px;
    }
    .ap-btn-primary {
        color: #ffffff;
        background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
        background-size: 160% 160%;
        background-position: 0% 50%;
        box-shadow: 0 6px 18px rgba(255, 125, 160, 0.35);
    }
    .ap-btn-primary:hover {
        background-position: 100% 50%;
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(232, 93, 133, 0.4);
    }
    .ap-btn-outline {
        color: #E85D85;
        background: #ffffff;
        border: 2px solid #FF9DB8;
    }
    .ap-btn-outline:hover {
        background: #FFF1F5;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 125, 160, 0.2);
    }
    .ap-btn:active { transform: translateY(0) scale(0.98); }

    /* staggered entrance for the action buttons */
    .ap-stagger {
        opacity: 0;
        transform: translateY(14px);
        transition: opacity 0.45s ease, transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
    }
    #ap-panel.ap-open .ap-stagger { opacity: 1; transform: translateY(0); }
    #ap-panel.ap-open .ap-stagger.d1 { transition-delay: 0.18s; }
    #ap-panel.ap-open .ap-stagger.d2 { transition-delay: 0.26s; }
    #ap-panel.ap-open .ap-stagger.d3 { transition-delay: 0.34s; }
    #ap-panel.ap-open .ap-stagger.d4 { transition-delay: 0.42s; }

    .ap-or {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #b9a8b0;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.14em;
        margin: 16px 0 4px 0;
    }
    .ap-or::before, .ap-or::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f0e4e9;
    }
    .ap-vendor {
        font-size: 12px;
        color: #8a7885;
        margin-top: 10px;
    }
    .ap-vendor a {
        color: #E85D85;
        font-weight: 700;
        text-decoration: none;
    }
    .ap-vendor a:hover { text-decoration: underline; }
</style>

<div id="ap-backdrop" aria-hidden="true"></div>

<div id="ap-panel" role="dialog" aria-modal="true" aria-label="Sign in to MJ Cheezain">
    <div class="ap-hero">
        <button type="button" class="ap-close" onclick="apClose()" aria-label="Close">&#10005;</button>
        <div class="ap-brand">mjcheezain.com</div>
        <p class="ap-tagline">
            Pakistan&rsquo;s local &amp; global shopping with
            <b>buyer protection &amp; great savings</b>
        </p>
    </div>

    <!-- Globe with Pakistan highlighted -->
    <div class="ap-globe-wrap">
        <svg class="ap-globe" viewBox="0 0 240 240" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Globe showing Pakistan">
            <defs>
                <radialGradient id="apSphere" cx="34%" cy="28%" r="80%">
                    <stop offset="0%" stop-color="#FFFFFF"/>
                    <stop offset="45%" stop-color="#FFEDE0"/>
                    <stop offset="78%" stop-color="#FFD1A8"/>
                    <stop offset="100%" stop-color="#F2A868"/>
                </radialGradient>
                <linearGradient id="apPak" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FF8FAE"/>
                    <stop offset="100%" stop-color="#D63A63"/>
                </linearGradient>
                <linearGradient id="apPin" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FF8FAE"/>
                    <stop offset="100%" stop-color="#D63A63"/>
                </linearGradient>
                <clipPath id="apClip"><circle cx="120" cy="128" r="86"/></clipPath>
            </defs>

            <!-- ground contact shadow -->
            <ellipse cx="120" cy="226" rx="86" ry="11" fill="#5A2036" opacity="0.16"/>

            <!-- orbit ring -->
            <ellipse cx="120" cy="128" rx="116" ry="42" fill="none" stroke="#FFFFFF" stroke-width="3" opacity="0.65" transform="rotate(-12 120 128)"/>
            <circle cx="230" cy="106" r="5" fill="#FFFFFF" opacity="0.95" transform="rotate(-12 120 128)"/>

            <!-- sphere -->
            <circle cx="120" cy="128" r="86" fill="url(#apSphere)"/>
            <g clip-path="url(#apClip)">
                <!-- graticule -->
                <g opacity="0.4">
                    <ellipse cx="120" cy="128" rx="34" ry="86" fill="none" stroke="#E8926A" stroke-width="1.6"/>
                    <ellipse cx="120" cy="128" rx="66" ry="86" fill="none" stroke="#E8926A" stroke-width="1.6"/>
                    <ellipse cx="120" cy="95" rx="86" ry="24" fill="none" stroke="#E8926A" stroke-width="1.6"/>
                    <ellipse cx="120" cy="128" rx="86" ry="30" fill="none" stroke="#E8926A" stroke-width="1.6"/>
                    <ellipse cx="120" cy="164" rx="86" ry="22" fill="none" stroke="#E8926A" stroke-width="1.6"/>
                </g>

                <!-- neighbouring landmasses (soft, stylised) -->
                <g fill="#FFC9B0" opacity="0.85">
                    <!-- Central Asia / Afghanistan-Iran plateau (upper-left) -->
                    <path d="M34,86 Q60,66 92,72 Q112,76 116,92 Q108,104 88,104 Q60,106 44,98 Q32,94 34,86 Z"/>
                    <!-- Arabian peninsula (lower-left) -->
                    <path d="M46,142 Q60,128 78,134 Q88,140 84,156 Q76,174 60,176 Q44,172 42,158 Q42,148 46,142 Z"/>
                    <!-- India (lower-right) -->
                    <path d="M138,118 Q160,110 176,120 Q188,130 182,148 Q174,170 158,186 Q148,192 144,178 Q136,152 134,134 Q134,124 138,118 Z"/>
                    <!-- Himalayas / China edge (upper-right) -->
                    <path d="M148,78 Q176,68 198,84 Q206,94 196,102 Q176,108 156,102 Q142,96 148,78 Z"/>
                </g>

                <!-- PAKISTAN — highlighted in brand pink (stylised but recognisable) -->
                <path d="M96,106
                         L104,96 L114,90 L122,82 L132,80 L138,86 L134,94 L126,98
                         L130,108 L124,118 L118,126 L114,138 L108,146
                         L96,148 L86,144 L92,136 L86,130 L92,122 L88,114 Z"
                      fill="url(#apPak)" stroke="#FFFFFF" stroke-width="2.5" stroke-linejoin="round"/>

                <!-- rim shading + specular highlight -->
                <ellipse cx="158" cy="170" rx="82" ry="80" fill="#8A3A1E" opacity="0.14"/>
                <ellipse cx="84" cy="82" rx="34" ry="18" fill="#FFFFFF" opacity="0.55" transform="rotate(-18 84 82)"/>
            </g>
            <circle cx="120" cy="128" r="86" fill="none" stroke="#FFFFFF" stroke-width="2" opacity="0.6"/>

            <!-- location pin dropped on Pakistan -->
            <g transform="translate(112,66)">
                <path d="M0,-26 C12,-26 21,-17 21,-6 C21,10 0,28 0,28 C0,28 -21,10 -21,-6 C-21,-17 -12,-26 0,-26 Z" fill="url(#apPin)" stroke="#FFFFFF" stroke-width="2"/>
                <circle cx="0" cy="-6" r="7" fill="#FFFFFF"/>
            </g>
        </svg>
    </div>

    <div class="ap-body">
        <a id="ap-signin" href="/login-user?type=customer-login" class="ap-btn ap-btn-primary ap-stagger d1">Continue with Sign In</a>
        <a id="ap-signup" href="/login-user?type=customer-signup" class="ap-btn ap-btn-outline ap-stagger d2">Create New Account</a>
        <div class="ap-or ap-stagger d3">OR</div>
        <p class="ap-vendor ap-stagger d4">
            Vendor Portal &mdash;
            <a id="ap-vendor-login" href="/login-user?type=vendor-login">Sign In</a>
            &nbsp;/&nbsp;
            <a id="ap-vendor-signup" href="/login-user?type=vendor-signup">Register</a>
        </p>
    </div>
</div>

<script>
(function () {
    var backdrop = document.getElementById('ap-backdrop');
    var panel = document.getElementById('ap-panel');
    if (!backdrop || !panel) return;

    // Current page path in the same format request()->path() produces
    // (no leading slash, "/" for home) so the backend redirect keeps working.
    function currentPage() {
        var p = window.location.pathname || '/';
        if (p !== '/' && p.charAt(0) === '/') p = p.substring(1);
        return p + (window.location.search || '');
    }

    // Build a /login-user URL guaranteeing both `type` and `page` params.
    function buildUrl(type, page) {
        return '/login-user?type=' + encodeURIComponent(type) + '&page=' + encodeURIComponent(page || currentPage());
    }

    // Point every popup button at the right URL, deriving role/page from
    // the link that was clicked (falls back safely when params are missing).
    function setTargets(href) {
        var type = 'customer-login';
        var page = '';
        try {
            var u = new URL(href, window.location.origin);
            type = u.searchParams.get('type') || type;
            page = u.searchParams.get('page') || '';
        } catch (e) { /* malformed href — defaults win */ }

        var role = type.indexOf('vendor') !== -1 ? 'vendor' : 'customer';
        document.getElementById('ap-signin').href = buildUrl(role + '-login', page);
        document.getElementById('ap-signup').href = buildUrl(role + '-signup', page);
        document.getElementById('ap-vendor-login').href = buildUrl('vendor-login', page);
        document.getElementById('ap-vendor-signup').href = buildUrl('vendor-signup', page);
    }

    window.apOpen = function (href) {
        setTargets(href || buildUrl('customer-login', ''));
        backdrop.classList.add('ap-open');
        panel.classList.add('ap-open');
        document.body.style.overflow = 'hidden';
    };

    window.apClose = function () {
        backdrop.classList.remove('ap-open');
        panel.classList.remove('ap-open');
        document.body.style.overflow = '';
    };

    backdrop.addEventListener('click', window.apClose);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') window.apClose();
    });

    // Intercept every existing /login-user link on the page and show the
    // popup instead. Wrapped in try/catch: on ANY error the original link
    // navigates normally, so the auth flow can never break.
    document.addEventListener('click', function (e) {
        var a = e.target && e.target.closest ? e.target.closest('a[href*="login-user"]') : null;
        if (!a || panel.contains(a)) return;
        try {
            e.preventDefault();
            window.apOpen(a.getAttribute('href'));
        } catch (err) {
            window.location.href = a.href;
        }
    });
})();
</script>
