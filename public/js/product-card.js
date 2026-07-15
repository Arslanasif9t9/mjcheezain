/**
 * Shared ss10-style product card builder.
 * ONE template used by every product section on the site (home sliders,
 * cosmetics sliders, related products, all-products listing, search, etc.)
 * so the card size/typography is identical everywhere.
 *
 * mode: 'slider' → fixed width for horizontal snap sliders
 *       'grid'   → fluid width for grid/listing layouts
 */
(function () {
    function esc(str) {
        return String(str == null ? '' : str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    window.buildProductCard = function (product, imgUrl, mode) {
        mode = mode || 'slider';

        // Same price maths as the single-product page (selling price + 17% GST)
        var price = product.selling_price * 1.17;
        var mrp = parseFloat(product.mrp || product.original_price || 0);
        var hasDiscount = mrp && mrp > price;
        var discountPct = hasDiscount ? Math.round(((mrp - price) / mrp) * 100) : 0;

        var sizeCls = mode === 'slider'
            ? 'w-[55vw] sm:w-60 md:w-72 flex-shrink-0 snap-start'
            : 'w-full';

        var card = document.createElement('div');
        card.className = 'product-card-ss10 bg-white rounded-2xl overflow-hidden group flex flex-col relative ' + sizeCls;
        card.style.boxShadow = '0 6px 20px rgba(17, 24, 39, 0.08)';

        card.innerHTML =
            // Wishlist heart
            '<div class="absolute top-2.5 right-2.5 z-10">' +
                '<button data-product-id="' + product.id + '" class="heart-btn p-1.5 sm:p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-md hover:bg-pink-50 transition active:scale-95" onclick="event.preventDefault(); event.stopPropagation(); if (window.favToggle) favToggle(' + product.id + ');">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-[18px] h-[18px] sm:w-5 sm:h-5 text-gray-700">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.682l-7.682-7.682a4.5 4.5 0 010-6.364z"></path>' +
                    '</svg>' +
                '</button>' +
            '</div>' +

            // Discount ribbon (top-left, only when discounted)
            (hasDiscount
                ? '<div class="absolute top-2.5 left-2.5 z-10 px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-bold text-white" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 3px 10px rgba(255,125,160,.4);">-' + discountPct + '%</div>'
                : '') +

            // Clickable body (ss10 proportions: wide image on top, roomy white body)
            '<a href="/product/' + product.id + '" class="no-underline flex flex-col flex-grow">' +
                '<div class="aspect-[16/10] bg-gray-50 overflow-hidden">' +
                    '<img src="' + imgUrl + '" alt="' + esc(product.name) + '" loading="lazy" onload="this.classList.add(\'is-loaded\')" ' +
                         'class="fade-in-img w-full h-full object-cover transition duration-500 ease-in-out group-hover:scale-105">' +
                '</div>' +
                '<div class="px-3.5 pt-3 sm:px-4 sm:pt-3.5">' +
                    // Bold title — ss10 style
                    '<h3 class="text-[15px] sm:text-lg font-bold text-gray-900 truncate leading-snug m-0 group-hover:text-[#E85D85] transition-colors">' + esc(product.name) + '</h3>' +
                    // Meta row — ss10 style: icon + meta • meta
                    '<p class="flex items-center gap-1 text-xs sm:text-sm text-gray-400 mt-1 mb-0 truncate">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 flex-shrink-0 text-[#FF7DA0]"><path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd"/></svg>' +
                        '<span class="truncate">' + esc(product.category || 'MJ Cheezain') + '</span>' +
                        '<span class="flex-shrink-0">&nbsp;•&nbsp;<span class="text-yellow-400">★</span> ' + (product.rating || '4.9') + '</span>' +
                    '</p>' +
                    // Price row
                    '<div class="flex items-baseline gap-1.5 mt-1.5 flex-wrap">' +
                        '<span class="text-base sm:text-lg font-extrabold text-gray-900 whitespace-nowrap">Rs. ' + price.toFixed(0) + '</span>' +
                        (hasDiscount ? '<span class="text-[11px] sm:text-xs text-gray-400 line-through whitespace-nowrap">Rs. ' + mrp.toFixed(0) + '</span>' : '') +
                    '</div>' +
                '</div>' +
            '</a>' +

            // Quick View button (always at the very end of the card)
            '<div class="px-3.5 pb-3.5 pt-2.5 sm:px-4 sm:pb-4 mt-auto">' +
                '<button onclick="window.location.href=\'/product/' + product.id + '\'" ' +
                        'class="w-full py-2 sm:py-2.5 text-[11px] sm:text-sm font-semibold text-white rounded-full transition duration-200 hover:opacity-90 active:scale-[0.98]" ' +
                        'style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 4px 12px rgba(255, 125, 160, 0.35);">' +
                    'Quick View' +
                '</button>' +
            '</div>';

        return card;
    };

    // Hover lift, injected once
    if (!document.getElementById('product-card-ss10-style')) {
        var style = document.createElement('style');
        style.id = 'product-card-ss10-style';
        style.textContent =
            '.product-card-ss10{transition:transform .25s ease, box-shadow .25s ease;}' +
            '.product-card-ss10:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(232,93,133,.16)!important;}';
        document.head.appendChild(style);
    }
})();
