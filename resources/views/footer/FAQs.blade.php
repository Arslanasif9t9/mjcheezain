@extends('layouts.structure')
@section('title', 'FAQs')
@section('style')
    <style>
        /* Style for the accordion content, initially hidden */
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out, padding 0.3s ease-in-out;
        }
        .accordion-content.active {
            height: auto;
            padding-top: -1rem;
            padding-bottom: 1rem;
        }
        /* Style for the plus icon rotation when active */
        .plus-icon.rotate-45 {
            transform: rotate(45deg);
        }
        /* Brand-gradient active state for FAQ tab pills */
        .nav-button {
            flex: 0 0 auto;
        }
        .nav-button.active-tab {
            background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 6px 16px -6px rgba(255, 125, 160, 0.55);
        }
        .nav-button.active-tab:hover {
            color: #fff;
        }
        /* Hide horizontal scrollbar on the mobile tab strip */
        .faq-tab-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .faq-tab-scroll::-webkit-scrollbar {
            display: none;
        }
    </style>
@endsection

@section('body')
    <x-cosmetics.header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <main class="bg-gray-50 overflow-x-hidden">

        <!-- Hero / Header Section -->
        <section class="bg-gradient-to-b from-pink-50/70 via-white to-gray-50 border-b border-gray-100">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-12 pb-10 md:pt-20 md:pb-16 text-center">
                <span class="section-kicker justify-center">Help Center</span>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mt-3 mb-4 PFDI">
                    Welcome to the MJCheezain Help Center
                </h1>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                    Here you'll find answers to the most commonly asked questions related to
                    <span class="text-pink-600 font-medium">ordering</span>,
                    <span class="text-pink-600 font-medium">payments</span>,
                    <span class="text-pink-600 font-medium">shipping</span>, and
                    <span class="text-pink-600 font-medium">returns</span>.
                </p>
                <div class="brand-divider"></div>
            </div>
        </section>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 md:py-14">

            <!-- Navigation Tabs -->
            <nav class="faq-tab-scroll flex md:flex-wrap md:justify-center gap-2 sm:gap-3 mb-8 md:mb-12 overflow-x-auto whitespace-nowrap -mx-4 px-4 sm:mx-0 sm:px-0 pb-2 border-b border-gray-200 md:pb-6">
                <!-- Order Tab -->
                <button id="nav-orders" class="nav-button flex items-center gap-2 px-4 sm:px-5 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-pink-600 hover:border-pink-200 transition duration-150 shadow-sm text-sm md:text-base font-medium" onclick="showSection('orders-section', this)">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span>Orders</span>
                </button>

                <!-- Payments Tab -->
                <button id="nav-payments" class="nav-button flex items-center gap-2 px-4 sm:px-5 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-pink-600 hover:border-pink-200 transition duration-150 shadow-sm text-sm md:text-base font-medium" onclick="showSection('payments-section', this)">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-9 5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span>Payments</span>
                </button>

                <!-- Shipping & Delivery Tab -->
                <button id="nav-shipping" class="nav-button flex items-center gap-2 px-4 sm:px-5 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-pink-600 hover:border-pink-200 transition duration-150 shadow-sm text-sm md:text-base font-medium" onclick="showSection('shipping-section', this)">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m4 4v10m0-2.857a4 4 0 014-4 4 4 0 014 4"></path></svg>
                    <span>Shipping & Delivery</span>
                </button>

                <!-- Returns & Refunds Tab -->
                <button id="nav-returns" class="nav-button flex items-center gap-2 px-4 sm:px-5 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:text-pink-600 hover:border-pink-200 transition duration-150 shadow-sm text-sm md:text-base font-medium" onclick="showSection('returns-section', this)">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.01 12.01 0 003 12c0 2.83 1.258 5.385 3.243 7.108a12 12 0 006.757 2.892 12 12 0 006.757-2.892C19.742 17.385 21 14.83 21 12a12.006 12.006 0 00-1.382-5.016z"></path></svg>
                    <span>Returns & Refunds</span>
                </button>
            </nav>

            <!-- FAQ Content Sections -->

            <!-- Section 1: Orders -->
            <section id="orders-section" class="faq-section mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 PFDI">Orders<span class="brand-divider brand-divider-left"></span></h2>
                <div id="orders-faq" class="space-y-3 sm:space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>How do I place an order?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>To place an order on MJCheezain.com:<br>
                                &nbsp; 1. Browse the product categories or use the search bar to find the item you want.<br>
                                &nbsp; 2. Click on the product to view details.<br>
                                &nbsp; 3. Select the quantity or variant (if applicable), then click "Add to Cart."<br>
                                &nbsp; 4. Go to your cart and click "Checkout."<br>
                                &nbsp; 5. Enter your shipping address and select your preferred payment method.<br>
                                &nbsp; 6. Confirm your order.<br>
                                Once your order is placed, you'll receive a confirmation via SMS and email.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 2 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>Will I receive an SMS or email confirmation for my order?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>Yes. Once your order is successfully placed and confirmed, you will receive:<br>
                                &nbsp; • An SMS confirmation with your order ID.<br>
                                &nbsp; • An email receipt with product details, payment method, and estimated delivery time.<br>
                                Please make sure your phone number and email address are entered correctly during checkout.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 3 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>How can I track my order?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>To track your order:<br>&nbsp; 1. Go to the "Track Your Order" page on our website.<br>&nbsp; 2. Enter your Order ID and registered email or phone number.<br>&nbsp; 3. You'll see real-time updates including "Processing," "Shipped," and "Out for Delivery."<br>If you face any issues, contact our support team.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2: Payments -->
            <section id="payments-section" class="faq-section mb-10 hidden">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 PFDI">Payments<span class="brand-divider brand-divider-left"></span></h2>
                <div id="payments-faq" class="space-y-3 sm:space-y-4">
                    <!-- FAQ Item 4 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>Can I pay using JazzCash or Easypaisa?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>Yes. MJCheezain supports multiple payment methods including:<br>&nbsp; • JazzCash<br>&nbsp; • Easypaisa<br>&nbsp; • Bank Transfer<br>&nbsp; • Cash on Delivery (COD)<br>You'll be given full instructions at checkout based on the method you select.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 5 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>My payment failed, but the amount was deducted. What should I do?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>If your transaction failed but the amount was deducted:<br>&nbsp; • Don’t worry — in most cases, the payment gateway auto-refunds the amount within 24 hours.<br>&nbsp; • If the amount is not refunded automatically, please:<br>&nbsp;&nbsp;&nbsp; 1. Take a screenshot of the deduction.<br>&nbsp;&nbsp;&nbsp; 2. Contact our support team via WhatsApp or email with proof.<br>We will verify and issue the refund manually if required.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 6 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>Do I get a payment confirmation?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>Yes, after your payment is successfully processed:<br>&nbsp; • You'll receive an instant SMS.<br>&nbsp; • An email receipt will also be sent to your registered email address.<br>This confirmation includes the order ID, payment amount, and method used.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 3: Shipping & Delivery -->
            <section id="shipping-section" class="faq-section mb-10 hidden">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 PFDI">Shipping & Delivery<span class="brand-divider brand-divider-left"></span></h2>
                <div id="shipping-faq" class="space-y-3 sm:space-y-4">
                    <!-- FAQ Item 7 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>How many days does delivery take?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>Our standard delivery timeframe is:<br>&nbsp; • 2–5 working days for major cities.<br>&nbsp; • Up to 7 days for remote or rural areas.<br>We always strive for faster deliveries via trusted courier partners.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 8 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>Is Cash on Delivery (COD) available?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>Yes, COD is available in most areas across Pakistan.<br><br>You can choose COD during checkout if you prefer to pay after receiving the item.<br><br>Note: For high-value orders or customized items, partial advance payment may be requested.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 9 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>What are the delivery charges?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>Delivery charges are calculated based on:<br>&nbsp; • Your location<br>&nbsp; • The size and weight of the product<br><br>You'll see the exact delivery cost at the checkout before confirming your order.<br><br>Occasionally, we offer free delivery promotions, so keep an eye out!</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 4: Returns & Refunds -->
            <section id="returns-section" class="faq-section mb-10 hidden">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 PFDI">Returns & Refunds<span class="brand-divider brand-divider-left"></span></h2>
                <div id="returns-faq" class="space-y-3 sm:space-y-4">
                    <!-- FAQ Item 10 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>What is your return policy?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>Items can be returned within 7 days of delivery, provided they are unused and in their original packaging. Please see our full policy for exceptions.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 11 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>How long does a refund take?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>Once your returned product is approved and received:<br>&nbsp; • Your refund will be processed within 3–5 working days<br>&nbsp; • Refund will be sent to your JazzCash, Easypaisa, or bank account<br>&nbsp; • You'll be notified via SMS and email when your refund is released.</p>
                        </div>
                    </div>
                    <!-- FAQ Item 12 -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <button class="accordion-header flex justify-between items-center gap-3 w-full px-4 sm:px-6 py-4 text-left text-sm sm:text-base font-medium text-gray-800 hover:text-pink-600 transition duration-150" onclick="toggleAccordion(this)">
                            <span>Can I exchange an item instead of returning it?</span>
                            <span class="plus-icon text-xl text-pink-500 shrink-0 transition-transform duration-300 transform">+</span>
                        </button>
                        <div class="accordion-content px-4 sm:px-6 text-sm sm:text-base text-gray-600 leading-relaxed">
                            <p>Yes, we offer exchanges subject to stock availability. Please contact our support team to arrange an exchange.</p>
                        </div>
                    </div>
                </div>
            </section>

        </div>

    </main>

    <x-footer />

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

            // 2. Deactivate all buttons (remove active brand-gradient style)
            document.querySelectorAll('.nav-button').forEach(button => {
                button.classList.remove('active-tab');
            });

            // 3. Show the target section
            document.getElementById(sectionId).classList.remove('hidden');

            // 4. Activate the clicked button (apply brand-gradient active style)
            buttonElement.classList.add('active-tab');

            // Keep the active tab visible in the scrollable strip on mobile
            if (typeof buttonElement.scrollIntoView === 'function') {
                buttonElement.scrollIntoView({ block: 'nearest', inline: 'nearest' });
            }
        }

        // Function to handle tab & question opening based on URL
        function handleURLTabs() {
            const params = new URLSearchParams(window.location.search);
            const tab = params.get('tab');

            // Default to Orders if no tab provided
            let sectionId = 'orders-section';
            let buttonId = 'nav-orders';
            let faqSelector = null;

            switch (tab) {
                case 'track-your-order':
                    sectionId = 'orders-section';
                    buttonId = 'nav-orders';
                    faqSelector = '#orders-faq > div:nth-of-type(3) .accordion-header'; // ✅ fixed selector
                    break;
                case 'return-refund-policy':
                    sectionId = 'returns-section';
                    buttonId = 'nav-returns';
                    faqSelector = '#returns-faq > div:nth-of-type(1) .accordion-header';
                    break;
                case 'shipping-policy':
                    sectionId = 'shipping-section';
                    buttonId = 'nav-shipping';
                    faqSelector = '#shipping-faq > div:nth-of-type(1) .accordion-header';
                    break;
                case 'secure-payment-assurance':
                    sectionId = 'payments-section';
                    buttonId = 'nav-payments';
                    faqSelector = '#payments-faq > div:nth-of-type(3) .accordion-header'; // ✅ fixed selector
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
