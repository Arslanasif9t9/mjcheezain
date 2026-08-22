/**
 * Fault Annotator — freehand pencil drawing tool for vendor fault-image uploads.
 *
 * Usage: for each fault-image upload slot, call
 *   FaultAnnotator.attachBadge(imageUploadContainerEl, fileInputEl)
 *
 * A small pencil badge appears on the image preview once a file is chosen.
 * Clicking it opens a canvas editor over the image; the vendor can draw
 * freehand strokes (mouse or touch) to mark/underline/circle the damage.
 * "Save annotation" flattens the strokes onto the image and replaces the
 * file in the given <input type="file"> (via DataTransfer) with the
 * annotated image, then fires a 'change' event so existing preview logic
 * picks it up automatically. Skipping the tool leaves the original file
 * untouched — annotation is purely optional.
 */
(function (window, document) {
    'use strict';

    if (window.FaultAnnotator) return; // already loaded

    var COLORS = ['#e11d48', '#111827', '#2563eb', '#f59e0b', '#16a34a', '#ffffff'];
    var SIZES = { small: 3, medium: 6, large: 11 };
    var MAX_DIMENSION = 1920; // cap exported image size

    var modalEl, canvasEl, ctx, colorRow, sizeButtons, undoBtn, clearBtn, saveBtn, cancelBtn, closeBtn;
    var bgImage = null;
    var strokes = [];
    var currentStroke = null;
    var drawing = false;
    var currentColor = COLORS[0];
    var currentSizeKey = 'medium';
    var activeFileInput = null;
    var activeFileName = 'fault-image.jpg';

    function injectStyles() {
        if (document.getElementById('fault-annotator-styles')) return;
        var style = document.createElement('style');
        style.id = 'fault-annotator-styles';
        style.textContent = [
            '.fault-annotate-badge{position:absolute;top:6px;right:6px;z-index:20;',
            'width:30px;height:30px;border-radius:9999px;border:none;cursor:pointer;',
            'background:rgba(17,24,39,0.75);color:#fff;display:none;align-items:center;',
            'justify-content:center;font-size:13px;box-shadow:0 2px 6px rgba(0,0,0,0.3);',
            'transition:transform .15s ease,background .15s ease;}',
            '.fault-annotate-badge:hover{background:#E85D85;transform:scale(1.08);}',
            '.fault-annotator-overlay{position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,0.72);',
            'display:none;align-items:center;justify-content:center;padding:16px;}',
            '.fault-annotator-overlay.is-open{display:flex;}',
            '.fault-annotator-modal{background:#fff;border-radius:14px;max-width:96vw;',
            'max-height:96vh;width:720px;display:flex;flex-direction:column;overflow:hidden;',
            'box-shadow:0 20px 60px rgba(0,0,0,0.35);}',
            '@media (prefers-reduced-motion: no-preference){.fault-annotator-overlay{transition:opacity .15s ease;}}',
            '.fault-annotator-head{display:flex;align-items:center;justify-content:space-between;',
            'padding:12px 16px;border-bottom:1px solid #e5e7eb;}',
            '.fault-annotator-head h3{margin:0;font-size:15px;font-weight:600;color:#111827;}',
            '.fault-annotator-close{background:none;border:none;font-size:18px;cursor:pointer;color:#6b7280;',
            'width:32px;height:32px;border-radius:8px;}',
            '.fault-annotator-close:hover{background:#f3f4f6;color:#111827;}',
            '.fault-annotator-canvas-wrap{flex:1;display:flex;align-items:center;justify-content:center;',
            'background:#0f172a;padding:10px;overflow:auto;touch-action:none;}',
            '.fault-annotator-canvas-wrap canvas{touch-action:none;max-width:100%;max-height:70vh;',
            'display:block;border-radius:6px;cursor:crosshair;background:#fff;}',
            '.fault-annotator-toolbar{display:flex;flex-wrap:wrap;align-items:center;gap:10px;',
            'padding:12px 16px;border-top:1px solid #e5e7eb;background:#f9fafb;}',
            '.fault-annotator-group{display:flex;align-items:center;gap:6px;}',
            '.fault-annotator-swatch{width:22px;height:22px;border-radius:9999px;border:2px solid #fff;',
            'box-shadow:0 0 0 1px #d1d5db;cursor:pointer;padding:0;}',
            '.fault-annotator-swatch.is-active{box-shadow:0 0 0 2px #111827;}',
            '.fault-annotator-size{width:30px;height:30px;border-radius:8px;border:1px solid #d1d5db;',
            'background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;}',
            '.fault-annotator-size.is-active{border-color:#E85D85;background:#fdecf1;}',
            '.fault-annotator-size span{display:block;border-radius:9999px;background:#111827;}',
            '.fault-annotator-btn{padding:7px 12px;border-radius:8px;border:1px solid #d1d5db;background:#fff;',
            'cursor:pointer;font-size:13px;font-weight:500;color:#374151;}',
            '.fault-annotator-btn:hover{background:#f3f4f6;}',
            '.fault-annotator-btn.primary{background:#E85D85;border-color:#E85D85;color:#fff;margin-left:auto;}',
            '.fault-annotator-btn.primary:hover{background:#d64a72;}',
            '.fault-annotator-spacer{flex:1 1 auto;}'
        ].join('');
        document.head.appendChild(style);
    }

    function buildModal() {
        if (modalEl) return;
        injectStyles();

        modalEl = document.createElement('div');
        modalEl.className = 'fault-annotator-overlay';
        modalEl.innerHTML =
            '<div class="fault-annotator-modal" role="dialog" aria-modal="true" aria-label="Annotate fault image">' +
                '<div class="fault-annotator-head">' +
                    '<h3><i class="fas fa-pencil-alt"></i>&nbsp; Mark the damage</h3>' +
                    '<button type="button" class="fault-annotator-close" aria-label="Close">&times;</button>' +
                '</div>' +
                '<div class="fault-annotator-canvas-wrap"><canvas></canvas></div>' +
                '<div class="fault-annotator-toolbar">' +
                    '<div class="fault-annotator-group" data-role="colors"></div>' +
                    '<div class="fault-annotator-group" data-role="sizes"></div>' +
                    '<button type="button" class="fault-annotator-btn" data-role="undo"><i class="fas fa-rotate-left"></i> Undo</button>' +
                    '<button type="button" class="fault-annotator-btn" data-role="clear"><i class="fas fa-trash-alt"></i> Clear</button>' +
                    '<button type="button" class="fault-annotator-btn" data-role="cancel">Cancel</button>' +
                    '<button type="button" class="fault-annotator-btn primary" data-role="save"><i class="fas fa-check"></i> Save annotation</button>' +
                '</div>' +
            '</div>';
        document.body.appendChild(modalEl);

        canvasEl = modalEl.querySelector('canvas');
        ctx = canvasEl.getContext('2d');
        colorRow = modalEl.querySelector('[data-role="colors"]');
        sizeButtons = modalEl.querySelector('[data-role="sizes"]');
        undoBtn = modalEl.querySelector('[data-role="undo"]');
        clearBtn = modalEl.querySelector('[data-role="clear"]');
        saveBtn = modalEl.querySelector('[data-role="save"]');
        cancelBtn = modalEl.querySelector('[data-role="cancel"]');
        closeBtn = modalEl.querySelector('.fault-annotator-close');

        COLORS.forEach(function (color) {
            var swatch = document.createElement('button');
            swatch.type = 'button';
            swatch.className = 'fault-annotator-swatch' + (color === currentColor ? ' is-active' : '');
            swatch.style.background = color;
            swatch.dataset.color = color;
            swatch.title = color;
            swatch.addEventListener('click', function () {
                currentColor = color;
                colorRow.querySelectorAll('.fault-annotator-swatch').forEach(function (el) {
                    el.classList.toggle('is-active', el.dataset.color === color);
                });
            });
            colorRow.appendChild(swatch);
        });

        Object.keys(SIZES).forEach(function (key) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'fault-annotator-size' + (key === currentSizeKey ? ' is-active' : '');
            btn.title = key;
            var dotSize = { small: 6, medium: 10, large: 15 }[key];
            btn.innerHTML = '<span style="width:' + dotSize + 'px;height:' + dotSize + 'px;"></span>';
            btn.dataset.size = key;
            btn.addEventListener('click', function () {
                currentSizeKey = key;
                sizeButtons.querySelectorAll('.fault-annotator-size').forEach(function (el) {
                    el.classList.toggle('is-active', el.dataset.size === key);
                });
            });
            sizeButtons.appendChild(btn);
        });

        undoBtn.addEventListener('click', function () {
            strokes.pop();
            redraw();
        });
        clearBtn.addEventListener('click', function () {
            strokes = [];
            redraw();
        });
        cancelBtn.addEventListener('click', closeModal);
        closeBtn.addEventListener('click', closeModal);
        modalEl.addEventListener('click', function (e) {
            if (e.target === modalEl) closeModal();
        });
        saveBtn.addEventListener('click', saveAnnotation);

        canvasEl.addEventListener('pointerdown', onPointerDown);
        canvasEl.addEventListener('pointermove', onPointerMove);
        canvasEl.addEventListener('pointerup', onPointerUp);
        canvasEl.addEventListener('pointercancel', onPointerUp);
        canvasEl.addEventListener('pointerleave', onPointerUp);
    }

    function getPos(e) {
        var rect = canvasEl.getBoundingClientRect();
        var scaleX = canvasEl.width / rect.width;
        var scaleY = canvasEl.height / rect.height;
        return {
            x: (e.clientX - rect.left) * scaleX,
            y: (e.clientY - rect.top) * scaleY
        };
    }

    function onPointerDown(e) {
        e.preventDefault();
        var pos = getPos(e);
        currentStroke = { color: currentColor, size: SIZES[currentSizeKey], points: [pos] };
        strokes.push(currentStroke);
        drawing = true;
        try { canvasEl.setPointerCapture(e.pointerId); } catch (err) { /* noop */ }
    }

    function onPointerMove(e) {
        if (!drawing || !currentStroke) return;
        e.preventDefault();
        var pos = getPos(e);
        var prev = currentStroke.points[currentStroke.points.length - 1];
        currentStroke.points.push(pos);
        ctx.strokeStyle = currentStroke.color;
        ctx.lineWidth = currentStroke.size;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.beginPath();
        ctx.moveTo(prev.x, prev.y);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
    }

    function onPointerUp(e) {
        if (!drawing) return;
        drawing = false;
        currentStroke = null;
    }

    function paintStroke(stroke) {
        if (!stroke.points.length) return;
        ctx.strokeStyle = stroke.color;
        ctx.lineWidth = stroke.size;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.beginPath();
        ctx.moveTo(stroke.points[0].x, stroke.points[0].y);
        for (var i = 1; i < stroke.points.length; i++) {
            ctx.lineTo(stroke.points[i].x, stroke.points[i].y);
        }
        if (stroke.points.length === 1) {
            // a tap with no drag: draw a dot
            ctx.lineTo(stroke.points[0].x + 0.1, stroke.points[0].y + 0.1);
        }
        ctx.stroke();
    }

    function redraw() {
        ctx.clearRect(0, 0, canvasEl.width, canvasEl.height);
        if (bgImage) ctx.drawImage(bgImage, 0, 0, canvasEl.width, canvasEl.height);
        strokes.forEach(paintStroke);
    }

    function layoutCanvas() {
        var w = bgImage.naturalWidth || bgImage.width;
        var h = bgImage.naturalHeight || bgImage.height;
        var scaleDown = Math.min(1, MAX_DIMENSION / Math.max(w, h));
        w = Math.round(w * scaleDown);
        h = Math.round(h * scaleDown);
        canvasEl.width = w;
        canvasEl.height = h;

        var wrap = modalEl.querySelector('.fault-annotator-canvas-wrap');
        var maxW = Math.max(240, wrap.clientWidth - 20);
        var maxH = Math.max(240, window.innerHeight * 0.6);
        var displayRatio = Math.min(maxW / w, maxH / h, 1);
        canvasEl.style.width = Math.round(w * displayRatio) + 'px';
        canvasEl.style.height = Math.round(h * displayRatio) + 'px';
    }

    function openModal() {
        modalEl.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modalEl.classList.remove('is-open');
        document.body.style.overflow = '';
        bgImage = null;
        strokes = [];
        activeFileInput = null;
    }

    function openEditor(file, fileInput) {
        buildModal();
        activeFileInput = fileInput;
        activeFileName = (file.name || 'fault-image.jpg').replace(/\.(png|jpe?g|gif|webp)$/i, '') + '-annotated.jpg';

        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                bgImage = img;
                strokes = [];
                openModal();
                layoutCanvas();
                redraw();
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function saveAnnotation() {
        if (!activeFileInput) { closeModal(); return; }
        canvasEl.toBlob(function (blob) {
            if (!blob) { closeModal(); return; }
            var annotatedFile;
            try {
                annotatedFile = new File([blob], activeFileName, { type: 'image/jpeg' });
            } catch (err) {
                // Older browsers without File constructor support: fall back to the blob.
                annotatedFile = blob;
                annotatedFile.name = activeFileName;
            }

            var dt = new DataTransfer();
            dt.items.add(annotatedFile);
            activeFileInput.files = dt.files;
            activeFileInput.dispatchEvent(new Event('change', { bubbles: true }));

            closeModal();
        }, 'image/jpeg', 0.9);
    }

    function attachBadge(containerEl, fileInput) {
        if (!containerEl || !fileInput) return;
        if (getComputedStyle(containerEl).position === 'static') {
            containerEl.style.position = 'relative';
        }

        var badge = document.createElement('button');
        badge.type = 'button';
        badge.className = 'fault-annotate-badge';
        badge.title = 'Draw / mark damage on this photo';
        badge.innerHTML = '<i class="fas fa-pencil-alt"></i>';
        containerEl.appendChild(badge);

        function syncVisibility() {
            var hasFile = fileInput.files && fileInput.files[0];
            badge.style.display = hasFile ? 'flex' : 'none';
        }

        fileInput.addEventListener('change', syncVisibility);
        syncVisibility();

        badge.addEventListener('pointerdown', function (e) { e.stopPropagation(); });
        badge.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var file = fileInput.files && fileInput.files[0];
            if (!file) return;
            openEditor(file, fileInput);
        });
    }

    window.FaultAnnotator = { attachBadge: attachBadge };
})(window, document);
