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
        'Perfume recommend karo',
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

    /* ---------- product card payloads (server-sent recommendations) ----------
       buildProductCard concatenates id / rating / image RAW into HTML, so every
       value is coerced/validated here — on the fetch path AND on storage load
       (localStorage could be hand-edited). name/category are esc()'d by the builder. */

    function sanitizeProducts(list) {
        if (!Array.isArray(list)) return [];
        var out = [];
        for (var i = 0; i < list.length && out.length < 3; i++) {
            var p = list[i];
            if (!p) continue;
            var id = parseInt(p.id, 10);
            if (!isFinite(id) || id <= 0) continue;
            var image = typeof p.image === 'string' && /^(https?:\/\/|\/)[^"'<>\s]+$/.test(p.image)
                ? p.image
                : '/img/default_img.png';
            var rating = parseFloat(p.rating);
            out.push({
                id: id,
                name: String(p.name || 'Product').slice(0, 120),
                image: image,
                selling_price: Number(p.selling_price) || 0,
                mrp: Number(p.mrp) || 0,
                rating: isFinite(rating) && rating > 0 ? Math.round(rating * 10) / 10 : null,
                category: String(p.category || '').slice(0, 60)
            });
        }
        return out;
    }

    /* ---------- localStorage history ---------- */

    function loadHistory() {
        try {
            var raw = localStorage.getItem(STORE_KEY);
            if (!raw) return [];
            var arr = JSON.parse(raw);
            if (!Array.isArray(arr)) return [];
            return arr.filter(function (m) {
                if (!m || (m.role !== 'user' && m.role !== 'assistant') || typeof m.text !== 'string') return false;
                if (m.products) {
                    m.products = sanitizeProducts(m.products);
                    if (!m.products.length) delete m.products;
                }
                return true;
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

    function pushMessage(role, msgText, products, kind) {
        var arr = loadHistory();
        var entry = { role: role, text: msgText, t: Date.now() };
        if (products && products.length) entry.products = products;
        if (kind) entry.kind = kind;
        arr.push(entry);
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

    /* ---------- product recommendations ----------
       Built with DOM APIs + textContent only, so vendor-controlled names/categories
       can never inject markup. Layout is a vertical list: it fits the 392px window
       and the mobile sheet without any horizontal scrolling. */

    function el(tag, cls, parent) {
        var node = document.createElement(tag);
        if (cls) node.className = cls;
        if (parent) parent.appendChild(node);
        return node;
    }

    function svg(pathD, viewBox) {
        var s = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        s.setAttribute('viewBox', viewBox || '0 0 24 24');
        s.setAttribute('fill', 'none');
        s.setAttribute('stroke', 'currentColor');
        s.setAttribute('stroke-width', '2.2');
        s.setAttribute('stroke-linecap', 'round');
        s.setAttribute('stroke-linejoin', 'round');
        var p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        p.setAttribute('d', pathD);
        s.appendChild(p);
        return s;
    }

    /* 3510 -> "3,510" */
    function money(n) {
        return String(Math.round(n)).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function productCard(p, index) {
        // same customer-price maths as every card on the site (selling price + 17% GST)
        var price = p.selling_price * 1.17;
        var mrp = Number(p.mrp) || 0;
        var hasDiscount = mrp > price;
        var off = hasDiscount ? Math.round(((mrp - price) / mrp) * 100) : 0;

        var card = el('a', 'mjg-pc');
        card.href = '/product/' + p.id;
        card.style.animationDelay = (index * 70) + 'ms';

        var thumb = el('span', 'mjg-pc-thumb', card);
        var img = el('img', 'mjg-pc-img', thumb);
        img.alt = '';
        img.loading = 'lazy';
        img.addEventListener('error', function () {
            if (!img.dataset.fbApplied) { img.dataset.fbApplied = '1'; img.src = '/img/default_img.png'; }
        });
        img.src = p.image;
        if (hasDiscount && off >= 1) el('span', 'mjg-pc-off', thumb).textContent = '-' + off + '%';

        var info = el('span', 'mjg-pc-info', card);
        el('span', 'mjg-pc-name', info).textContent = p.name;

        var sub = el('span', 'mjg-pc-sub', info);
        if (p.category) el('span', 'mjg-pc-cat', sub).textContent = p.category;
        // rating only when the product genuinely has one — never a placeholder
        if (p.rating) el('span', 'mjg-pc-star', sub).textContent = '★ ' + p.rating;

        var priceRow = el('span', 'mjg-pc-price', info);
        el('b', null, priceRow).textContent = 'Rs. ' + money(price);
        if (hasDiscount) el('s', null, priceRow).textContent = 'Rs. ' + money(mrp);

        el('span', 'mjg-pc-go', card).appendChild(svg('M9 18l6-6-6-6'));
        return card;
    }

    function renderProducts(products, kind) {
        if (!products || !products.length) {
            if (kind === 'none') renderEmptyState();
            return;
        }

        var wrap = el('div', 'mjg-recs');
        if (kind === 'alternative') {
            el('div', 'mjg-recs-label', wrap).textContent = 'Milte julte options';
        }
        products.forEach(function (p, i) { wrap.appendChild(productCard(p, i)); });

        var more = el('a', 'mjg-recs-more', wrap);
        more.href = '/products/all-page';
        more.textContent = 'Saare products dekhein';
        more.appendChild(svg('M9 18l6-6-6-6'));

        msgs.appendChild(wrap);
    }

    /* store had nothing at all — still hand the user somewhere to go */
    function renderEmptyState() {
        var box = el('div', 'mjg-empty');
        el('div', 'mjg-empty-ic', box).appendChild(svg('M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z'));
        el('div', 'mjg-empty-t', box).textContent = 'Ye cheez abhi store mein nahi hai';
        el('div', 'mjg-empty-s', box).textContent = 'Aap yahan se browse kar sakte hain:';
        var chips = el('div', 'mjg-empty-chips', box);
        [
            ['Saare products', '/products/all-page'],
            ['Cosmetics', '/cosmetics'],
            ['Home', '/']
        ].forEach(function (c) {
            var a = el('a', null, chips);
            a.href = c[1];
            a.textContent = c[0];
        });
        msgs.appendChild(box);
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
            arr.forEach(function (m) {
                bubble(m.role, m.text);
                if (m.role === 'assistant') renderProducts(m.products, m.kind);
            });
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

    /* unread dot lives on the FAB and on every bottom-nav MJGuider tab */
    function setDot(show) {
        dot.hidden = !show;
        Array.prototype.forEach.call(document.querySelectorAll('.mjg-nav-dot'), function (d) {
            d.hidden = !show;
        });
    }

    function openChat() {
        root.classList.add('mjg-open');
        win.setAttribute('aria-hidden', 'false');
        setDot(false);
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

    /* Bottom-nav MJGuider tabs: open the chat; their presence hides the
       mobile FAB (guests have no bottom nav, so they keep the FAB). */
    var navTabs = document.querySelectorAll('.mjg-nav-open');
    if (navTabs.length) {
        root.classList.add('mjg-nav');
        Array.prototype.forEach.call(navTabs, function (t) {
            t.addEventListener('click', openChat);
        });
    }

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

        // context = the last 10 messages BEFORE this new one.
        // Cards carry the product details now, so the shown products are appended to
        // the assistant's context text — that is how "dusra wala kitne ka hai?" works.
        // Sliced under the server's max:3000 so one long reply can never 422-poison the chat.
        var context = loadHistory().slice(-CONTEXT_SIZE).map(function (m) {
            var t = String(m.text);
            if (m.products && m.products.length) {
                t += '\n[shown to user: ' + m.products.map(function (p) {
                    return p.name + ' — Rs. ' + Math.round(p.selling_price * 1.17);
                }).join('; ') + ']';
            }
            return { role: m.role, text: t.slice(0, 2500) };
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
                var products = sanitizeProducts(data && data.products);
                var kind = data && typeof data.kind === 'string' ? data.kind : '';
                pushMessage('assistant', reply, products, kind);
                bubble('assistant', reply);
                renderProducts(products, kind);
                scrollDown();
                if (!isOpen()) setDot(true);
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
