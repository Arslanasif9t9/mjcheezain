@extends('layouts.structure')
@section('title', 'Vendor Zone')
@section('style')
    <style>
        /* Accordion content, initially collapsed */
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }
        /* Plus icon rotation when active */
        .plus-icon.rotate-45 {
            transform: rotate(45deg);
        }
        /* Active tab pill — brand gradient */
        .nav-button.tab-active {
            background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
            color: #fff !important;
            border-color: transparent;
            box-shadow: 0 6px 16px -4px rgba(255, 125, 160, 0.45);
        }
        /* Hide scrollbar on the mobile tab strip */
        .tab-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .tab-scroll::-webkit-scrollbar {
            display: none;
        }
        /* Gradient icon circle */
        .grad-circle {
            background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
            color: #fff;
        }
    </style>
@endsection

@section('body')
    <x-cosmetics.header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <main class="bg-gray-50 overflow-x-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-10 md:py-16">

            <!-- Header Section -->
            <header class="text-center mb-8 md:mb-12">
                <span class="section-kicker justify-center">Vendor Zone</span>
                <h1 class="PFDI text-3xl sm:text-4xl md:text-5xl text-gray-900 mb-4 leading-tight">
                    Seller Information Center
                </h1>
                <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                    Everything you need to know about becoming a seller, managing your store, commissions, and product guidelines on <span class="text-pink-600 font-medium">MJCheezain</span>.
                </p>
                <div class="brand-divider"></div>
            </header>

            <!-- Navigation Tabs -->
            <nav class="mb-8 md:mb-12 -mx-4 px-4 sm:mx-0 sm:px-0">
                <div class="tab-scroll flex md:flex-wrap md:justify-center gap-2 sm:gap-3 overflow-x-auto md:overflow-visible whitespace-nowrap pb-2 md:pb-0">
                    <!-- Become a Seller Tab -->
                    <button id="nav-seller" class="nav-button tab-active shrink-0 inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-pink-600 hover:border-pink-200 transition duration-150 shadow-sm text-sm md:text-base font-medium" onclick="showSection('seller-section', this)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>Become a Seller</span>
                    </button>

                    <!-- Vendor Dashboard Tab -->
                    <button id="nav-dashboard" class="nav-button shrink-0 inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-pink-600 hover:border-pink-200 transition duration-150 shadow-sm text-sm md:text-base font-medium" onclick="showSection('dashboard-section', this)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                        <span>Vendor Dashboard</span>
                    </button>

                    <!-- Commission Policy Tab -->
                    <button id="nav-commission" class="nav-button shrink-0 inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-pink-600 hover:border-pink-200 transition duration-150 shadow-sm text-sm md:text-base font-medium" onclick="showSection('commission-section', this)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Commission Policy</span>
                    </button>

                    <!-- Product Guidelines Tab -->
                    <button id="nav-guidelines" class="nav-button shrink-0 inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-pink-600 hover:border-pink-200 transition duration-150 shadow-sm text-sm md:text-base font-medium" onclick="showSection('guidelines-section', this)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Product Guidelines</span>
                    </button>
                </div>
            </nav>

            <!-- FAQ Content Sections -->

            <!-- Section 1: Become a Seller -->
            <section id="seller-section" class="faq-section mb-10">
                <h2 class="PFDI text-2xl md:text-3xl text-gray-900 inline-block">Become a Seller</h2>
                <div class="brand-divider brand-divider-left mb-6"></div>
                <div id="seller-faq" class="space-y-3 md:space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>How do I become a seller on MJCheezain?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">Joining MJCheezain as a seller is quick and simple. Click "Register as a Seller", fill out the required details, and start your online store today!</p>
                            @if($siteAccess['vendor_register'] ?? true)
                            <div class="mt-3 mb-4">
                                <a href="#" class="btn-brand-gradient inline-flex items-center justify-center w-full sm:w-auto font-semibold py-2.5 px-6 rounded-full">
                                    Register as Seller
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>What are the benefits of selling on MJCheezain?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <ul class="py-2 space-y-2">
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">✓</span>
                                    <span>Zero listing fee for first 5 products</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">✓</span>
                                    <span>Quick seller verification</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">✓</span>
                                    <span>Easy onboarding process</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">✓</span>
                                    <span>Dashboard access to manage inventory, orders, and payments</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>How long does the seller verification process take?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">Our seller verification process is designed to be quick and efficient. In most cases, accounts are verified within 24-48 hours after submitting all required documentation.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2: Vendor Dashboard -->
            <section id="dashboard-section" class="faq-section mb-10 hidden">
                <h2 class="PFDI text-2xl md:text-3xl text-gray-900 inline-block">Vendor Dashboard</h2>
                <div class="brand-divider brand-divider-left mb-6"></div>
                <div id="dashboard-faq" class="space-y-3 md:space-y-4">
                    <!-- FAQ Item 4 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>How do I access my Vendor Dashboard?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">Once your seller account is approved, you can log in to your Vendor Dashboard using your email and password. The login area is secure and accessible 24/7, giving you full control over your e-commerce operations.</p>
                            @if($siteAccess['vendor_login'] ?? true)
                            <div class="mt-3 mb-4">
                                <a href="#" class="btn-brand-gradient inline-flex items-center justify-center w-full sm:w-auto font-semibold py-2.5 px-6 rounded-full">
                                    Vendor Login
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>What can I do from my Vendor Dashboard?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">Your Vendor Dashboard is your business control room. It offers a full suite of features to manage your selling activities efficiently. The dashboard is mobile-friendly, so you can manage your business anytime, anywhere.</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3 mb-4">
                                <div class="card-hover-glow bg-white p-5 rounded-xl shadow-md border border-gray-100">
                                    <div class="grad-circle w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i data-lucide="upload" class="w-5 h-5"></i>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 text-center">Product Management</h3>
                                    <p class="text-gray-600 text-sm text-center">
                                        Upload products in bulk, edit listings, and organize your inventory with our easy-to-use tools.
                                    </p>
                                </div>

                                <div class="card-hover-glow bg-white p-5 rounded-xl shadow-md border border-gray-100">
                                    <div class="grad-circle w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 text-center">Order Tracking</h3>
                                    <p class="text-gray-600 text-sm text-center">
                                        View all orders, update shipment status, and manage returns from one centralized location.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>Can I track my sales and earnings from the dashboard?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">Yes, your Vendor Dashboard provides comprehensive sales analytics including:</p>
                            <ul class="py-2 space-y-2">
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>Real-time data on views, conversions, and earnings</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>Commission breakdown for each sale</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>Inventory alerts when stock is running low</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>Performance metrics to help you make informed business decisions</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 3: Commission Policy -->
            <section id="commission-section" class="faq-section mb-10 hidden">
                <h2 class="PFDI text-2xl md:text-3xl text-gray-900 inline-block">Commission Policy</h2>
                <div class="brand-divider brand-divider-left mb-6"></div>
                <div id="commission-faq" class="space-y-3 md:space-y-4">
                    <!-- FAQ Item 7 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>What is MJCheezain's commission structure?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">To keep things transparent, MJCheezain uses a simple commission model. Commission is deducted automatically before payment transfer, and full statements are visible in your dashboard.</p>

                            <div class="text-white p-5 rounded-xl text-center mt-3 mb-4 shadow-md" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                                <p class="font-bold text-lg sm:text-xl">You keep 95% of the sale price</p>
                                <p class="text-sm text-white/90">(excluding delivery charges)</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 8 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>Are there any hidden fees or setup costs?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">No, we believe in complete transparency. Our policy includes:</p>
                            <ul class="py-2 space-y-2">
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>First 5 products: ₹0 commission (free listings)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>After that: A flat 5% commission on each sale</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>No hidden charges or setup fees</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- FAQ Item 9 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>When will I receive my payments?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">Payments are processed on a regular schedule. You can expect to receive your earnings:</p>
                            <ul class="py-2 space-y-2">
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>Weekly for established sellers with consistent sales</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>Bi-weekly for new sellers during the first month</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>Payments are transferred directly to your registered bank account or payment method</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 4: Product Guidelines -->
            <section id="guidelines-section" class="faq-section mb-10 hidden">
                <h2 class="PFDI text-2xl md:text-3xl text-gray-900 inline-block">Product Guidelines</h2>
                <div class="brand-divider brand-divider-left mb-6"></div>
                <div id="guidelines-faq" class="space-y-3 md:space-y-4">
                    <!-- FAQ Item 10 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>What are the product guidelines for sellers?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">We maintain a strict product policy to ensure quality and trust across the platform. Violation of these rules may result in product removal or account suspension.</p>

                            <div class="bg-pink-50 border border-pink-100 p-4 sm:p-5 rounded-xl mt-3 mb-4">
                                <h3 class="font-bold text-base sm:text-lg mb-3 text-gray-900">What Sellers Must Follow:</h3>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <span class="text-pink-500 mr-2 mt-1">•</span>
                                        <span>No fake, banned, or illegal products allowed</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-pink-500 mr-2 mt-1">•</span>
                                        <span>Product titles and descriptions must be clear, honest, and specific</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-pink-500 mr-2 mt-1">•</span>
                                        <span>High-quality product images are mandatory</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-pink-500 mr-2 mt-1">•</span>
                                        <span>Prices should be competitive and accurate</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-pink-500 mr-2 mt-1">•</span>
                                        <span>Shipping must be done within the committed timeframe</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 11 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>What happens if I violate the product guidelines?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">We take guideline violations seriously to maintain a trustworthy marketplace:</p>
                            <ul class="py-2 space-y-2">
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>First offense: Product removal with explanation</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>Repeat offenses: Temporary suspension of selling privileges</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-pink-500 mr-2 mt-1">•</span>
                                    <span>Severe or repeated violations: Permanent account suspension</span>
                                </li>
                            </ul>
                            <p class="mt-2 pb-4">We always provide clear communication about any actions taken and opportunities to appeal decisions.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 12 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-md overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-5 py-4 text-left font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>Still have questions about selling on MJCheezain?</span>
                            <span class="plus-icon text-xl text-pink-500 transition-transform duration-300 transform shrink-0">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-5 bg-gray-50 text-gray-600">
                            <p class="py-2">Our seller support team is available to help you with any questions about becoming a vendor or managing your store.</p>

                            <div class="flex flex-col sm:flex-row justify-start gap-3 sm:gap-4 mt-3 mb-4">
                                <a href="mailto:sellers@mjcheezain.com" class="w-full sm:w-auto bg-white hover:bg-pink-50 text-pink-600 font-semibold py-2.5 px-6 rounded-full border border-pink-200 transition flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    Email Seller Support
                                </a>
                                <a href="tel:03XX-XXXXXXX" class="btn-brand-gradient w-full sm:w-auto font-semibold py-2.5 px-6 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    Call Vendor Helpline
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <x-footer />

    <!-- Lucide icons: loaded here (not in the shared layout) so only the pages
         that actually use icons pay for it. -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        // Function to toggle individual FAQ items (accordion)
        function toggleAccordion(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector('.plus-icon');

            // Close all other open accordions within the same section
            const sectionId = header.closest('.faq-section').id;
            document.querySelectorAll(`#${sectionId} .accordion-content.active`).forEach(item => {
                if (item !== content) {
                    item.classList.remove('active');
                    item.style.maxHeight = null;
                    item.previousElementSibling.querySelector('.plus-icon').classList.remove('rotate-45');
                }
            });

            // Toggle the clicked accordion
            if (content.classList.contains('active')) {
                content.classList.remove('active');
                content.style.maxHeight = null;
                icon.classList.remove('rotate-45');
            } else {
                content.classList.add('active');
                content.style.maxHeight = content.scrollHeight + 25 + "px";
                icon.classList.add('rotate-45');
            }
        }

        // Function to switch between main FAQ sections (tab switching)
        function showSection(sectionId, buttonElement) {
            // 1. Hide all FAQ sections and close any open accordions
            document.querySelectorAll('.faq-section').forEach(section => {
                section.classList.add('hidden');
                // Also ensure all accordions are closed when switching sections
                section.querySelectorAll('.accordion-content.active').forEach(item => {
                    item.classList.remove('active');
                    item.style.maxHeight = null;
                    item.previousElementSibling.querySelector('.plus-icon').classList.remove('rotate-45');
                });
            });

            // 2. Deactivate all buttons (remove active style)
            document.querySelectorAll('.nav-button').forEach(button => {
                button.classList.remove('tab-active');
            });

            // 3. Show the target section
            document.getElementById(sectionId).classList.remove('hidden');

            // 4. Activate the clicked button (apply active style)
            buttonElement.classList.add('tab-active');

            // Keep the active tab visible in the scrollable strip on mobile
            if (typeof buttonElement.scrollIntoView === 'function') {
                buttonElement.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
            }
        }

        // Function to handle tab & question opening based on URL
        function handleURLTabs() {
            const params = new URLSearchParams(window.location.search);
            const tab = params.get('tab');

            // Default to Become a Seller if no tab provided
            let sectionId = 'seller-section';
            let buttonId = 'nav-seller';
            let faqSelector = null;

            switch (tab) {
                case 'become-seller':
                    sectionId = 'seller-section';
                    buttonId = 'nav-seller';
                    faqSelector = '#seller-faq > div:nth-of-type(1) .accordion-header';
                    break;
                case 'vendor-dashboard':
                    sectionId = 'dashboard-section';
                    buttonId = 'nav-dashboard';
                    faqSelector = '#dashboard-faq > div:nth-of-type(2) .accordion-header';
                    break;
                case 'vendor-login':
                    sectionId = 'dashboard-section';
                    buttonId = 'nav-dashboard';
                    faqSelector = '#dashboard-faq > div:nth-of-type(1) .accordion-header';
                    break;
                case 'commission-policy':
                    sectionId = 'commission-section';
                    buttonId = 'nav-commission';
                    faqSelector = '#commission-faq > div:nth-of-type(1) .accordion-header';
                    break;
                case 'product-guidelines':
                    sectionId = 'guidelines-section';
                    buttonId = 'nav-guidelines';
                    faqSelector = '#guidelines-faq > div:nth-of-type(1) .accordion-header';
                    break;
                default:
                    sectionId = 'seller-section';
                    buttonId = 'nav-seller';
            }

            // Show correct section
            const button = document.getElementById(buttonId);
            if (button) showSection(sectionId, button);

            // Automatically open a specific FAQ if defined
            if (faqSelector) {
                const question = document.querySelector(faqSelector);
                if (question) {
                    setTimeout(() => toggleAccordion(question), 300); // small delay ensures section visible
                }
            }
        }

        // Initialize on page load
        window.onload = function() {
            handleURLTabs();
            if (window.lucide && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }
        };
    </script>
@endsection

@section('script')
@endsection
