@extends('layouts.structure')
@section('title', 'Future Vision')
@section('body')
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 70rem;">

        <!-- 1. WELCOME / HERO SECTION -->
        <section class="py-20 md:py-32 text-center">
            <h1 class="text-3xl sm:text-6xl font-display font-medium tracking-wider mb-3 PFDI">
                Redefining E-Commerce for the Future
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                At MJCheezain, we believe that the future of e-commerce lies not just in selling products — but in creating trust, quality, and long-term value for both buyers and sellers.
            </p>
        </section>
        
        <!-- Horizontal Divider -->
        {{-- <div class="h-px bg-gray-200 max-w-4xl mx-auto"></div> --}}

        <!-- 2. OUR JOURNEY SECTION -->
        <section class="pb-20 md:pb-32">
            <!-- Grid container: Stack vertically on mobile, two columns on desktop -->
            <div class="grid grid-cols-1 gap-12 lg:gap-16 items-start">
                
                <!-- LEFT COLUMN: Text Content -->
                <div class="space-y-6">
                    <h2 class="text-3xl sm:text-4xl font-bold border-b-2 border-gray-300 inline-block pb-2 mb-4 PFDI">
                        Our Bold Vision
                    </h2>
                    <p class="text-base leading-relaxed text-gray-700">
                        To make MJCheezain a national symbol of trust and a globally competitive brand that brings convenience, consistency, and care into the daily lives of millions of shoppers.
                    </p>
                    <p class="text-base leading-relaxed text-gray-700">
                        We're not here to be just another platform. We are building an ecosystem — where every product is carefully curated, every seller is verified, and every customer feels 100% confident in what they buy.
                    </p>
                </div>

            </div>
        </section>
        
        <!-- 3. OUR JOURNEY SECTION -->
        <section class="pb-20 md:pb-32">
            <!-- Grid container: Stack vertically on mobile, two columns on desktop -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
                
                <!-- LEFT COLUMN: Text Content -->
                <div class="space-y-6">
                    <h2 class="text-3xl sm:text-4xl font-bold border-b-2 border-gray-300 inline-block pb-2 mb-4 PFDI">
                        Our In-House Brands
                    </h2>
                    <p class="text-base leading-relaxed text-gray-700">
                        Our long-term goal is to develop our own line of trusted in-house brands, covering a wide range of categories including Auto Parts, Electronics, Fashion, and Home Essentials.
                    </p>
                    <p class="text-base leading-relaxed text-gray-700">
                        These brands will carry the MJCheezain seal of quality, ensuring that every item meets our strict standards for performance, durability, and affordability.
                    </p>
                </div>
                
                <!-- RIGHT COLUMN: Visual Placeholder -->
                <div class="lg:sticky lg:top-10">
                    <div class="relative w-full h-64 sm:h-80 bg-neutral-100 rounded-xl shadow-lg border border-neutral-200 flex flex-col items-center justify-center align-center p-6">
                        <h2 class="text-lg font-semibold text-gray-500 text-center">
                            MJCheezain Seal of Quality
                        </h2>
                        <p class="text-center">Our mark of excellence that guarantees product quality, customer satisfaction, and value for money.</p>
                        <!-- Sub-layer to match the slight shadow effect in the original image -->
                        <div class="absolute inset-0 bg-gradient-to-t from-neutral-50/50 via-transparent to-transparent rounded-xl pointer-events-none"></div>
                    </div>
                </div>

            </div>
        </section>

        <!-- 4. OUR PRODUCT CATEGORIES SECTION -->
        <section class="py-20 md:py-32 text-center">
            <h2 class="text-4xl sm:text-5xl font-display font-medium tracking-wide mb-4 PFDI">
                Building a Complete Ecosystem
            </h2>
            <p class="text-center mb-12">As we grow, our focus will remain on product innovation, faster delivery systems, fair pricing, and superior customer experience — all built locally, but with a global standard.</p>
            
            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Card 1: Affordable Pricing -->
                <div class="bg-white p-8 sm:p-10 rounded-xl shadow-lg border border-gray-100 text-center flex flex-col items-center">
                    <!-- Icon: Dollar Sign / Pricing -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full border border-[#d4af37] mb-5">
                        <i data-lucide="dollar-sign" class="w-6 h-6 text-[#d4af37] stroke-[1.5]"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">
                        Innovation
                    </h3>
                    <p class="text-sm text-gray-600 max-w-xs">
                        Continuously improving our platform and services to stay ahead of market needs and technological advancements.
                    </p>
                </div>

                <!-- Card 2: Diverse Selection -->
                <div class="bg-white p-8 sm:p-10 rounded-xl shadow-lg border border-gray-100 text-center flex flex-col items-center">
                    <!-- Icon: Grid / Diverse Selection -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full border border-[#d4af37] mb-5">
                        <i data-lucide="layout-grid" class="w-6 h-6 text-[#d4af37] stroke-[1.5]"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">
                        Logistics
                    </h3>
                    <p class="text-sm text-gray-600 max-w-xs">
                        Developing faster, more reliable delivery systems to ensure customer satisfaction nationwide.
                    </p>
                </div>
                
                <!-- Card 3: Smooth Experience -->
                <div class="bg-white p-8 sm:p-10 rounded-xl shadow-lg border border-gray-100 text-center flex flex-col items-center">
                    <!-- Icon: Code / Experience -->
                    <div class="w-12 h-12 flex items-center justify-center rounded-full border border-[#d4af37] mb-5">
                        <i data-lucide="code" class="w-6 h-6 text-[#d4af37] stroke-[1.5]"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">
                        Customer Care
                    </h3>
                    <p class="text-sm text-gray-600 max-w-xs">
                        Building relationships through exceptional service, support, and post-purchase engagement
                    </p>
                </div>

            </div>
        </section>

    </div> 

    <!-- 7. OUR PROMISE SECTION (New Section) -->
    <section class="bg-off-white pt-20 pb-16 md:pb-32">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 100rem;">
            <h2 class="PFDI text-4xl sm:text-5xl font-display font-medium tracking-wide text-center mb-4">
                Global Ambition, Local Roo
            </h2>
            <p class="text-center mb-8">While we aim to become a globally competitive brand, we remain committed to our local communities — creating jobs, supporting local businesses, and contributing to economic growth.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 p-8 sm:p-12 rounded-xl border border-gray-100">
                
                <!-- Column 1: For Customers -->
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Local Impact</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#d4af37] flex-shrink-0"></i>
                            <span class="text-base text-gray-700">Supporting local manufacturers and artisans</span>
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
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Global Standards</h3>
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

    <!-- Script to initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
@endsection