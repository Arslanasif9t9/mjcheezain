/* MJGuider — site-wide support chatbot widget.
 * History: browser localStorage only, capped at 70 messages (oldest trimmed).
 * Context: last 10 messages accompany every request so the AI remembers the chat.
 * Server is stateless — see app/Services/MjGuide/. */
(function () {
    'use strict';

    var root = document.getElementById('mjGuide');
    if (!root || root.dataset.mjgInit) return;
    root.dataset.mjgInit = '1';

    var STORE_KEY = 'mj_guide_history';
    var MAX_MESSAGES = 70;
    var CONTEXT_SIZE = 10;
    var WELCOME =
        'Hi! I am MJGuider, the assistant of your website. Agar aap ko is website ke ' +
        'mutalliq kisi bhi qisam ka masla, sawal ya madad chahiye ho to aap mujhse pooch sakte hain.';
    var SUGGESTIONS = [
        'Order kaise track karun?',
        'Return kaise hota hai?',
        'Contact info chahiye',
        'Vendor kaise banun?'
    ];

    var fab = document.getElementById('mjGuideFab');
    var back = document.getElementById('mjGuideBack');
    var win = document.getElementById('mjGuideWin');
    var dot = document.getElementById('mjGuideDot');
    var msgs = document.getElementById('mjGuideMsgs');
    var form = document.getElementById('mjGuideForm');
    var text = document.getElementById('mjGuideText');
    var send = document.getElementById('mjGuideSend');
    var clearBtn = document.getElementById('mjGuideClear');
    var closeBtn = document.getElementById('mjGuideClose');
    var confirmBar = document.getElementById('mjGuideConfirm');
    var confirmYes = document.getElementById('mjGuideConfirmYes');
    var confirmNo = document.getElementById('mjGuideConfirmNo');

    var pending = false;

    /* ---------- localStorage history ---------- */

    function loadHistory() {
        try {
            var raw = localStorage.getItem(STORE_KEY);
            if (!raw) return [];
            var arr = JSON.parse(raw);
            if (!Array.isArray(arr)) return [];
            return arr.filter(function (m) {
                return m && (m.role === 'user' || m.role === 'assistant') && typeof m.text === 'string';
            });
        } catch (e) {
            return []; // corrupt data -> fresh chat, never crash the widget
        }
    }

    function saveHistory(arr) {
        try {
            localStorage.setItem(STORE_KEY, JSON.stringify(arr.slice(-MAX_MESSAGES)));
        } catch (e) { /* storage full/blocked — chat still works for this page view */ }
    }

    function pushMessage(role, msgText) {
        var arr = loadHistory();
        arr.push({ role: role, text: msgText, t: Date.now() });
        saveHistory(arr);
    }

    /* ---------- rendering (textContent only — no HTML injection) ---------- */

    function bubble(role, msgText, extraClass) {
        var el = document.createElement('div');
        el.className = 'mjg-bub ' + (role === 'user' ? 'user' : 'bot') + (extraClass ? ' ' + extraClass : '');
        el.textContent = msgText;
        msgs.appendChild(el);
        return el;
    }

    function scrollDown() {
        msgs.scrollTop = msgs.scrollHeight;
    }

    function renderChips() {
        var wrap = document.createElement('div');
        wrap.className = 'mjg-chips';
        SUGGESTIONS.forEach(function (s) {
            var b = document.createElement('button');
            b.type = 'button';
            b.textContent = s;
            b.addEventListener('click', function () { sendMessage(s); });
            wrap.appendChild(b);
        });
        msgs.appendChild(wrap);
    }

    function renderAll() {
        msgs.textContent = '';
        var arr = loadHistory();
        if (arr.length === 0) {
            bubble('assistant', WELCOME);
            renderChips();
        } else {
            arr.forEach(function (m) { bubble(m.role, m.text); });
        }
        scrollDown();
    }

    var typingEl = null;
    function showTyping() {
        if (typingEl) return;
        typingEl = document.createElement('div');
        typingEl.className = 'mjg-typing';
        typingEl.innerHTML = '<i></i><i></i><i></i>';
        msgs.appendChild(typingEl);
        scrollDown();
    }
    function hideTyping() {
        if (typingEl && typingEl.parentNode) typingEl.parentNode.removeChild(typingEl);
        typingEl = null;
    }

    /* ---------- open / close (FAB hides while open; mobile locks page scroll) ---------- */

    var savedOverflow = '';

    function isOpen() {
        return root.classList.contains('mjg-open');
    }

    function isMobile() {
        return window.matchMedia('(max-width: 767px)').matches;
    }

    function openChat() {
        root.classList.add('mjg-open');
        win.setAttribute('aria-hidden', 'false');
        dot.hidden = true;
        hideConfirm();
        renderAll();
        if (isMobile()) {
            savedOverflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden'; // page behind the sheet must not scroll
        } else {
            text.focus();
        }
    }

    function closeChat() {
        root.classList.remove('mjg-open');
        win.setAttribute('aria-hidden', 'true');
        hideConfirm();
        document.body.style.overflow = savedOverflow;
    }

    fab.addEventListener('click', openChat);
    closeBtn.addEventListener('click', closeChat);
    back.addEventListener('click', closeChat);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen()) closeChat();
    });

    /* ---------- clear chat (inline confirm strip — no blocking dialog) ---------- */

    function hideConfirm() {
        confirmBar.hidden = true;
    }

    clearBtn.addEventListener('click', function () {
        confirmBar.hidden = !confirmBar.hidden;
    });
    confirmNo.addEventListener('click', hideConfirm);
    confirmYes.addEventListener('click', function () {
        try { localStorage.removeItem(STORE_KEY); } catch (e) { /* ignore */ }
        hideConfirm();
        renderAll();
    });

    /* ---------- sending ---------- */

    function setPending(state) {
        pending = state;
        text.disabled = state;
        send.disabled = state;
    }

    function sendMessage(value) {
        value = (value || '').trim();
        if (!value || pending) return;

        // context = the last 10 messages BEFORE this new one
        var context = loadHistory().slice(-CONTEXT_SIZE).map(function (m) {
            return { role: m.role, text: m.text };
        });

        var hadHistory = loadHistory().length > 0;
        pushMessage('user', value);
        if (!hadHistory) renderAll(); // first message: drop the suggestion chips
        else bubble('user', value);
        scrollDown();
        setPending(true);
        showTyping();

        fetch(root.dataset.endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': root.dataset.csrf
            },
            body: JSON.stringify({ message: value, context: context })
        })
            .then(function (res) {
                if (res.status === 429) throw new Error('rate');
                if (!res.ok) throw new Error('http');
                return res.json();
            })
            .then(function (data) {
                hideTyping();
                var reply = data && typeof data.reply === 'string' && data.reply.trim() !== ''
                    ? data.reply
                    : 'Maazrat, jawab nahi mil saka. Dobara try karein.';
                pushMessage('assistant', reply);
                bubble('assistant', reply);
                scrollDown();
                if (!isOpen()) dot.hidden = false;
            })
            .catch(function (err) {
                hideTyping();
                var note = err && err.message === 'rate'
                    ? 'Aap bohat tez messages bhej rahe hain — thora ruk kar dobara try karein.'
                    : 'Connection problem — internet check kar ke dobara try karein.';
                bubble('assistant', note, 'mjg-note'); // note bubbles are not saved to history
                scrollDown();
            })
            .then(function () {
                setPending(false);
                if (isOpen() && !isMobile()) text.focus();
            });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var value = text.value;
        text.value = '';
        sendMessage(value);
    });

    /* ---------- avoid the product-page #cartSummary bar ---------- */

    var cartBar = document.getElementById('cartSummary');
    if (cartBar) {
        var syncLift = function () {
            root.classList.toggle('mjg-lift', !cartBar.classList.contains('translate-y-full'));
        };
        syncLift();
        new MutationObserver(syncLift).observe(cartBar, { attributes: true, attributeFilter: ['class'] });
    }
})();
