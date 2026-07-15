<section class="bg-white p-4 mx-auto my-4 rounded-2xl">
    <h2 class="font-bold text-xl mb-4">Biggest Savings</h2>

    <div class="flex gap-3 sm:gap-5 overflow-x-auto snap-x snap-mandatory pb-2" style="-ms-overflow-style:none;scrollbar-width:none;">
        @forelse($products as $product)
            @php
                $cardPrice = $product->selling_price * 1.17;
                $cardMrp = (float) ($product->mrp ?? 0);
                $cardHasDiscount = $cardMrp && $cardMrp > $cardPrice;
                $cardDiscountPct = $cardHasDiscount ? round((($cardMrp - $cardPrice) / $cardMrp) * 100) : 0;
            @endphp
            <div class="product-card-ss10 bg-white rounded-2xl overflow-hidden group flex flex-col relative w-[55vw] sm:w-60 md:w-72 flex-shrink-0 snap-start" style="box-shadow: 0 6px 20px rgba(17, 24, 39, 0.08);">
                @if($cardHasDiscount)
                    <div class="absolute top-2.5 left-2.5 z-10 px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-bold text-white" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 3px 10px rgba(255,125,160,.4);">-{{ $cardDiscountPct }}%</div>
                @endif
                <a href="{{ url('product/'.$product->id) }}" class="no-underline flex flex-col flex-grow">
                    <div class="aspect-[16/10] bg-gray-50 overflow-hidden">
                        <img src="{{ $product->primaryImage->image_path ?? asset('img/default_img.png') }}"
                             alt="{{ $product->name }}" loading="lazy"
                             class="w-full h-full object-cover transition duration-500 ease-in-out group-hover:scale-105">
                    </div>
                    <div class="px-3.5 pt-3 sm:px-4 sm:pt-3.5">
                        <h3 class="text-[15px] sm:text-lg font-bold text-gray-900 truncate leading-snug m-0 group-hover:text-[#E85D85] transition-colors">{{ $product->name }}</h3>
                        <p class="flex items-center gap-1 text-xs sm:text-sm text-gray-400 mt-1 mb-0 truncate">
                            <span class="truncate">{{ $product->category ?? 'MJ Cheezain' }}</span>
                            <span class="flex-shrink-0">&nbsp;•&nbsp;{{ $product->updated_at->diffForHumans() }}</span>
                        </p>
                        <div class="flex items-baseline gap-1.5 mt-1.5 flex-wrap">
                            <span class="text-base sm:text-lg font-extrabold text-gray-900 whitespace-nowrap">Rs. {{ number_format($cardPrice, 0) }}</span>
                            @if($cardHasDiscount)
                                <span class="text-[11px] sm:text-xs text-gray-400 line-through whitespace-nowrap">Rs. {{ number_format($cardMrp, 0) }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                <div class="px-3.5 pb-3.5 pt-2.5 sm:px-4 sm:pb-4 mt-auto">
                    <button onclick="window.location.href='{{ url('product/'.$product->id) }}'"
                            class="w-full py-2 sm:py-2.5 text-[11px] sm:text-sm font-semibold text-white rounded-full transition duration-200 hover:opacity-90 active:scale-[0.98]"
                            style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 4px 12px rgba(255, 125, 160, 0.35);">
                        Quick View
                    </button>
                </div>
            </div>
        @empty
            <p>No discounted products found.</p>
        @endforelse
    </div>
</section>
