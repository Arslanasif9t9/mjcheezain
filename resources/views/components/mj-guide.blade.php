{{-- MJ Guide — site-wide support chatbot (all users incl. guests).
     Injected via customer/global-nav + customer/vendor mobile-navs; @once
     collapses duplicate includes so it renders exactly once per page. --}}
@once
<div id="mjGuide" data-csrf="{{ csrf_token() }}" data-endpoint="{{ url('/mj-guide/message') }}">

    {{-- Floating button --}}
    <button type="button" id="mjGuideFab" aria-label="Open MJ Guide chat">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <span>MJ Guide</span>
        <i id="mjGuideDot" hidden></i>
    </button>

    {{-- Chat window --}}
    <div id="mjGuideWin" aria-hidden="true">
        <div class="mjg-head">
            <div class="mjg-ava">MJ</div>
            <div class="mjg-title">
                <b>MJ Guide</b>
                <small><span class="mjg-online"></span> Online</small>
            </div>
            <button type="button" id="mjGuideClear" title="Clear chat" aria-label="Clear chat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
            <button type="button" id="mjGuideClose" title="Close" aria-label="Close chat">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
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
            transition: transform .18s ease;
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

        /* ---- Window ---- */
        #mjGuideWin {
            position: fixed; right: 24px; bottom: 92px; z-index: 9991;
            width: 380px; max-width: calc(100vw - 32px);
            height: min(560px, 75vh);
            background: #fff; border-radius: 20px; overflow: hidden;
            display: flex; flex-direction: column;
            box-shadow: 0 18px 50px rgba(0,0,0,.22);
            opacity: 0; visibility: hidden; transform: translateY(14px) scale(.98);
            transition: opacity .22s ease, transform .22s ease, visibility .22s;
        }
        #mjGuide.mjg-open #mjGuideWin { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
        #mjGuide.mjg-open #mjGuideFab { animation: none; }

        .mjg-head {
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(120deg, #FF7DA0, #FFC275);
            color: #fff; padding: 13px 14px; flex: none;
        }
        .mjg-ava {
            width: 38px; height: 38px; border-radius: 50%; flex: none;
            background: rgba(255,255,255,.25); border: 2px solid #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-style: italic; font-family: Georgia, serif; font-size: 14px;
        }
        .mjg-title { flex: 1; min-width: 0; line-height: 1.25; }
        .mjg-title b { display: block; font-size: 15px; }
        .mjg-title small { font-size: 11px; opacity: .95; display: flex; align-items: center; gap: 5px; }
        .mjg-online { width: 7px; height: 7px; border-radius: 50%; background: #4ade80; display: inline-block; }
        #mjGuideClear, #mjGuideClose {
            background: rgba(255,255,255,.18); border: none; color: #fff; cursor: pointer;
            width: 32px; height: 32px; border-radius: 10px; flex: none;
            display: flex; align-items: center; justify-content: center;
            transition: background .15s ease;
        }
        #mjGuideClear:hover, #mjGuideClose:hover { background: rgba(255,255,255,.32); }
        #mjGuideClear svg, #mjGuideClose svg { width: 16px; height: 16px; }
        #mjGuideClear.mjg-arm { background: #ef4444; }

        .mjg-body {
            flex: 1; overflow-y: auto; background: #fdf7f4;
            padding: 14px 12px; display: flex; flex-direction: column; gap: 10px;
            overscroll-behavior: contain;
        }
        .mjg-bub {
            max-width: 86%; padding: 10px 14px; border-radius: 16px;
            font-size: 15.5px; line-height: 1.55; white-space: pre-wrap; word-break: break-word;
        }
        .mjg-bub.bot {
            background: #fff; color: #1f2937; align-self: flex-start;
            border: 1px solid #ffe4ec; border-bottom-left-radius: 4px;
            box-shadow: 0 2px 6px rgba(0,0,0,.04);
        }
        .mjg-bub.user {
            background: linear-gradient(120deg, #FF7DA0, #FFC275); color: #fff;
            align-self: flex-end; border-bottom-right-radius: 4px;
        }
        .mjg-bub.mjg-note { font-size: 13px; color: #9ca3af; background: transparent; border: none; box-shadow: none; align-self: center; text-align: center; }
        .mjg-typing {
            display: flex; gap: 4px; align-self: flex-start;
            background: #fff; border: 1px solid #ffe4ec; border-radius: 16px;
            border-bottom-left-radius: 4px; padding: 13px 16px;
        }
        .mjg-typing i { width: 6px; height: 6px; border-radius: 50%; background: #FF7DA0; animation: mjgB 1s infinite; }
        .mjg-typing i:nth-child(2) { animation-delay: .15s; }
        .mjg-typing i:nth-child(3) { animation-delay: .3s; }
        @keyframes mjgB { 0%, 60%, 100% { transform: translateY(0); } 30% { transform: translateY(-5px); } }

        .mjg-input { display: flex; gap: 8px; padding: 12px; background: #fff; border-top: 1px solid #f3e2e9; flex: none; }
        #mjGuideText {
            flex: 1; min-width: 0; border: 1px solid #f0d4de; border-radius: 999px;
            padding: 10px 16px; font-family: inherit; font-size: 16px; color: #1f2937; outline: none;
        }
        #mjGuideText:focus { border-color: #E85D85; box-shadow: 0 0 0 3px rgba(232,93,133,.12); }
        #mjGuideText:disabled { background: #faf6f7; color: #9ca3af; }
        #mjGuideSend {
            width: 42px; height: 42px; border-radius: 50%; border: none; cursor: pointer; flex: none;
            background: linear-gradient(120deg, #FF7DA0, #FFC275); color: #fff;
            display: flex; align-items: center; justify-content: center;
            transition: transform .15s ease, opacity .15s ease;
        }
        #mjGuideSend:hover { transform: scale(1.06); }
        #mjGuideSend:disabled { opacity: .55; transform: none; cursor: default; }
        #mjGuideSend svg { width: 17px; height: 17px; margin-left: 2px; }

        /* ---- Mobile: sit above bottom tab bars, bottom-sheet window ---- */
        @media (max-width: 767px) {
            #mjGuideFab { right: 14px; bottom: 5.9rem; padding: 12px 17px; font-size: 13px; }
            #mjGuideWin {
                right: 8px; left: 8px; bottom: 9.2rem; width: auto;
                height: min(520px, calc(100vh - 12rem));
            }
        }
    </style>
    <script src="{{ asset('js/mj-guide.js') }}" defer></script>
</div>
@endonce
