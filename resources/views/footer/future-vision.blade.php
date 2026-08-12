@extends('layouts.structure')
@section('title', 'Future Vision')
@section('body')
    <x-cosmetics.header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <main class="bg-white overflow-x-hidden">

        <!-- 1. WELCOME / HERO SECTION -->
        <section class="bg-gray-50 py-12 sm:py-20 md:py-28">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="section-kicker mb-4">Future Vision</span>
                <h1 class="text-3xl sm:text-5xl md:text-6xl font-medium tracking-wide text-gray-900 mb-4 PFDI">
                    Redefining E-Commerce for the Future
                </h1>
                <div class="brand-divider mb-6"></div>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    At MJCheezain, we believe that the future of e-commerce lies not just in selling products — but in creating trust, quality, and long-term value for both buyers and sellers.
                </p>
            </div>
        </section>

        <!-- 2. OUR BOLD VISION SECTION -->
        <section class="py-12 sm:py-20">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl sm:text-4xl font-medium text-gray-900 PFDI">
                    Our Bold Vision
                </h2>
                <div class="brand-divider mb-6"></div>
                <div class="space-y-5 text-left sm:text-center">
                    <p class="text-base leading-relaxed text-gray-700">
                        To make MJCheezain a national symbol of trust and a globally competitive brand that brings convenience, consistency, and care into the daily lives of millions of shoppers.
                    </p>
                    <p class="text-base leading-relaxed text-gray-700">
                        We're not here to be just another platform. We are building an ecosystem — where every product is carefully curated, every seller is verified, and every customer feels 100% confident in what they buy.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. OUR IN-HOUSE BRANDS SECTION -->
        <section class="bg-gray-50 py-12 sm:py-20">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

                    <!-- LEFT COLUMN: Text Content -->
                    <div class="space-y-5">
                        <h2 class="text-2xl sm:text-4xl font-medium text-gray-900 PFDI">
                            Our In-House Brands
                        </h2>
                        <div class="brand-divider brand-divider-left"></div>
                        <p class="text-base leading-relaxed text-gray-700">
                            Our long-term goal is to develop our own line of trusted in-house brands, covering a wide range of categories including Auto Parts, Electronics, Fashion, and Home Essentials.
                        </p>
                        <p class="text-base leading-relaxed text-gray-700">
                            These brands will carry the MJCheezain seal of quality, ensuring that every item meets our strict standards for performance, durability, and affordability.
                        </p>
                    </div>

                    <!-- RIGHT COLUMN: Seal of Quality Card -->
                    <div>
                        <div class="card-hover-glow relative w-full bg-white rounded-xl border border-gray-100 shadow-md flex flex-col items-center justify-center text-center p-8 sm:p-12">
                            <div class="w-14 h-14 flex items-center justify-center rounded-full mb-5" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                                <i data-lucide="badge-check" class="w-7 h-7 text-white stroke-[1.5]"></i>
                            </div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                                MJCheezain Seal of Quality
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">Our mark of excellence that guarantees product quality, customer satisfaction, and value for money.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- 4. BUILDING A COMPLETE ECOSYSTEM SECTION -->
        <section class="py-12 sm:py-20 md:py-28">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-5xl font-medium tracking-wide text-gray-900 PFDI">
                    Building a Complete Ecosystem
                </h2>
                <div class="brand-divider mb-6"></div>
                <p class="text-base sm:text-lg text-gray-600 max-w-3xl mx-auto mb-10 sm:mb-14 leading-relaxed">As we grow, our focus will remain on product innovation, faster delivery systems, fair pricing, and superior customer experience — all built locally, but with a global standard.</p>

                <!-- Categories Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">

                    <!-- Card 1: Innovation -->
                    <div class="card-hover-glow bg-white p-8 sm:p-10 rounded-xl shadow-md border border-gray-100 text-center flex flex-col items-center">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full mb-5" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="dollar-sign" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                            Innovation
                        </h3>
                        <p class="text-sm text-gray-600 max-w-xs leading-relaxed">
                            Continuously improving our platform and services to stay ahead of market needs and technological advancements.
                        </p>
                    </div>

                    <!-- Card 2: Logistics -->
                    <div class="card-hover-glow bg-white p-8 sm:p-10 rounded-xl shadow-md border border-gray-100 text-center flex flex-col items-center">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full mb-5" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="layout-grid" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                            Logistics
                        </h3>
                        <p class="text-sm text-gray-600 max-w-xs leading-relaxed">
                            Developing faster, more reliable delivery systems to ensure customer satisfaction nationwide.
                        </p>
                    </div>

                    <!-- Card 3: Customer Care -->
                    <div class="card-hover-glow bg-white p-8 sm:p-10 rounded-xl shadow-md border border-gray-100 text-center flex flex-col items-center sm:col-span-2 md:col-span-1">
                        <div class="w-12 h-12 flex items-center justify-center rounded-full mb-5" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i data-lucide="code" class="w-6 h-6 text-white stroke-[1.5]"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">
                            Customer Care
                        </h3>
                        <p class="text-sm text-gray-600 max-w-xs leading-relaxed">
                            Building relationships through exceptional service, support, and post-purchase engagement
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <!-- 5. GLOBAL AMBITION, LOCAL ROOTS SECTION -->
        <section class="py-12 sm:py-20 md:py-24" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="PFDI text-3xl sm:text-5xl font-medium tracking-wide text-center text-white mb-4">
                    Global Ambition, Local Roo
                </h2>
                <p class="text-center text-white/90 text-base sm:text-lg max-w-3xl mx-auto mb-10 sm:mb-14 leading-relaxed">While we aim to become a globally competitive brand, we remain committed to our local communities — creating jobs, supporting local businesses, and contributing to economic growth.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10">

                    <!-- Column 1: Local Impact -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md p-6 sm:p-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Local Impact</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-5 h-5 mr-3 mt-1 text-[#FF7DA0] flex-shrink-0"></i>
                                <span class="text-base text-gray-700">Supporting local manufacturers and artisans</span>
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

                    <!-- Column 2: Global Standards -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md p-6 sm:p-10">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Global Standards</h3>
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
