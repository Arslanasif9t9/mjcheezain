@extends('layouts.structure')
@section('title', 'Privacy & Policy - MJCheezain | Your Data Protection')
@section('style')
    <style>
        /* Policy card left-border accent on hover */
        .policy-section {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .policy-section:hover {
            border-left-color: #FF7DA0;
        }
        /* Soft gradient icon chip */
        .section-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(255, 125, 160, .12);
            color: #FF7DA0;
            flex-shrink: 0;
        }
        .contact-channel {
            transition: all 0.3s ease;
        }
        .contact-channel:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px -6px rgba(255, 125, 160, 0.25), 0 4px 10px rgba(0, 0, 0, 0.04);
        }
        .social-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            flex-shrink: 0;
        }
        .brand-text-gradient {
            background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
@endsection
@section('body')
    <x-cosmetics.header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <main class="overflow-x-hidden bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 sm:py-16">

            <!-- Header Section -->
            <section class="mb-10 sm:mb-16 text-center">
                <span class="section-kicker justify-center">Privacy & Policy</span>
                <h1 class="PFDI text-2xl sm:text-4xl text-gray-900 mt-3 mb-2 flex items-center justify-center flex-wrap gap-2">
                    <span class="inline-flex items-center justify-center rounded-xl flex-shrink-0" style="width:40px;height:40px;background:rgba(255,125,160,.12);color:#FF7DA0;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6-6h6m6 0h-6m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span>Your Data, Our Responsibility</span>
                </h1>
                <div class="brand-divider"></div>
                <p class="text-base sm:text-lg italic text-gray-500 mt-5">We Value Your Privacy and Strive to Protect it at Every Step.</p>
            </section>

            <!-- Vendor Privacy Policy -->
            <section class="mb-10 sm:mb-16">
                <h2 class="PFDI text-xl sm:text-2xl text-gray-900 mb-4">Vendor Privacy Policy</h2>
                <p class="mb-5 leading-relaxed text-gray-700">
                    At MJ CHEEZAIN, we respect your privacy and work to keep your personal and business information safe.
                </p>
                <ul class="space-y-1">
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We may collect your <strong>name, phone number, email, business details, payment information, and verification information</strong> when you create and use a Vendor account.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We use this information to <strong>create and manage your Vendor account</strong>, verify your identity, process orders, and provide marketplace services.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Customers will only see the Vendor&rsquo;s approved store and product information, such as the store name, logo, product details, images, prices, and other information needed for shopping. The Vendor&rsquo;s <strong>private and personal information will not be shown to customers</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We may use your information to <strong>process Vendor payments, payouts, returns, replacements, and other marketplace activities</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We may contact you about <strong>orders, account updates, important policy changes, security, or marketplace-related information</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We may use necessary information to <strong>prevent fraud, protect customers and Vendors, and keep MJ CHEEZAIN secure</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We do not sell your personal information to third parties for their own marketing purposes.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Your information may be shared with <strong>payment providers, delivery partners, verification services, or other service providers</strong> when necessary to provide marketplace services.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We take reasonable steps to <strong>protect your personal and business information</strong> from unauthorized access or misuse.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>MJ CHEEZAIN is responsible for protecting Vendor account and personal information through appropriate security measures. Vendors are responsible for keeping their passwords and OTPs confidential and informing MJ CHEEZAIN if they notice any unauthorized access.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We may keep certain information for as long as necessary to <strong>provide our services, meet legal requirements, resolve disputes, and maintain business records</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>By creating a Vendor account, you agree to the collection and use of your information as described in this <strong>Vendor Privacy Policy</strong>.</span>
                    </li>
                </ul>
            </section>

            <hr class="border-gray-100 my-8 sm:my-12">

            <!-- Customer Privacy Policy -->
            <section class="policy-section bg-white p-5 sm:p-8 rounded-xl shadow-sm border border-gray-100 mb-8 sm:mb-12">
                <h2 class="PFDI text-lg sm:text-xl text-gray-900 mb-4">Customer Privacy Policy</h2>
                <ul class="space-y-1">
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We collect basic information such as your <strong>name, phone number, email, delivery address, and order details</strong> to provide our services.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We use your information to <strong>process orders, payments, deliveries, returns, replacements, and customer support</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Your personal information is <strong>kept private</strong> and is not shown publicly to Vendors or other customers.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We may share only the <strong>necessary information</strong> with trusted payment providers, delivery partners, and service providers to complete your order and provide our services.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>MJ CHEEZAIN takes reasonable steps to <strong>protect your personal information</strong> and uses it only as needed to provide, improve, and secure our marketplace services.</span>
                    </li>
                </ul>
            </section>

            <hr class="border-gray-100 my-8 sm:my-12">

            <!-- Cookie Policy Section -->
            <section class="mb-10 sm:mb-16">
                <div class="text-center mb-8">
                    <h2 class="PFDI text-2xl sm:text-3xl text-gray-900">Cookie Policy</h2>
                    <div class="brand-divider"></div>
                </div>
                <p class="text-center text-gray-700 mb-2 max-w-2xl mx-auto">
                    Cookies are small files stored in your browser that help MJ CHEEZAIN remember certain information and preferences.
                </p>
                <p class="text-center text-gray-700 mb-2 max-w-2xl mx-auto">
                    We use necessary cookies for login, shopping carts, language, preferences, and basic website functions.
                </p>
                <p class="text-center text-gray-700 mb-2 max-w-2xl mx-auto">
                    If you save a delivery address in your account, you will not need to enter it again when placing another order. Your saved address will remain securely linked to your account.
                </p>
                <p class="text-center text-gray-700 mb-10 max-w-2xl mx-auto">
                    We may use cookies to understand how customers use our website and improve their overall shopping experience.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 text-center mb-10">
                    <div class="card-hover-glow bg-white p-6 border border-gray-100 rounded-xl shadow-sm">
                        <span class="inline-flex items-center justify-center rounded-xl mb-3" style="width:60px;height:60px;background:rgba(255,125,160,.12);color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </span>
                        <p class="font-bold text-gray-900">Shopping Experience</p>
                        <p class="text-sm mt-2 text-gray-600">Remember what you've added to your cart</p>
                    </div>

                    <div class="card-hover-glow bg-white p-6 border border-gray-100 rounded-xl shadow-sm">
                        <span class="inline-flex items-center justify-center rounded-xl mb-3" style="width:60px;height:60px;background:rgba(255,125,160,.12);color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v10m2 4v.01m-2-4h2m4-2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </span>
                        <p class="font-bold text-gray-900">Preferences & Login</p>
                        <p class="text-sm mt-2 text-gray-600">Remember your login status and preferences</p>
                    </div>

                    <div class="card-hover-glow bg-white p-6 border border-gray-100 rounded-xl shadow-sm">
                        <span class="inline-flex items-center justify-center rounded-xl mb-3" style="width:60px;height:60px;background:rgba(255,125,160,.12);color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l2-2m2 4l-2-2m2 2l-2-2m2 2h6m6 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <p class="font-bold text-gray-900">Performance Tracking</p>
                        <p class="text-sm mt-2 text-gray-600">Track performance for improvement</p>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-100 p-4 rounded-xl max-w-2xl mx-auto">
                    <p class="text-gray-700 text-center">
                        <span class="font-medium text-pink-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1 align-text-bottom" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#FF7DA0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Note:
                        </span>
                        You can choose to accept or reject cookies at any time through your browser settings. However, disabling cookies may limit some website features like saved carts or faster login.
                    </p>
                </div>

                <p class="text-center font-medium mt-6 text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1 align-text-bottom" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#FF7DA0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    We use cookies responsibly — and never for spying or unauthorized data sharing.
                </p>
            </section>

            <hr class="border-gray-100 my-8 sm:my-12">

            <!-- Disclaimer Section -->
            <section class="policy-section bg-gray-50 p-5 sm:p-8 rounded-xl border border-gray-100 shadow-sm mb-8 sm:mb-12">
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    <div class="section-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h2 class="PFDI text-xl sm:text-2xl text-gray-900 mb-4">Disclaimer</h2>
                        <p class="text-gray-700 mb-4">
                            MJ CHEEZAIN is a marketplace that connects customers with independent Vendors.
                        </p>
                        <p class="text-gray-700 mb-4">
                            Product details, prices, images, availability, and other product information are provided by the respective Vendors.
                        </p>
                        <p class="text-gray-700 mb-4">
                            Customers are encouraged to check product details and conditions before placing an order.
                        </p>
                        <p class="text-gray-700">
                            MJ CHEEZAIN works to provide a safe and reliable shopping experience, but Vendor-related product information and services remain the responsibility of the respective Vendor.
                        </p>
                    </div>
                </div>
            </section>

            <hr class="border-gray-100 my-8 sm:my-12">

            <!-- Contact Section -->
            <section class="mb-8 text-center">
                <div class="mb-8">
                    <h2 class="PFDI text-2xl sm:text-3xl text-gray-900">Let's Stay Connected</h2>
                    <div class="brand-divider"></div>
                </div>
                <p class="mb-10 max-w-lg mx-auto text-gray-700">
                    Our seller support team is available to help you with any questions about becoming a vendor or managing your store.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-10">
                    <!-- WhatsApp -->
                    <div class="contact-channel bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <span class="text-green-500 inline-block mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.893 20.37A8.995 8.995 0 0112 21a9 9 0 01-9-9 8.995 8.995 0 011.63-5.37l-1.38-5.38 5.38 1.38A8.995 8.995 0 0112 3a9 9 0 019 9 8.995 8.995 0 01-1.63 5.37l1.38 5.38-5.38-1.38z" /></svg>
                        </span>
                        <p class="font-bold text-lg mb-1 text-gray-900">WhatsApp Support</p>
                        <p class="text-sm text-gray-500 mb-2">Get instant support through live chat</p>
                        <a href="#" class="text-green-600 font-medium text-sm hover:text-green-800">Click to Chat →</a>
                    </div>

                    <!-- Phone -->
                    <div class="contact-channel bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <span class="text-[#E85D85] inline-block mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </span>
                        <p class="font-bold text-lg mb-1 text-gray-900">Phone Support</p>
                        <p class="text-sm text-gray-500 mb-2">Call us for voice support</p>
                        <a href="tel:03XX-XXXXXXX" class="text-[#E85D85] font-medium text-sm hover:text-[#C94A72]">03XX-XXXXXXX →</a>
                    </div>

                    <!-- Email -->
                    <div class="contact-channel bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <span class="inline-block mb-3" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-2 1h-2a2 2 0 00-2 2v4a2 2 0 002 2h2a2 2 0 002-2v-4a2 2 0 00-2-2z" /></svg>
                        </span>
                        <p class="font-bold text-lg mb-1 text-gray-900">Email Support</p>
                        <p class="text-sm text-gray-500 mb-2">For business inquiries and feedback</p>
                        <a href="mailto:support@mjcheezain.com" class="font-medium text-sm hover:text-pink-700 break-words" style="color:#FF7DA0;">support@mjcheezain.com →</a>
                    </div>
                </div>

                <!-- Social Media -->
                <h3 class="PFDI text-base sm:text-lg text-gray-900 mb-4">Social Media Presence</h3>
                <div class="flex flex-wrap justify-center items-center gap-3 mb-8">
                    <a href="#" class="social-icon bg-[#E85D85] hover:bg-[#C94A72]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                        </svg>
                    </a>
                    <a href="#" class="social-icon bg-pink-600 hover:bg-pink-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </a>
                    <a href="#" class="social-icon bg-red-600 hover:bg-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </a>
                    <span class="text-gray-500 text-sm flex items-center">(Coming Soon)</span>
                </div>

                <!-- Office Address -->
                <h3 class="PFDI text-base sm:text-lg text-gray-900 mb-2">Office Address</h3>
                <div class="bg-gray-50 border border-gray-100 p-4 rounded-xl max-w-md mx-auto">
                    <p class="text-gray-700 text-sm">
                        If MJCheezain opens a physical office or warehouse, its location will be officially listed here for visits, returns, or business meetings.
                    </p>
                </div>

                <p class="mt-12 sm:mt-16 text-lg sm:text-xl italic PFDI brand-text-gradient">
                    "We believe your <strong>data is your property</strong> — and our <strong>duty is to protect it.</strong>"
                </p>
            </section>

        </div>
    </main>

    <x-footer />
@endsection
