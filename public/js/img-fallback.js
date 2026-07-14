// Global image fallback — if ANY <img> on the page fails to load (broken path,
// missing file, network issue), it is swapped to the default placeholder image.
// Attached in the capture phase because img error events do not bubble.
(function () {
    var FALLBACK = '/img/default_img.png';
    document.addEventListener('error', function (e) {
        var el = e.target;
        if (el && el.tagName === 'IMG' && !el.dataset.fbApplied) {
            el.dataset.fbApplied = '1'; // guard: never loop if the fallback itself fails
            el.src = FALLBACK;
        }
    }, true);
})();
