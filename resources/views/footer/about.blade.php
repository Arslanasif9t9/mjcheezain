@extends('layouts.structure')
@section('title', 'About')
@section('body')
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 70rem;">

        <!-- 1. WELCOME / HERO SECTION -->
        <section class="py-20 md:py-32 text-center">
            <h1 class="text-3xl sm:text-6xl font-display font-medium tracking-wider mb-3 PFDI">
                Welcome to MJ CHEEZAIN
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Your one-stop destination for quality products across multiple categories – from auto parts to lifestyle essentials.
            </p>
        </section>
        
        <!-- Horizontal Divider -->
        {{-- <div class="h-px bg-gray-200 max-w-4xl mx-auto"></div> --}}

        <!-- 2. OUR JOURNEY SECTION -->
        <section class="pb-20 md:pb-32">
            <!-- Grid container: Stack vertically on mobile, two columns on desktop -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
                
                <!-- LEFT COLUMN: Text Content -->
                <div class="space-y-6">
                    <h2 class="text-3xl sm:text-4xl font-bold border-b-2 border-gray-300 inline-block pb-2 mb-4 PFDI">
                        Our Journey
                    </h2>
                    <p class="text-base leading-relaxed text-gray-700">
                        MJCheezain was officially launched on August 1, 2025, with a clear vision to revolutionize the e-commerce landscape. We began our journey by focusing on the **automotive sector**, offering a wide range of high-quality auto parts and accessories.
                    </p>
                    <p class="text-base leading-relaxed text-gray-700">
                        Our commitment to quality, affordability, and customer satisfaction quickly set us apart, establishing a strong foundation for future growth and allowing us to expand into lifestyle essentials. We believe in providing value that lasts.
                    </p>
                </div>
                
                <!-- RIGHT COLUMN: Visual Placeholder -->
                <div class="lg:sticky lg:top-10">
                    <div class="relative w-full h-64 sm:h-80 bg-neutral-100 rounded-xl shadow-lg border border-neutral-200 flex items-center justify-center p-6">
                        <span class="text-lg font-semibold text-gray-500 text-center">
                            Auto parts and e-commerce visuals
                        </span>
                        <!-- Sub-layer to match the slight shadow effect in the original image -->
                        <div class="absolute inset-0 bg-gradient-to-t from-neutral-50/50 via-transparent to-transparent rounded-xl pointer-events-none"></div>
                    </div>
                </div>

            </div>
        </section>
        
        <!-- 3. EXPANDING BEYOND AUTO PARTS SECTION -->
        <section class="pb-20 md:pb-32">
            <!-- Grid container: Stack vertically on mobile, two columns on desktop. Columns are INVERTED (Image first) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                
                <!-- LEFT COLUMN (Order 1 on desktop): Image -->
                <div class="order-2 lg:order-1 lg:sticky lg:top-10">
                    <div class="relative w-full h-80 sm:h-96 rounded-xl shadow-2xl overflow-hidden">
                        <!-- Placeholder Image (Unsplash photo with a lifestyle/e-commerce theme) -->
                        <img 
                            src="https://images.unsplash.com/photo-1549490382-b13c19e5d429?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8ZWNvbW1lcmNlLHdvbWFuLGxpZmVzdHlsZXx8fHx8fDE2NzM0MTI3NTg&ixlib=rb-4.0.3&q=80&w=1080" 
                            alt="Woman viewing e-commerce site on tablet surrounded by plants, symbolizing expansion into lifestyle."
                            class="w-full h-full object-cover"
                            onerror="this.onerror=null;this.src='https://placehold.co/1080x960/FAF8F4/A3A3A3?text=Lifestyle+Image+Placeholder'"
                        >
                        <!-- Subtle border and shadow to lift it off the background -->
                        <div class="absolute inset-0 border-4 border-white rounded-xl pointer-events-none"></div>
                    </div>
                </div>

                <!-- RIGHT COLUMN (Order 2 on desktop): Text Content -->
                <div class="order-1 lg:order-2 space-y-6">
                    <!-- The font-display class applies the stylized font -->
                    <h2 class="text-4xl sm:text-5xl font-display font-medium leading-tight tracking-wide PFDI">
                        Expanding Beyond Auto Parts
                    </h2>
                    <p class="text-base leading-relaxed text-gray-700">
                        While automotive products remain at our core, we recognized the growing need for a comprehensive online marketplace that caters to all aspects of our customers' lives. This led us to expand our offerings into diverse categories, transforming MJCheezain into a versatile multi-vendor platform.
                    </p>
                    <p class="text-base leading-relaxed text-gray-700">
                        Our commitment is to curate high-quality goods across **electronics, home decor, fashion, and fitness**, ensuring the same standards of excellence that our auto-parts customers rely on.
                    </p>
                </div>
            </div>
        </section>

        <!-- 4. OUR PRODUCT CATEGORIES SECTION -->
        <section class="py-20 md:py-32 text-center">
            <h2 class="text-4xl sm:text-5xl font-display font-medium tracking-wide mb-16 PFDI">
                Our Product Categories
            </h2>
            
            <!-- Categories Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                
                <!-- Category Card Template -->
                <div class="p-8 bg-white rounded-xl shadow-md transition duration-300 hover:shadow-lg hover:ring-2 hover:ring-[#d4af37]/50 cursor-pointer flex flex-col items-center justify-center min-h-[160px] border border-gray-100">
                    <i data-lucide="wrench" class="w-10 h-10 mb-3 text-[#d4af37] stroke-1"></i>
                    <span class="text-lg font-medium text-gray-700">Auto Parts & Accessories</span>
                </div>

                <div class="p-8 bg-white rounded-xl shadow-md transition duration-300 hover:shadow-lg hover:ring-2 hover:ring-[#d4af37]/50 cursor-pointer flex flex-col items-center justify-center min-h-[160px] border border-gray-100">
                    <i data-lucide="home" class="w-10 h-10 mb-3 text-[#d4af37] stroke-1"></i>
                    <span class="text-lg font-medium text-gray-700">Home & Living</span>
                </div>

                <div class="p-8 bg-white rounded-xl shadow-md transition duration-300 hover:shadow-lg hover:ring-2 hover:ring-[#d4af37]/50 cursor-pointer flex flex-col items-center justify-center min-h-[160px] border border-gray-100">
                    <i data-lucide="shirt" class="w-10 h-10 mb-3 text-[#d4af37] stroke-1"></i>
                    <span class="text-lg font-medium text-gray-700">Fashion & Apparel</span>
                </div>

                <div class="p-8 bg-white rounded-xl shadow-md transition duration-300 hover:shadow-lg hover:ring-2 hover:ring-[#d4af37]/50 cursor-pointer flex flex-col items-center justify-center min-h-[160px] border border-gray-100">
                    <i data-lucide="laptop" class="w-10 h-10 mb-3 text-[#d4af37] stroke-1"></i>
                    <span class="text-lg font-medium text-gray-700">Electronics & Gadgets</span>
                </div>

                <div class="p-8 bg-white rounded-xl shadow-md transition duration-300 hover:shadow-lg hover:ring-2 hover:ring-[#d4af37]/50 cursor-pointer flex flex-col items-center justify-center min-h-[160px] border border-gray-100">
                    <i data-lucide="utensils-crossed" class="w-10 h-10 mb-3 text-[#d4af37] stroke-1"></i>
                    <span class="text-lg font-medium text-gray-700">Kitchen & Dining</span>
                </div>
                
                <div class="p-8 bg-white rounded-xl shadow-md transition duration-300 hover:shadow-lg hover:ring-2 hover:ring-[#d4af37]/50 cursor-pointer flex flex-col items-center justify-center min-h-[160px] border border-gray-100">
                    <i data-lucide="heart-handshake" class="w-10 h-10 mb-3 text-[#d4af37] stroke-1"></i>
                    <span class="text-lg font-medium text-gray-700">Health & Beauty</span>
                </div>

            </div>
        </section>

    </div> 
    
    <!-- 5. GLOBAL EXPANSION VISION SECTION (Dark Section) -->
    <section class="bg-black py-24 md:py-32 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="PFDI text-4xl sm:text-5xl font-display font-medium tracking-wide text-[#d4af37] mb-6">
                Global Expansion Vision
            </h2>
            <p class="text-lg text-white max-w-3xl mx-auto">
                MJCheezain aims to become a leading international e-commerce platform, connecting vendors and customers from all corners of the globe. We are actively working to expand our presence in key markets, bringing our diverse product range and exceptional service to a worldwide audience.
            </p>
        </div>
    </section>

    <!-- 6. VALUE PROPOSITIONS SECTION -->
    <section class="bg-off-white py-20 md:py-32">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 100rem;">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Card 1: Affordable Pricing -->
                <div class="bg-white p-8 sm:p-10 rounded-xl shadow-lg border border-gray-100 text-center flex flex-col items-center">
                    <!-- Icon: Dollar Sign / Pricing -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full border border-[#d4af37] mb-5">
                        <i data-lucide="dollar-sign" class="w-6 h-6 text-[#d4af37] stroke-[1.5]"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">
                        Affordable Pricing
                    </h3>
                    <p class="text-sm text-gray-600 max-w-xs">
                        We work directly with vendors to ensure competitive prices without compromising quality.
                    </p>
                </div>

                <!-- Card 2: Diverse Selection -->
                <div class="bg-white p-8 sm:p-10 rounded-xl shadow-lg border border-gray-100 text-center flex flex-col items-center">
                    <!-- Icon: Grid / Diverse Selection -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full border border-[#d4af37] mb-5">
                        <i data-lucide="layout-grid" class="w-6 h-6 text-[#d4af37] stroke-[1.5]"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">
                        Diverse Selection
                    </h3>
                    <p class="text-sm text-gray-600 max-w-xs">
                        From auto parts to everyday essentials, we're constantly expanding our product range.
                    </p>
                </div>
                
                <!-- Card 3: Smooth Experience -->
                <div class="bg-white p-8 sm:p-10 rounded-xl shadow-lg border border-gray-100 text-center flex flex-col items-center">
                    <!-- Icon: Code / Experience -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full border border-[#d4af37] mb-5">
                        <i data-lucide="code" class="w-6 h-6 text-[#d4af37] stroke-[1.5]"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">
                        Smooth Experience
                    </h3>
                    <p class="text-sm text-gray-600 max-w-xs">
                        Intuitive interface, secure payments, and reliable customer support.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- 7. OUR PROMISE SECTION (New Section) -->
    <section class="bg-off-white pt-20 pb-16 md:pb-32">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 100rem;">
            <h2 class="PFDI text-4xl sm:text-5xl font-display font-medium tracking-wide text-center mb-12">
                Our Promise
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 p-8 sm:p-12 rounded-xl border border-gray-100">
                
                <!-- Column 1: For Customers -->
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">For Customers</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#d4af37] flex-shrink-0"></i>
                            <span class="text-base text-gray-700">Genuine products with quality assurance</span>
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#d4af37] flex-shrink-0"></i>
                            <span class="text-base text-gray-700">Competitive pricing across all categories</span>
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#d4af37] flex-shrink-0"></i>
                            <span class="text-base text-gray-700">Secure payment options</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Column 2: For Sellers -->
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">For Sellers</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#d4af37] flex-shrink-0"></i>
                            <span class="text-base text-gray-700">Low commission rates</span>
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#d4af37] flex-shrink-0"></i>
                            <span class="text-base text-gray-700">Easy-to-use vendor dashboard</span>
                        </li>
                        <li class="flex items-start">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#d4af37] flex-shrink-0"></i>
                            <span class="text-base text-gray-700">Marketing and promotional support</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <!-- 8. TAGLINE DIVIDER (New Section) -->
    <div class="text-center py-10">
        <p class="text-xl font-display italic text-gray-600">
            MJ CHEEZAIN — Redefining Global Shopping with Trust, Quality & Style.
        </p>
    </div>

    <!-- Script to initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
@endsection