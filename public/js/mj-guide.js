/* MJ Guide — site-wide support chatbot widget.
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
        'Hi! I am MJ Guide, the assistant of your website. Agar aap ko is website ke ' +
        'mutalliq kisi bhi qisam ka masla, sawal ya madad chahiye ho to aap mujhse pooch sakte hain.';

    var fab = document.getElementById('mjGuideFab');
    var win = document.getElementById('mjGuideWin');
    var dot = document.getElementById('mjGuideDot');
    var msgs = document.getElementById('mjGuideMsgs');
    var form = document.getElementById('mjGuideForm');
    var text = document.getElementById('mjGuideText');
    var send = document.getElementById('mjGuideSend');
    var clearBtn = document.getElementById('mjGuideClear');
    var closeBtn = document.getElementById('mjGuideClose');

    var pending = false;
    var clearArmTimer = null;

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

    function renderAll() {
        msgs.textContent = '';
        var arr = loadHistory();
        if (arr.length === 0) {
            bubble('assistant', WELCOME);
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

    /* ---------- open / close ---------- */

    function isOpen() {
        return root.classList.contains('mjg-open');
    }

    function openChat() {
        root.classList.add('mjg-open');
        win.setAttribute('aria-hidden', 'false');
        dot.hidden = true;
        renderAll();
        if (window.matchMedia('(min-width: 768px)').matches) text.focus();
    }

    function closeChat() {
        root.classList.remove('mjg-open');
        win.setAttribute('aria-hidden', 'true');
    }

    fab.addEventListener('click', function () {
        if (isOpen()) closeChat(); else openChat();
    });
    closeBtn.addEventListener('click', closeChat);

    /* ---------- clear chat (two-tap confirm, no blocking dialog) ---------- */

    clearBtn.addEventListener('click', function () {
        if (clearBtn.classList.contains('mjg-arm')) {
            clearTimeout(clearArmTimer);
            clearBtn.classList.remove('mjg-arm');
            try { localStorage.removeItem(STORE_KEY); } catch (e) { /* ignore */ }
            renderAll();
        } else {
            clearBtn.classList.add('mjg-arm');
            clearBtn.title = 'Tap again to clear chat';
            clearArmTimer = setTimeout(function () {
                clearBtn.classList.remove('mjg-arm');
                clearBtn.title = 'Clear chat';
            }, 3000);
        }
    });

    /* ---------- sending ---------- */

    function setPending(state) {
        pending = state;
        text.disabled = state;
        send.disabled = state;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (pending) return;
        var value = text.value.trim();
        if (!value) return;

        // context = the last 10 messages BEFORE this new one
        var context = loadHistory().slice(-CONTEXT_SIZE).map(function (m) {
            return { role: m.role, text: m.text };
        });

        pushMessage('user', value);
        bubble('user', value);
        text.value = '';
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
                if (isOpen() && window.matchMedia('(min-width: 768px)').matches) text.focus();
            });
    });
})();
