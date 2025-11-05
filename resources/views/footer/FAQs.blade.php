@extends('layouts.structure')
@section('title', 'FAQs')
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
    </style>
@endsection

@section('body')
    <div class="mx-auto px-4 py-12 md:py-20" style="max-width: 100rem;">

        <!-- Header Section -->
        <header class="text-center mb-10 md:mb-16">
            <h1 class="font-script text-5xl md:text-6xl font-normal text-gray-900 mb-4">
                Welcome to the MJCheezain Help Center
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Here you'll find answers to the most commonly asked questions related to 
                <span class="text-accent-gold font-medium">ordering</span>, 
                <span class="text-accent-gold font-medium">payments</span>, 
                <span class="text-accent-gold font-medium">shipping</span>, and 
                <span class="text-accent-gold font-medium">returns</span>.
            </p>
        </header>

        <!-- Navigation Tabs -->
        <nav class="flex flex-wrap justify-center gap-4 mb-12 border-b border-gray-200 pb-8">
            <!-- Order Tab -->
            <button id="nav-orders" class="nav-button flex items-center space-x-2 px-4 py-3 rounded-xl border border-accent-gold text-accent-gold hover:bg-yellow-50 transition duration-150 shadow-sm text-sm md:text-base" onclick="showSection('orders-section', this)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <span>Orders</span>
            </button>
            
            <!-- Payments Tab -->
            <button id="nav-payments" class="nav-button flex items-center space-x-2 px-4 py-3 rounded-xl border border-accent-gold text-accent-gold hover:bg-yellow-50 transition duration-150 shadow-sm text-sm md:text-base" onclick="showSection('payments-section', this)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-9 5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span>Payments</span>
            </button>
            
            <!-- Shipping & Delivery Tab -->
            <button id="nav-shipping" class="nav-button flex items-center space-x-2 px-4 py-3 rounded-xl border border-accent-gold text-accent-gold hover:bg-yellow-50 transition duration-150 shadow-sm text-sm md:text-base" onclick="showSection('shipping-section', this)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m4 4v10m0-2.857a4 4 0 014-4 4 4 0 014 4"></path></svg>
                <span>Shipping & Delivery</span>
            </button>
            
            <!-- Returns & Refunds Tab -->
            <button id="nav-returns" class="nav-button flex items-center space-x-2 px-4 py-3 rounded-xl border border-accent-gold text-accent-gold hover:bg-yellow-50 transition duration-150 shadow-sm text-sm md:text-base" onclick="showSection('returns-section', this)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.01 12.01 0 003 12c0 2.83 1.258 5.385 3.243 7.108a12 12 0 006.757 2.892 12 12 0 006.757-2.892C19.742 17.385 21 14.83 21 12a12.006 12.006 0 00-1.382-5.016z"></path></svg>
                <span>Returns & Refunds</span>
            </button>
        </nav>

        <!-- FAQ Content Sections -->

        <!-- Section 1: Orders -->
        <section id="orders-section" class="faq-section mb-10">
            <h2 class="text-3xl font-script text-accent-gold mb-6 border-b border-accent-gold pb-2 inline-block">Orders</h2>
            <div id="orders-faq">
                <!-- FAQ Item 1 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>How do I place an order?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">You can place an order by browsing our products, adding your desired items to the cart, and proceeding to checkout. Follow the simple steps to enter your shipping details and payment information.</p>
                    </div>
                </div>
                <!-- FAQ Item 2 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>Will I receive an SMS or email confirmation for my order?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Yes, you will receive both an email confirmation immediately after placing your order and an SMS notification when the order is dispatched for shipping.</p>
                    </div>
                </div>
                <!-- FAQ Item 3 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>How can I track my order?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Once your order is shipped, you will receive a tracking number via email and SMS. You can use this number on our partner courier's website to monitor the delivery status.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Payments -->
        <section id="payments-section" class="faq-section mb-10 hidden">
            <h2 class="text-3xl font-script text-accent-gold mb-6 border-b border-accent-gold pb-2 inline-block">Payments</h2>
            <div id="payments-faq">
                <!-- FAQ Item 4 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>Can I pay using JazzCash or Easypaisa?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Currently, we support Cash on Delivery (COD), Credit/Debit Card payments, and select digital wallet options. Please check the checkout page for the latest available methods.</p>
                    </div>
                </div>
                <!-- FAQ Item 5 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>My payment failed, but the amount was deducted. What should I do?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Please wait for 24 hours. The deducted amount is usually reversed by your bank within this period. If the issue persists, please contact our support team with your transaction ID.</p>
                    </div>
                </div>
                <!-- FAQ Item 6 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>Do I get a payment confirmation?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Yes, a payment confirmation email will be sent to your registered email address immediately upon successful transaction.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Shipping & Delivery -->
        <section id="shipping-section" class="faq-section mb-10 hidden">
            <h2 class="text-3xl font-script text-accent-gold mb-6 border-b border-accent-gold pb-2 inline-block">Shipping & Delivery</h2>
            <div id="shipping-faq">
                <!-- FAQ Item 7 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>How many days does delivery take?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Standard delivery typically takes 3-5 business days depending on your location. Expedited options may be available at checkout.</p>
                    </div>
                </div>
                <!-- FAQ Item 8 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>Is Cash on Delivery (COD) available?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Yes, Cash on Delivery is available for most locations within the country.</p>
                    </div>
                </div>
                <!-- FAQ Item 9 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>What are the delivery charges?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Delivery charges are calculated based on your order weight and delivery location, and will be displayed clearly at the checkout page before you finalize your order.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Section 4: Returns & Refunds (New Section) -->
        <section id="returns-section" class="faq-section mb-10 hidden">
            <h2 class="text-3xl font-script text-accent-gold mb-6 border-b border-accent-gold pb-2 inline-block">Returns & Refunds</h2>
            <div id="returns-faq">
                <!-- FAQ Item 10 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>What is your return policy?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Items can be returned within 7 days of delivery, provided they are unused and in their original packaging. Please see our full policy for exceptions.</p>
                    </div>
                </div>
                <!-- FAQ Item 11 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>How long does a refund take?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Once your returned item is received and inspected, the refund process will begin. It typically takes 5-10 business days for the amount to reflect in your bank account.</p>
                    </div>
                </div>
                <!-- FAQ Item 12 -->
                <div class="border-b border-gray-200">
                    <button class="accordion-header flex justify-between items-center w-full py-4 text-left font-medium text-gray-800 hover:text-accent-gold transition duration-150" onclick="toggleAccordion(this)">
                        <span>Can I exchange an item instead of returning it?</span>
                        <span class="plus-icon text-xl transition-transform duration-300 transform">+</span>
                    </button>
                    <div class="accordion-content px-4 bg-gray-50 rounded-b-lg">
                        <p class="text-gray-600">Yes, we offer exchanges subject to stock availability. Please contact our support team to arrange an exchange.</p>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

@section('script')
    <!-- JavaScript for Accordion and Tab Switching Functionality -->
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

        // Initialize the page to show the "Orders" section on load
        window.onload = function() {
            // Get the button for the 'Orders' section
            const initialButton = document.getElementById('nav-orders');
            // Explicitly set initial state (simulating a click for initial setup)
            if (initialButton) {
                showSection('orders-section', initialButton);
            }
        };
    </script>

@endsection