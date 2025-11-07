@extends('layouts.structure')
@section('title', 'Vendor Zone')
@section('style')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cream-bg': '#fcfaf7',
                        'accent-gold': '#a0885a', // Closest hex color to the image
                    },
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif'],
                        'script': ['Playfair Display', 'serif'], // Using Playfair Display as a similar elegant serif font
                    },
                }
            }
        }
    </script>
    <style>
        /* Fallback for the custom script font to look more elegant */
        .font-script {
            font-family: 'Playfair Display', serif;
        }
        /* Style for the accordion content, initially hidden */
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out, padding 0.3s ease-in-out;
        }
        .accordion-content.active {
            /* max-height: 500px; Adjust based on expected content size */
            height: auto;
            padding-top: -1rem;
            padding-bottom: 1rem;
        }
        /* Style for the plus icon rotation when active */
        .plus-icon.rotate-45 {
            transform: rotate(45deg);
        }
        .seller-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 15px;
            background: rgba(160, 136, 90, 0.1);
            color: #a0885a;
        }
        
        .seller-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .seller-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-left-color: #a0885a;
        }
    </style>
@endsection

@section('body')
    <div class="max-w-5xl mx-auto px-4 py-12 md:py-20">

        <!-- Header Section -->
        <header class="text-center mb-10 md:mb-16">
            <h1 class="font-script text-3xl md:text-5xl font-bold text-gray-900 mb-4">
                Seller Information Center
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Everything you need to know about becoming a seller, managing your store, commissions, and product guidelines on <span class="text-accent-gold font-medium">MJCheezain</span>.
            </p>
        </header>

        <!-- Navigation Tabs -->
        <nav class="flex flex-wrap justify-center gap-4 mb-12 border-b border-gray-200 pb-8">
            <!-- Become a Seller Tab -->
            <button id="nav-seller" class="nav-button flex items-center space-x-2 px-5 py-2 rounded-3xl border border-accent-gold bg-accent-gold text-white transition duration-150 shadow-sm text-sm md:text-base" onclick="showSection('seller-section', this)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>Become a Seller</span>
            </button>
            
            <!-- Vendor Dashboard Tab -->
            <button id="nav-dashboard" class="nav-button flex items-center space-x-2 px-5 py-2 rounded-3xl border border-accent-gold text-accent-gold hover:bg-yellow-50 transition duration-150 shadow-sm text-sm md:text-base" onclick="showSection('dashboard-section', this)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                <span>Vendor Dashboard</span>
            </button>
            
            <!-- Commission Policy Tab -->
            <button id="nav-commission" class="nav-button flex items-center space-x-2 px-5 py-2 rounded-3xl border border-accent-gold text-accent-gold hover:bg-yellow-50 transition duration-150 shadow-sm text-sm md:text-base" onclick="showSection('commission-section', this)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Commission Policy</span>
            </button>
            
            <!-- Product Guidelines Tab -->
            <button id="nav-guidelines" class="nav-button flex items-center space-x-2 px-5 py-2 rounded-3xl border border-accent-gold text-accent-gold hover:bg-yellow-50 transition duration-150 shadow-sm text-sm md:text-base" onclick="showSection('guidelines-section', this)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Product Guidelines</span>
            </button>
        </nav>

        <!-- FAQ Content Sections -->

        <!-- Section 1: Become a Seller -->
        <section id="seller-section" class="faq-section mb-10">
            <h2 class="text-3xl font-bold font-script text-accent-gold mb-6 border-b border-accent-gold pb-2 inline-block">Become a Seller</h2>
            <div id="seller-faq">
                <!-- FAQ Item 1 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>How do I become a seller on MJCheezain?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">Joining MJCheezain as a seller is quick and simple. Click "Register as a Seller", fill out the required details, and start your online store today!</p>
                        <div class="mt-4">
                            <a href="#" class="inline-block bg-accent-gold hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                Register as Seller
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Item 2 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>What are the benefits of selling on MJCheezain?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <ul class="py-2 space-y-2">
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">✓</span>
                                <span>Zero listing fee for first 5 products</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">✓</span>
                                <span>Quick seller verification</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">✓</span>
                                <span>Easy onboarding process</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">✓</span>
                                <span>Dashboard access to manage inventory, orders, and payments</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- FAQ Item 3 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>How long does the seller verification process take?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">Our seller verification process is designed to be quick and efficient. In most cases, accounts are verified within 24-48 hours after submitting all required documentation.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Vendor Dashboard -->
        <section id="dashboard-section" class="faq-section mb-10 hidden">
            <h2 class="text-3xl font-bold font-script text-accent-gold mb-6 border-b border-accent-gold pb-2 inline-block">Vendor Dashboard</h2>
            <div id="dashboard-faq">
                <!-- FAQ Item 4 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>How do I access my Vendor Dashboard?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">Once your seller account is approved, you can log in to your Vendor Dashboard using your email and password. The login area is secure and accessible 24/7, giving you full control over your e-commerce operations.</p>
                        <div class="mt-4">
                            <a href="#" class="inline-block bg-accent-gold hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                                Vendor Login
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Item 5 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>What can I do from my Vendor Dashboard?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">Your Vendor Dashboard is your business control room. It offers a full suite of features to manage your selling activities efficiently. The dashboard is mobile-friendly, so you can manage your business anytime, anywhere.</p>
                        
                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div class="seller-card bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <div class="seller-icon">
                                    <i class="fas fa-upload text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2 text-center">Product Management</h3>
                                <p class="text-gray-600 text-sm text-center">
                                    Upload products in bulk, edit listings, and organize your inventory with our easy-to-use tools.
                                </p>
                            </div>
                            
                            <div class="seller-card bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                <div class="seller-icon">
                                    <i class="fas fa-clipboard-list text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2 text-center">Order Tracking</h3>
                                <p class="text-gray-600 text-sm text-center">
                                    View all orders, update shipment status, and manage returns from one centralized location.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Item 6 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>Can I track my sales and earnings from the dashboard?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">Yes, your Vendor Dashboard provides comprehensive sales analytics including:</p>
                        <ul class="py-2 space-y-2">
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>Real-time data on views, conversions, and earnings</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>Commission breakdown for each sale</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>Inventory alerts when stock is running low</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>Performance metrics to help you make informed business decisions</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Commission Policy -->
        <section id="commission-section" class="faq-section mb-10 hidden">
            <h2 class="text-3xl font-bold font-script text-accent-gold mb-6 border-b border-accent-gold pb-2 inline-block">Commission Policy</h2>
            <div id="commission-faq">
                <!-- FAQ Item 7 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>What is MJCheezain's commission structure?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">To keep things transparent, MJCheezain uses a simple commission model. Commission is deducted automatically before payment transfer, and full statements are visible in your dashboard.</p>
                        
                        <div class="bg-accent-gold text-white p-4 rounded-lg text-center mt-4">
                            <p class="font-bold text-xl">You keep 95% of the sale price</p>
                            <p class="text-sm">(excluding delivery charges)</p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Item 8 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>Are there any hidden fees or setup costs?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">No, we believe in complete transparency. Our policy includes:</p>
                        <ul class="py-2 space-y-2">
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>First 5 products: ₹0 commission (free listings)</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>After that: A flat 5% commission on each sale</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>No hidden charges or setup fees</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- FAQ Item 9 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>When will I receive my payments?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">Payments are processed on a regular schedule. You can expect to receive your earnings:</p>
                        <ul class="py-2 space-y-2">
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>Weekly for established sellers with consistent sales</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>Bi-weekly for new sellers during the first month</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>Payments are transferred directly to your registered bank account or payment method</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4: Product Guidelines -->
        <section id="guidelines-section" class="faq-section mb-10 hidden">
            <h2 class="text-3xl font-bold font-script text-accent-gold mb-6 border-b border-accent-gold pb-2 inline-block">Product Guidelines</h2>
            <div id="guidelines-faq">
                <!-- FAQ Item 10 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>What are the product guidelines for sellers?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">We maintain a strict product policy to ensure quality and trust across the platform. Violation of these rules may result in product removal or account suspension.</p>
                        
                        <div class="bg-yellow-50 p-4 rounded-lg mt-4">
                            <h3 class="font-bold text-lg mb-3 text-gray-800">What Sellers Must Follow:</h3>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <span class="text-accent-gold mr-2 mt-1">•</span>
                                    <span>No fake, banned, or illegal products allowed</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-accent-gold mr-2 mt-1">•</span>
                                    <span>Product titles and descriptions must be clear, honest, and specific</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-accent-gold mr-2 mt-1">•</span>
                                    <span>High-quality product images are mandatory</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-accent-gold mr-2 mt-1">•</span>
                                    <span>Prices should be competitive and accurate</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-accent-gold mr-2 mt-1">•</span>
                                    <span>Shipping must be done within the committed timeframe</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Item 11 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>What happens if I violate the product guidelines?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">We take guideline violations seriously to maintain a trustworthy marketplace:</p>
                        <ul class="py-2 space-y-2">
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>First offense: Product removal with explanation</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>Repeat offenses: Temporary suspension of selling privileges</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-accent-gold mr-2 mt-1">•</span>
                                <span>Severe or repeated violations: Permanent account suspension</span>
                            </li>
                        </ul>
                        <p class="mt-2">We always provide clear communication about any actions taken and opportunities to appeal decisions.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 12 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>Still have questions about selling on MJCheezain?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="py-2">Our seller support team is available to help you with any questions about becoming a vendor or managing your store.</p>
                        
                        <div class="flex flex-col sm:flex-row justify-start gap-4 mt-4">
                            <a href="mailto:sellers@mjcheezain.com" class="bg-white hover:bg-gray-50 text-accent-gold font-bold py-2 px-4 rounded-lg border border-accent-gold transition flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Email Seller Support
                            </a>
                            <a href="tel:03XX-XXXXXXX" class="bg-accent-gold hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                Call Vendor Helpline
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>




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
                button.classList.remove('bg-accent-gold', 'text-white');
                button.classList.add('text-accent-gold', 'hover:bg-yellow-50');
            });

            // 3. Show the target section
            document.getElementById(sectionId).classList.remove('hidden');

            // 4. Activate the clicked button (apply active style)
            buttonElement.classList.add('bg-accent-gold', 'text-white');
            buttonElement.classList.remove('text-accent-gold', 'hover:bg-yellow-50');
        }

        // Function to handle tab & question opening based on URL
        function handleURLTabs() {
            const params = new URLSearchParams(window.location.search);
            const tab = params.get('tab');

            // Default to Orders if no tab provided
            let sectionId = 'seller-section';
            let buttonId = 'nav-seller';
            let faqSelector = null;

            switch (tab) {
                case 'become-seller':
                    sectionId = 'seller-section';
                    buttonId = 'nav-seller';
                    faqSelector = '#seller-faq > div:nth-of-type(1) .accordion-header'; // ✅ fixed selector
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
                    faqSelector = '#commission-faq > div:nth-of-type(1) .accordion-header'; // ✅ fixed selector
                    break;
                case 'product-guidelines':
                    sectionId = 'guidelines-section';
                    buttonId = 'nav-guidelines';
                    faqSelector = '#guidelines-faq > div:nth-of-type(1) .accordion-header'; // ✅ fixed selector
                    break;
                default:
                    sectionId = 'orders-section';
                    buttonId = 'nav-orders';
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
        };
    </script>
@endsection

@section('script')
    <!-- JavaScript for Accordion and Tab Switching Functionality -->
    

@endsection