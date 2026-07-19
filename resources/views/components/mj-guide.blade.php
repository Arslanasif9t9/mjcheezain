{{-- MJGuider — site-wide support chatbot (all users incl. guests).
     Injected via customer/global-nav + customer/vendor mobile-navs + login page;
     @once collapses duplicate includes so it renders exactly once per page. --}}
@once
<div id="mjGuide" data-csrf="{{ csrf_token() }}" data-endpoint="{{ url('/mj-guide/message') }}">

    {{-- Floating button (hides while the chat is open) --}}
    <button type="button" id="mjGuideFab" aria-label="Open MJGuider chat">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <span>MJGuider</span>
        <i id="mjGuideDot" hidden></i>
    </button>

    {{-- Mobile backdrop --}}
    <div id="mjGuideBack" aria-hidden="true"></div>

    {{-- Chat window --}}
    <div id="mjGuideWin" role="dialog" aria-label="MJGuider chat" aria-hidden="true">
        <div class="mjg-head">
            <div class="mjg-ava">MJ<em></em></div>
            <div class="mjg-title">
                <b>MJGuider</b>
                <small>Online &middot; MJ Cheezain Assistant</small>
            </div>
            <button type="button" id="mjGuideClear" title="Clear chat" aria-label="Clear chat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
            <button type="button" id="mjGuideClose" title="Close" aria-label="Close chat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="mjg-confirm" id="mjGuideConfirm" hidden>
            <span>Poori chat clear kar dein?</span>
            <button type="button" id="mjGuideConfirmYes">Clear karein</button>
            <button type="button" id="mjGuideConfirmNo">Cancel</button>
        </div>
        <div class="mjg-body" id="mjGuideMsgs"></div>
        <form class="mjg-input" id="mjGuideForm" autocomplete="off">
            <input type="text" id="mjGuideText" maxlength="1000" placeholder="Apna sawal likhein…" aria-label="Type your question">
            <button type="submit" id="mjGuideSend" aria-label="Send">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </form>
    </div>

    <style>
        #mjGuide { font-family: 'Poppins', -apple-system, 'Segoe UI', sans-serif; }
        #mjGuide *, #mjGuide *::before, #mjGuide *::after { box-sizing: border-box; }
        #mjGuide button { -webkit-tap-highlight-color: transparent; }

        /* ---- Floating button ---- */
        #mjGuideFab {
            position: fixed; right: 24px; bottom: 24px; z-index: 9990;
            display: flex; align-items: center; gap: 8px;
            background: linear-gradient(120deg, #FF7DA0, #FFC275);
            color: #fff; border: none; cursor: pointer;
            padding: 13px 20px; border-radius: 999px;
            font-family: inherit; font-size: 14px; font-weight: 700; letter-spacing: .2px;
            box-shadow: 0 10px 26px rgba(232, 93, 133, .45);
            animation: mjgPulse 2.6s infinite;
            transition: transform .2s ease, opacity .2s ease, visibility .2s;
        }
        #mjGuideFab:hover { transform: translateY(-2px); }
        #mjGuideFab svg { width: 20px; height: 20px; }
        #mjGuideDot {
            position: absolute; top: -2px; right: -2px; width: 13px; height: 13px;
            background: #ef4444; border: 2px solid #fff; border-radius: 50%;
        }
        @keyframes mjgPulse {
            0%, 100% { box-shadow: 0 10px 26px rgba(232,93,133,.45); }
            50% { box-shadow: 0 10px 26px rgba(232,93,133,.45), 0 0 0 12px rgba(232,93,133,.08); }
        }
        /* chat open -> button hides */
        #mjGuide.mjg-open #mjGuideFab { opacity: 0; visibility: hidden; transform: scale(.6); animation: none; }

        /* page has a visible #cartSummary bar -> lift the button above it */
        #mjGuide.mjg-lift #mjGuideFab { bottom: 6.5rem; }

        /* ---- Mobile backdrop (hidden on desktop) ---- */
        #mjGuideBack {
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(31, 20, 26, .42);
            opacity: 0; visibility: hidden; transition: opacity .28s ease, visibility .28s;
        }

        /* ---- Window (desktop: opens in the button's corner) ----
           z 10000: above bottom navs (9999) & auth popup; below vendor drawer (10001) + page-loader */
        #mjGuideWin {
            position: fixed; right: 24px; bottom: 24px; z-index: 10000;
            width: 392px; max-width: calc(100vw - 32px);
            height: min(600px, 80vh);
            background: #fff; border-radius: 22px; overflow: hidden;
            display: flex; flex-direction: column;
            box-shadow: 0 24px 60px rgba(31, 20, 26, .28);
            opacity: 0; visibility: hidden; transform: translateY(16px) scale(.97);
            transition: opacity .24s ease, transform .24s cubic-bezier(.32,.72,.24,1), visibility .24s;
        }
        #mjGuide.mjg-open #mjGuideWin { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }

        /* ---- Header ---- */
        .mjg-head {
            display: flex; align-items: center; gap: 11px;
            background: linear-gradient(120deg, #FF7DA0, #FFC275);
            color: #fff; padding: 14px; flex: none;
        }
        .mjg-ava {
            position: relative; width: 40px; height: 40px; border-radius: 50%; flex: none;
            background: rgba(255,255,255,.25); border: 2px solid #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-style: italic; font-family: Georgia, serif; font-size: 14px;
        }
        .mjg-ava em {
            position: absolute; right: -1px; bottom: -1px; width: 11px; height: 11px;
            background: #4ade80; border: 2px solid #fff; border-radius: 50%;
        }
        .mjg-title { flex: 1; min-width: 0; line-height: 1.3; }
        .mjg-title b { display: block; font-size: 15.5px; letter-spacing: .2px; }
        .mjg-title small { font-size: 11px; opacity: .95; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }
        #mjGuideClear, #mjGuideClose {
            background: rgba(255,255,255,.18); border: none; color: #fff; cursor: pointer;
            width: 34px; height: 34px; border-radius: 11px; flex: none;
            display: flex; align-items: center; justify-content: center;
            transition: background .15s ease;
        }
        #mjGuideClear:hover, #mjGuideClose:hover { background: rgba(255,255,255,.34); }
        #mjGuideClear svg, #mjGuideClose svg { width: 15px; height: 15px; }

        /* ---- Clear-chat confirm strip ---- */
        .mjg-confirm {
            display: flex; align-items: center; gap: 8px; flex: none; flex-wrap: wrap;
            background: #FFF1F5; border-bottom: 1px solid #ffd9e5;
            padding: 10px 14px; font-size: 13px; color: #83274a; font-weight: 500;
        }
        .mjg-confirm span { flex: 1; min-width: 120px; }
        .mjg-confirm button {
            border: none; cursor: pointer; border-radius: 999px;
            font-family: inherit; font-size: 12.5px; font-weight: 600; padding: 6px 14px;
        }
        #mjGuideConfirmYes { background: #ef4444; color: #fff; }
        #mjGuideConfirmYes:hover { background: #dc2626; }
        #mjGuideConfirmNo { background: #fff; color: #6b7280; border: 1px solid #e5e7eb; }

        /* ---- Messages ---- */
        .mjg-body {
            flex: 1; overflow-y: auto; background: #fdf7f4;
            padding: 16px 14px; display: flex; flex-direction: column; gap: 10px;
            overscroll-behavior: contain;
            scrollbar-width: thin; scrollbar-color: #F5BFD2 transparent;
        }
        .mjg-body::-webkit-scrollbar { width: 5px; }
        .mjg-body::-webkit-scrollbar-track { background: transparent; }
        .mjg-body::-webkit-scrollbar-thumb { background: #F5BFD2; border-radius: 99px; }
        .mjg-body::-webkit-scrollbar-thumb:hover { background: #E85D85; }

        .mjg-bub {
            max-width: 86%; padding: 10px 14px; border-radius: 16px;
            font-size: 15.5px; line-height: 1.55; white-space: pre-wrap; word-break: break-word;
            animation: mjgIn .22s ease both;
        }
        @keyframes mjgIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
        .mjg-bub.bot {
            background: #fff; color: #1f2937; align-self: flex-start;
            border: 1px solid #ffe4ec; border-bottom-left-radius: 5px;
            box-shadow: 0 2px 6px rgba(0,0,0,.04);
        }
        .mjg-bub.user {
            background: linear-gradient(120deg, #FF7DA0, #FFC275); color: #fff;
            align-self: flex-end; border-bottom-right-radius: 5px;
            box-shadow: 0 4px 12px rgba(232,93,133,.22);
        }
        .mjg-bub.mjg-note { font-size: 13px; color: #9ca3af; background: transparent; border: none; box-shadow: none; align-self: center; text-align: center; }

        /* ---- Product recommendations ----
           Chat-native list cards: full bubble width, no horizontal scrolling, and
           fully self-contained (widget pages ship different Tailwind flavors, so
           nothing here may depend on utility classes). */
        .mjg-recs {
            display: flex; flex-direction: column; gap: 8px; flex: none;
            align-self: stretch; max-width: 100%; min-width: 0; margin-top: 2px;
        }
        .mjg-recs-label {
            display: flex; align-items: center; gap: 6px; padding-left: 2px;
            font-size: 11px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
            color: #E85D85;
        }
        .mjg-recs-label::before {
            content: ''; width: 14px; height: 2px; border-radius: 2px;
            background: linear-gradient(90deg, #FF7DA0, #FFC275);
        }

        .mjg-pc {
            display: flex; align-items: center; gap: 11px; padding: 9px; min-width: 0;
            background: #fff; border: 1px solid #ffe4ec; border-radius: 15px;
            text-decoration: none; color: inherit;
            box-shadow: 0 2px 8px rgba(31, 20, 26, .05);
            animation: mjgIn .28s ease both;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .mjg-pc:hover { transform: translateY(-2px); border-color: #ffc9db; box-shadow: 0 10px 22px rgba(232, 93, 133, .16); }
        .mjg-pc:active { transform: translateY(0) scale(.995); }

        .mjg-pc-thumb {
            position: relative; flex: none; width: 76px; height: 76px;
            border-radius: 12px; overflow: hidden; background: #FFF4F7;
        }
        .mjg-pc-img { width: 100%; height: 100%; object-fit: cover; display: block; opacity: 1; }
        .mjg-pc-off {
            position: absolute; left: 4px; bottom: 4px; padding: 1px 6px; border-radius: 999px;
            font-size: 9.5px; font-weight: 800; color: #fff; letter-spacing: .2px;
            background: linear-gradient(115deg, #FF7DA0, #FFC275);
            box-shadow: 0 2px 6px rgba(232, 93, 133, .35);
        }

        .mjg-pc-info { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 3px; }
        .mjg-pc-name {
            font-size: 14px; font-weight: 600; color: #1f2937; line-height: 1.35;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden; word-break: break-word;
        }
        .mjg-pc:hover .mjg-pc-name { color: #E85D85; }
        .mjg-pc-sub { display: flex; align-items: center; gap: 7px; min-width: 0; font-size: 11.5px; color: #9ca3af; }
        .mjg-pc-cat { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0; }
        .mjg-pc-star { flex: none; color: #b8893a; font-weight: 600; white-space: nowrap; }
        .mjg-pc-price { display: flex; align-items: baseline; gap: 6px; flex-wrap: wrap; }
        .mjg-pc-price b { font-size: 15.5px; font-weight: 800; color: #111827; white-space: nowrap; }
        .mjg-pc-price s { font-size: 11.5px; color: #b9bfc9; white-space: nowrap; }

        .mjg-pc-go {
            flex: none; width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(120deg, #FF7DA0, #FFC275); color: #fff;
            box-shadow: 0 4px 10px rgba(232, 93, 133, .3);
        }
        .mjg-pc-go svg { width: 14px; height: 14px; }

        .mjg-recs-more {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 9px; border-radius: 12px; border: 1px dashed #f6cdda;
            background: #fffafc; color: #E85D85; text-decoration: none;
            font-size: 12.5px; font-weight: 600;
            transition: background .15s ease, border-color .15s ease;
        }
        .mjg-recs-more:hover { background: #fff1f5; border-color: #E85D85; }

        /* nothing matched — never dead-end the user */
        .mjg-empty {
            align-self: stretch; background: #fff; border: 1px solid #ffe4ec; border-radius: 15px;
            padding: 16px 14px; text-align: center;
            box-shadow: 0 2px 8px rgba(31, 20, 26, .05); animation: mjgIn .28s ease both;
        }
        .mjg-empty-ic {
            width: 42px; height: 42px; margin: 0 auto 9px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(120deg, #FFE9F0, #FFF0DF); color: #E85D85;
        }
        .mjg-empty-ic svg { width: 20px; height: 20px; }
        .mjg-empty-t { font-size: 13.5px; font-weight: 600; color: #1f2937; }
        .mjg-empty-s { font-size: 12px; color: #9ca3af; margin-top: 3px; }
        .mjg-empty-chips { display: flex; flex-wrap: wrap; gap: 7px; justify-content: center; margin-top: 11px; }
        .mjg-empty-chips a {
            border: 1px solid #f6cdda; background: #fff; color: #E85D85; text-decoration: none;
            border-radius: 999px; padding: 6px 13px; font-size: 12.5px; font-weight: 500;
            transition: background .15s ease, color .15s ease;
        }
        .mjg-empty-chips a:hover { background: #E85D85; color: #fff; }

        /* quick-suggestion chips (empty chat) */
        .mjg-chips { display: flex; flex-wrap: wrap; gap: 8px; align-self: flex-start; animation: mjgIn .25s ease both; }
        .mjg-chips button {
            border: 1px solid #f6cdda; background: #fff; color: #E85D85; cursor: pointer;
            border-radius: 999px; padding: 7px 14px;
            font-family: inherit; font-size: 13px; font-weight: 500;
            transition: background .15s ease, color .15s ease;
        }
        .mjg-chips button:hover { background: #E85D85; color: #fff; }

        .mjg-typing {
            display: flex; gap: 4px; align-self: flex-start;
            background: #fff; border: 1px solid #ffe4ec; border-radius: 16px;
            border-bottom-left-radius: 5px; padding: 13px 16px;
        }
        .mjg-typing i { width: 6px; height: 6px; border-radius: 50%; background: #FF7DA0; animation: mjgB 1s infinite; }
        .mjg-typing i:nth-child(2) { animation-delay: .15s; }
        .mjg-typing i:nth-child(3) { animation-delay: .3s; }
        @keyframes mjgB { 0%, 60%, 100% { transform: translateY(0); } 30% { transform: translateY(-5px); } }

        /* ---- Input (compact) ---- */
        .mjg-input {
            display: flex; align-items: center; gap: 8px; flex: none;
            padding: 10px 12px; background: #fff; border-top: 1px solid #f3e2e9;
        }
        #mjGuideText {
            flex: 1; min-width: 0; height: 42px;
            border: 1px solid #f0d4de; border-radius: 999px;
            padding: 0 16px; font-family: inherit; font-size: 16px; color: #1f2937; outline: none;
            background: #fdfafb;
        }
        #mjGuideText:focus { border-color: #E85D85; background: #fff; box-shadow: 0 0 0 3px rgba(232,93,133,.12); }
        #mjGuideText:disabled { background: #faf6f7; color: #9ca3af; }
        #mjGuideSend {
            width: 40px; height: 40px; border-radius: 50%; border: none; cursor: pointer; flex: none;
            background: linear-gradient(120deg, #FF7DA0, #FFC275); color: #fff;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(232,93,133,.3);
            transition: transform .15s ease, opacity .15s ease;
        }
        #mjGuideSend:hover { transform: scale(1.08); }
        #mjGuideSend:active { transform: scale(.94); }
        #mjGuideSend:disabled { opacity: .55; transform: none; cursor: default; }
        #mjGuideSend svg { width: 16px; height: 16px; margin-left: 2px; }

        /* =========================================================
           Mobile — FULL-SCREEN app view
           ========================================================= */
        @media (max-width: 767px) {
            #mjGuideFab { right: 14px; bottom: 5.9rem; padding: 12px 17px; font-size: 13px; }
            #mjGuide.mjg-lift #mjGuideFab { bottom: 9.6rem; }
            /* page has an MJGuider tab in the bottom nav -> FAB not needed on mobile
               (guests have no bottom nav, so THEY keep the FAB) */
            #mjGuide.mjg-nav #mjGuideFab { display: none; }

            #mjGuideBack { display: block; }
            #mjGuide.mjg-open #mjGuideBack { opacity: 1; visibility: visible; }

            /* full-screen app page (covers bottom nav too) */
            #mjGuideWin {
                top: 0; left: 0; right: 0; bottom: 0; width: 100%; max-width: 100%;
                height: 100vh; height: 100dvh;
                border-radius: 0;
                opacity: 1; transform: translateY(104%); /* slide fully off-screen */
                transition: transform .34s cubic-bezier(.32,.72,.24,1), visibility .34s;
            }
            #mjGuide.mjg-open #mjGuideWin { transform: translateY(0); }

            .mjg-head { padding: calc(12px + env(safe-area-inset-top)) 14px 12px; }
            .mjg-ava { width: 42px; height: 42px; }

            .mjg-input { padding-bottom: calc(10px + env(safe-area-inset-bottom)); }
        }
    </style>
    {{-- filemtime cache-buster: stable across requests (browser-cacheable), busts automatically on deploy --}}
    <script src="{{ asset('js/mj-guide.js') }}?v={{ @filemtime(public_path('js/mj-guide.js')) ?: 1 }}" defer></script>
</div>
@endonce
