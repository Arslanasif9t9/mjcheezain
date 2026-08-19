@extends('layouts.structure')
@section('title', 'About')
@section('body')
    <x-cosmetics.header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <main class="overflow-x-hidden bg-white">

        <!-- 1. WELCOME / HERO SECTION -->
        <section class="relative bg-gradient-to-b from-[#FFF3F0] via-[#FFF9F4] to-white">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-12 sm:py-20 md:py-28 text-center">
                <h1 class="PFDI text-3xl sm:text-5xl md:text-6xl font-display font-medium tracking-wider text-gray-900 mb-4">
                    Welcome to MJ CHEEZAIN
                </h1>
                <div class="brand-divider mb-5"></div>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                    Your one-stop destination for quality products across multiple categories – from auto parts to lifestyle essentials.
                </p>
            </div>
        </section>

        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            <!-- 2. OUR JOURNEY SECTION -->
            <section class="py-12 sm:py-16 md:py-24">
                <!-- Grid container: Stack vertically on mobile, two columns on desktop -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">

                    <!-- LEFT COLUMN: Text Content -->
                    <div class="space-y-6">
                        <div>
                            <h2 class="PFDI text-3xl sm:text-4xl font-bold text-gray-900 pb-2">
                                Our Journey
                            </h2>
                            <div class="brand-divider brand-divider-left"></div>
                        </div>
                        <p class="text-base leading-relaxed text-gray-700">
                            MJ CHEEZAIN has been operating since <strong class="text-gray-900">1 August 2025</strong>, dedicating over a year to developing, improving, and refining our marketplace system. Our official multi-vendor marketplace will launch on <strong class="text-gray-900">6 September 2026</strong> with a complete and professionally developed system.
                        </p>
                        <p class="text-base leading-relaxed text-gray-700">
                            Customers can search for any product they want, or simply ask <strong class="text-gray-900">MJ GUIDER</strong> for assistance, and it will help find products according to their needs and situation. Customers can also ask questions about the MJ CHEEZAIN marketplace and its features for a more convenient shopping experience.
                        </p>
                        <p class="text-base leading-relaxed text-gray-700">
                            Our main brand focus is <strong class="text-gray-900">MJ Fragrance &amp; Scents</strong> &mdash; discover your fragrance, buy, experience, and enjoy.
                        </p>
                    </div>

                    <!-- RIGHT COLUMN: Visual Placeholder -->
                    <div class="lg:sticky lg:top-24">
                        <div class="relative w-full h-56 sm:h-72 lg:h-80 rounded-xl shadow-md border border-gray-100 bg-gradient-to-br from-[#FFF3F0] to-[#FFF9EF] flex items-center justify-center p-6 card-hover-glow">
                            <div class="flex flex-col items-center text-center">
                                <span class="w-14 h-14 mb-4 rounded-full flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                                    <i data-lucide="car" class="w-7 h-7 text-white stroke-[1.5]"></i>
                                </span>
                                <span class="text-base sm:text-lg font-semibold text-gray-600">
                                    Auto parts and e-commerce visuals
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- 3. EXPANDING BEYOND AUTO PARTS SECTION -->
            <section class="pb-12 sm:pb-16 md:pb-24">
                <!-- Grid container: Stack vertically on mobile, two columns on desktop. Columns are INVERTED (Image first) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

                    <!-- LEFT COLUMN (Order 1 on desktop): Image -->
                    <div class="order-2 lg:order-1 lg:sticky lg:top-24">
                        <div class="relative w-full h-64 sm:h-80 lg:h-96 rounded-xl shadow-lg overflow-hidden card-hover-glow">
                            <!-- Placeholder Image (Unsplash photo with a lifestyle/e-commerce theme) -->
                            <img
                                src="https://images.unsplash.com/photo-1549490382-b13c19e5d429?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxfDB8MXxyYW5kb218MHx8ZWNvbW1lcmNlLHdvbWFuLGxpZmVzdHlsZXx8fHx8fDE2NzM0MTI3NTg&ixlib=rb-4.0.3&q=80&w=1080"
                                alt="Woman viewing e-commerce site on tablet surrounded by plants, symbolizing expansion into lifestyle."
                                class="w-full h-full object-cover"
                                onerror="this.onerror=null;this.src='https://placehold.co/1080x960/FFF3F0/FF7DA0?text=Lifestyle+Image+Placeholder'"
                            >
                            <!-- Subtle border to lift it off the background -->
                            <div class="absolute inset-0 border-4 border-white rounded-xl pointer-events-none"></div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN (Order 2 on desktop): Text Content -->
                    <div class="order-1 lg:order-2 space-y-6">
                        <div>
                            <h2 class="PFDI text-3xl sm:text-5xl font-display font-medium leading-tight tracking-wide text-gray-900">
                                Our Core &mdash; MJ Fragrance &amp; Scents
                            </h2>
                            <div class="brand-divider brand-divider-left"></div>
                        </div>
                        <p class="text-base leading-relaxed text-gray-700">
                            At the heart of MJ CHEEZAIN is <strong class="text-gray-900">MJ Fragrance &amp; Scents</strong>, our core brand and a key part of our international identity.
                        </p>
                        <p class="text-base leading-relaxed text-gray-700">
                            We aim to build a fragrance brand that represents Pakistan on the global stage, bringing premium scents and a distinctive experience to customers worldwide.
                        </p>
                        <p class="text-base leading-relaxed text-gray-700">
                            And with your support, we aim to make MJ CHEEZAIN <strong class="text-gray-900">a new benchmark for multi-vendor e-commerce in Pakistan &mdash; and beyond.</strong> Buy. Experience. Enjoy the Fragrance.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 4. OUR PRODUCT CATEGORIES SECTION -->
            <section class="py-12 sm:py-16 md:py-24 text-center">
                <h2 class="PFDI text-3xl sm:text-5xl font-display font-medium tracking-wide text-gray-900">
                    Our Product Categories
                </h2>
                <div class="brand-divider mb-10 sm:mb-14"></div>

                <!-- Categories Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">

                    <!-- Category Card Template -->
                    <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-100 shadow-md card-hover-glow cursor-pointer flex flex-col items-center justify-center min-h-[140px] sm:min-h-[160px]">
                        <span class="w-12 h-12 mb-3 rounded-full flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="briefcase" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </span>
                        <span class="text-base sm:text-lg font-medium text-gray-700">Bags &amp; Luggage</span>
                    </div>

                    <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-100 shadow-md card-hover-glow cursor-pointer flex flex-col items-center justify-center min-h-[140px] sm:min-h-[160px]">
                        <span class="w-12 h-12 mb-3 rounded-full flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="laptop" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </span>
                        <span class="text-base sm:text-lg font-medium text-gray-700">Smart Home &amp; Gadgets</span>
                    </div>

                    <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-100 shadow-md card-hover-glow cursor-pointer flex flex-col items-center justify-center min-h-[140px] sm:min-h-[160px]">
                        <span class="w-12 h-12 mb-3 rounded-full flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="sparkles" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </span>
                        <span class="text-base sm:text-lg font-medium text-gray-700">Fragrance &amp; Scents</span>
                    </div>

                    <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-100 shadow-md card-hover-glow cursor-pointer flex flex-col items-center justify-center min-h-[140px] sm:min-h-[160px]">
                        <span class="w-12 h-12 mb-3 rounded-full flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="utensils-crossed" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </span>
                        <span class="text-base sm:text-lg font-medium text-gray-700">Kitchen &amp; Dining</span>
                    </div>

                    <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-100 shadow-md card-hover-glow cursor-pointer flex flex-col items-center justify-center min-h-[140px] sm:min-h-[160px]">
                        <span class="w-12 h-12 mb-3 rounded-full flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="shirt" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </span>
                        <span class="text-base sm:text-lg font-medium text-gray-700">Fashion &amp; Clothing</span>
                    </div>

                    <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-100 shadow-md card-hover-glow cursor-pointer flex flex-col items-center justify-center min-h-[140px] sm:min-h-[160px]">
                        <span class="w-12 h-12 mb-3 rounded-full flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="heart-handshake" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </span>
                        <span class="text-base sm:text-lg font-medium text-gray-700">Personal Care &amp; Daily Essentials</span>
                    </div>

                    <div class="p-6 sm:p-8 rounded-xl border border-gray-100 shadow-md card-hover-glow cursor-pointer flex flex-col items-center justify-center min-h-[140px] sm:min-h-[160px]" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                        <span class="w-12 h-12 mb-3 rounded-full flex items-center justify-center bg-white/20">
                            <i data-lucide="plus" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </span>
                        <span class="text-base sm:text-lg font-medium text-white">And Many More</span>
                    </div>

                </div>
            </section>

        </div>

        <!-- 5. GLOBAL EXPANSION VISION SECTION (Brand Gradient Band) -->
        <section class="py-14 sm:py-20 md:py-28 text-center" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="PFDI text-3xl sm:text-5xl font-display font-medium tracking-wide text-white mb-5">
                    Global Expansion Vision
                </h2>
                <p class="text-base sm:text-lg text-white/95 max-w-3xl mx-auto leading-relaxed italic">
                    MJ CHEEZAIN aims to become a leading international e-commerce platform.
                </p>
                <p class="text-base sm:text-lg text-white/95 max-w-3xl mx-auto leading-relaxed mt-4">
                    Our challenge is to make MJ CHEEZAIN <strong class="text-white">Pakistan's #1 multi-vendor marketplace by 6 September 2027</strong>, earning the highest level of customer trust and building exceptional marketplace value.
                </p>
                <p class="text-base sm:text-lg text-white/95 max-w-3xl mx-auto leading-relaxed mt-4 italic">
                    We aim to build one of Pakistan's best multi-vendor marketplaces and then take MJ CHEEZAIN to the international market.
                </p>
                <p class="text-base sm:text-lg text-white/95 max-w-3xl mx-auto leading-relaxed mt-4 italic">
                    InshaAllah, 6 September 2027 will mark a new milestone in the MJ CHEEZAIN journey.
                </p>
            </div>
        </section>

        <!-- 6. VALUE PROPOSITIONS SECTION -->
        <section class="bg-gray-50 py-12 sm:py-16 md:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">

                    <!-- Card 1: Affordable Pricing -->
                    <div class="bg-white p-6 sm:p-10 rounded-xl border border-gray-100 shadow-md card-hover-glow text-center flex flex-col items-center">
                        <!-- Icon: Dollar Sign / Pricing -->
                        <div class="w-12 h-12 flex items-center justify-center rounded-full mb-5" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="dollar-sign" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                            Affordable Pricing
                        </h3>
                        <p class="text-sm text-gray-600 max-w-xs">
                            We work directly with vendors to offer competitive prices without compromising on quality.
                        </p>
                    </div>

                    <!-- Card 2: Diverse Selection -->
                    <div class="bg-white p-6 sm:p-10 rounded-xl border border-gray-100 shadow-md card-hover-glow text-center flex flex-col items-center">
                        <!-- Icon: Grid / Diverse Selection -->
                        <div class="w-12 h-12 flex items-center justify-center rounded-full mb-5" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="layout-grid" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                            Wide Selection
                        </h3>
                        <p class="text-sm text-gray-600 max-w-xs">
                            From Fragrance &amp; Scents to everyday products, we are continuously adding more products.
                        </p>
                    </div>

                    <!-- Card 3: Smooth Experience -->
                    <div class="bg-white p-6 sm:p-10 rounded-xl border border-gray-100 shadow-md card-hover-glow text-center flex flex-col items-center">
                        <!-- Icon: Code / Experience -->
                        <div class="w-12 h-12 flex items-center justify-center rounded-full mb-5" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="code" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                            Easy Shopping
                        </h3>
                        <p class="text-sm text-gray-600 max-w-xs">
                            An easy-to-use website, secure payments, and helpful customer support.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- 7. OUR PROMISE SECTION -->
        <section class="bg-gray-50 pb-12 sm:pb-16 md:pb-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="PFDI text-3xl sm:text-5xl font-display font-medium tracking-wide text-gray-900 text-center">
                    Our Promise
                </h2>
                <div class="brand-divider mb-8 sm:mb-12"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16 p-6 sm:p-12 bg-white rounded-xl border border-gray-100 shadow-md">

                    <!-- Column 1: For Customers -->
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">For Customers</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#FF7DA0] flex-shrink-0"></i>
                                <span class="text-base text-gray-700">Genuine products with quality assurance</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#FF7DA0] flex-shrink-0"></i>
                                <span class="text-base text-gray-700">Competitive pricing across all categories</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#FF7DA0] flex-shrink-0"></i>
                                <span class="text-base text-gray-700">Secure payment options</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 2: For Sellers -->
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">For Sellers</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#FF7DA0] flex-shrink-0"></i>
                                <span class="text-base text-gray-700">Low commission rates</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#FF7DA0] flex-shrink-0"></i>
                                <span class="text-base text-gray-700">Easy-to-use vendor dashboard</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#FF7DA0] flex-shrink-0"></i>
                                <span class="text-base text-gray-700">Marketing and promotional support</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>

        <!-- 8. TAGLINE DIVIDER -->
        <div class="text-center py-8 sm:py-10 px-4">
            <span class="section-kicker justify-center mb-2">Bottom Line</span>
            <p class="PFDI text-lg sm:text-xl font-display italic text-gray-600">
                Our goal is to build customer trust in MJ CHEEZAIN.
            </p>
        </div>

    </main>

    <x-footer />

    <!-- Lucide icons: loaded here (not in the shared layout) so only the pages
         that actually use icons pay for it. -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Script to initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
@endsection
