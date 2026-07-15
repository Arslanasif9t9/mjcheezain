/**
 * MJ Cheezain page-transition loader.
 * The instant a user clicks a link (or submits a form) that navigates to a
 * new page, a branded overlay appears: a spinning brand-gradient ring around
 * an "MJ" monogram + an animated gradient bar on top. This gives immediate
 * feedback that the tap registered while the next page loads.
 *
 * Self-contained: injects its own CSS + DOM. Include once on any page:
 *   <script src="/js/page-loader.js"></script>
 *
 * Skips: new-tab links, downloads, hash jumps, javascript: links, modified
 * clicks (ctrl/cmd/shift/middle), and anything marked data-no-loader.
 */
(function () {
    if (window.__mjPageLoaderInstalled) return;
    window.__mjPageLoaderInstalled = true;

    /* ---------- styles ---------- */
    var css =
        '#mj-page-loader{position:fixed;inset:0;z-index:2147483000;display:flex;flex-direction:column;align-items:center;justify-content:center;' +
            'background:rgba(255,246,240,.82);-webkit-backdrop-filter:blur(3px);backdrop-filter:blur(3px);' +
            'opacity:0;pointer-events:none;transition:opacity .18s ease;}' +
        '#mj-page-loader.mj-show{opacity:1;pointer-events:auto;}' +

        /* top indeterminate gradient bar */
        '#mj-page-loader .mj-bar{position:absolute;top:0;left:0;height:3px;width:100%;overflow:hidden;}' +
        '#mj-page-loader .mj-bar::before{content:"";position:absolute;top:0;left:-40%;height:100%;width:40%;border-radius:99px;' +
            'background:linear-gradient(90deg,#FF7DA0,#FFC275);animation:mjBarSlide 1s ease-in-out infinite;}' +
        '@keyframes mjBarSlide{0%{left:-40%}50%{left:60%}100%{left:100%}}' +

        /* MJ badge with spinning gradient ring */
        '#mj-page-loader .mj-ring{position:relative;width:74px;height:74px;border-radius:50%;display:flex;align-items:center;justify-content:center;}' +
        '#mj-page-loader .mj-ring::before{content:"";position:absolute;inset:0;border-radius:50%;padding:3px;' +
            'background:conic-gradient(from 0deg,#FF7DA0,#FFC275,rgba(255,125,160,.08),#FF7DA0);' +
            '-webkit-mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude;' +
            'animation:mjSpin .9s linear infinite;}' +
        '@keyframes mjSpin{to{transform:rotate(360deg)}}' +
        '#mj-page-loader .mj-badge{width:58px;height:58px;border-radius:50%;background:#fff;box-shadow:0 8px 24px rgba(232,93,133,.22);' +
            'display:flex;align-items:center;justify-content:center;}' +
        '#mj-page-loader .mj-badge span{font-family:Cinzel,Georgia,serif;font-weight:700;font-size:22px;letter-spacing:.5px;' +
            'background:linear-gradient(115deg,#E85D85,#FF9F5A);-webkit-background-clip:text;background-clip:text;color:transparent;}' +

        /* loading text with animated dots */
        '#mj-page-loader .mj-text{margin-top:14px;font-size:12px;font-weight:600;letter-spacing:2px;color:#E85D85;text-transform:uppercase;' +
            'font-family:Poppins,ui-sans-serif,system-ui,sans-serif;}' +
        '#mj-page-loader .mj-text::after{content:"";animation:mjDots 1.2s steps(4,end) infinite;}' +
        '@keyframes mjDots{0%{content:""}25%{content:"."}50%{content:".."}75%{content:"..."}}';

    var style = document.createElement('style');
    style.textContent = css;
    document.head.appendChild(style);

    /* ---------- DOM ---------- */
    var overlay = document.createElement('div');
    overlay.id = 'mj-page-loader';
    overlay.innerHTML =
        '<div class="mj-bar"></div>' +
        '<div class="mj-ring"><div class="mj-badge"><span>MJ</span></div></div>' +
        '<div class="mj-text">Loading</div>';

    function mount() { document.body.appendChild(overlay); }
    if (document.body) mount();
    else document.addEventListener('DOMContentLoaded', mount);

    var hideTimer = null;

    function show() {
        overlay.classList.add('mj-show');
        // Failsafe: never trap the user if navigation silently fails
        clearTimeout(hideTimer);
        hideTimer = setTimeout(hide, 8000);
    }

    function hide() {
        clearTimeout(hideTimer);
        overlay.classList.remove('mj-show');
    }

    window.mjShowPageLoader = show;
    window.mjHidePageLoader = hide;

    /* ---------- triggers ---------- */
    document.addEventListener('click', function (e) {
        // Only plain left-clicks
        if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        var a = e.target.closest && e.target.closest('a[href]');
        if (!a) return;
        if (a.hasAttribute('data-no-loader')) return;
        if (a.target && a.target !== '_self') return;          // new tab
        if (a.hasAttribute('download')) return;

        var href = a.getAttribute('href') || '';
        if (!href || href.startsWith('#') || href.startsWith('javascript:') ||
            href.startsWith('mailto:') || href.startsWith('tel:')) return;

        // Same-page hash navigation
        try {
            var url = new URL(a.href, location.href);
            if (url.origin !== location.origin) return;         // external site
            if (url.pathname === location.pathname && url.search === location.search && url.hash) return;
        } catch (_) { return; }

        // Defer until AFTER every other click handler has run: some links are
        // intercepted by JS (e.g. /login-user links open the auth POPUP instead
        // of navigating). Only show the loader if nothing called preventDefault.
        setTimeout(function () {
            if (!e.defaultPrevented) show();
        }, 0);
    }, true);

    // Real form navigations (not AJAX forms — those call preventDefault)
    document.addEventListener('submit', function (e) {
        var f = e.target;
        if (f.hasAttribute && f.hasAttribute('data-no-loader')) return;
        if (f.target && f.target !== '_self') return;
        setTimeout(function () {
            if (!e.defaultPrevented) show();
        }, 0);
    }, true);

    // JS-driven navigations (Quick View buttons etc. use window.location.href)
    window.addEventListener('beforeunload', show);

    // Coming back via back/forward cache — make sure it's gone
    window.addEventListener('pageshow', function () { hide(); });
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') hide();
    });
})();
