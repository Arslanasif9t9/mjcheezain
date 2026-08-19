@extends('layouts.structure')
@section('title', 'Legal Policies - MJCheezain')
@section('style')
    <style>
        /* Policy card left-border accent on hover */
        .policy-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .policy-card:hover {
            border-left-color: #FF7DA0;
        }
        /* Soft gradient icon chip */
        .icon-chip {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(255, 125, 160, .12);
            color: #FF7DA0;
            flex-shrink: 0;
        }
        .icon-chip-lg {
            width: 60px;
            height: 60px;
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

            <!-- Page Hero -->
            <section class="text-center mb-10 sm:mb-16">
                <span class="section-kicker justify-center">Legal Policies</span>
                <h1 class="PFDI text-2xl sm:text-4xl text-gray-900 mt-3 mb-2 flex items-center justify-center flex-wrap gap-2">
                    <span class="icon-chip" style="width:40px;height:40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6-6h6m6 0h-6m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span>Terms &amp; Conditions</span>
                </h1>
                <div class="brand-divider"></div>
                <p class="text-base sm:text-lg italic text-gray-500 mt-5">Please read these terms carefully before using MJ CHEEZAIN.</p>
            </section>

            <!-- Customer Terms & Conditions -->
            <section class="policy-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-8 mb-8 sm:mb-12">
                <h2 class="PFDI text-xl sm:text-2xl text-gray-900 mb-4">Customer Terms &amp; Conditions</h2>
                <ul class="space-y-1">
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Customers should provide <strong>correct and up-to-date information</strong> when creating an account.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Customers are responsible for keeping their <strong>account and login information secure</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Customers can shop freely across MJ CHEEZAIN and should use the marketplace in a <strong>safe and lawful manner</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Before placing an order, customers can review the <strong>product details, price, quantity, and return conditions</strong> to make the right choice.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Customers should provide <strong>correct delivery information</strong> to help ensure a smooth delivery.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Customers agree to complete their payment using the <strong>available payment methods</strong> shown at checkout.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Customers can request a <strong>return or replacement within the applicable time period</strong>, according to our Return &amp; Replacement Policy.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>If a return or replacement is requested by the customer&rsquo;s own choice, the <strong>applicable courier charges will be paid by the customer</strong>. If the issue is caused by the Vendor, the applicable courier charges will be handled by the Vendor.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Customers can contact <strong>MJ CHEEZAIN for help</strong> with orders, returns, replacements, payments, or other marketplace-related questions.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>We ask customers to provide <strong>honest information and genuine requests</strong> so we can provide better service to everyone.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>MJ CHEEZAIN may take appropriate action if an account is used for <strong>fraud, abuse, or activities that may harm other customers, Vendors, or the marketplace</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Customers agree to follow the <strong>MJ CHEEZAIN Privacy Policy, Cookies Policy, Return &amp; Replacement Policy, Payment Policy, and applicable laws</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>MJ CHEEZAIN is committed to providing a <strong>safe, fair, and smooth shopping experience</strong> and will handle customer concerns according to our marketplace policies.</span>
                    </li>
                </ul>
            </section>

            <hr class="border-gray-100 my-8 sm:my-12">

            <!-- Vendor Terms & Conditions -->
            <section class="policy-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-8 mb-8 sm:mb-12">
                <h2 class="PFDI text-xl sm:text-2xl text-gray-900 mb-4">Vendor Terms &amp; Conditions</h2>
                <ul class="space-y-1">
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Vendors must provide <strong>accurate and complete account information</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Vendors may sell only <strong>legal and approved products</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Selling <strong>illegal drugs, addictive substances, controlled substances, or prohibited products</strong> is strictly forbidden. Any violation may result in <strong>immediate account suspension or termination</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Vendors are responsible for the <strong>quality, authenticity, and accuracy of their product descriptions</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Vendors must not list <strong>wrong, fake, counterfeit, or misleading products</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Vendors must <strong>process and ship orders within the required time</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>If a return or replacement is caused by a <strong>Vendor error</strong>, the applicable courier charges will be paid by the Vendor.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span><strong>MJ CHEEZAIN will charge no commission on your sales for the first 3 months.</strong> After the first 3 months, the applicable commission will be charged according to our Vendor Commission Policy.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Vendors must not <strong>force or encourage customers to make payments or communicate outside the MJ CHEEZAIN platform</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>If a Vendor violates marketplace policies, MJ CHEEZAIN may <strong>remove listings, restrict, suspend, or terminate the Vendor account</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span>Vendors must follow <strong>MJ CHEEZAIN&rsquo;s privacy policies, marketplace policies, and all applicable laws and regulations</strong>.</span>
                    </li>
                    <li class="flex items-start text-base text-gray-700 mb-3">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0 font-bold" style="color:#FF7DA0;">&#8226;</span>
                        <span><strong>Vendor payments will remain on hold until the applicable return period for the order has been completed.</strong> After the return period ends, the eligible amount may be released according to the payout schedule. The held amount will remain visible in the Vendor Portal until it is released.</span>
                    </li>
                </ul>
            </section>

            <hr class="border-gray-100 my-8 sm:my-12">

            <!-- Cookie Policy -->
            <section class="mb-10 sm:mb-16">
                <div class="text-center mb-8">
                    <h2 class="PFDI text-2xl sm:text-3xl text-gray-900">Cookie Policy</h2>
                    <div class="brand-divider"></div>
                </div>
                <p class="text-center text-gray-700 mb-10 max-w-2xl mx-auto">
                    MJCheezain uses cookies to enhance your browsing experience. Cookies help us remember what you've added to your cart, your login status, and browsing preferences. You can accept or reject cookies anytime from your browser settings.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 text-center">
                    <div class="card-hover-glow bg-white p-6 border border-gray-100 rounded-xl shadow-sm">
                        <span class="icon-chip icon-chip-lg mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </span>
                        <p class="font-bold text-gray-900">Shopping Experience</p>
                    </div>

                    <div class="card-hover-glow bg-white p-6 border border-gray-100 rounded-xl shadow-sm">
                        <span class="icon-chip icon-chip-lg mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v10m2 4v.01m-2-4h2m4-2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </span>
                        <p class="font-bold text-gray-900">Preferences & Login</p>
                    </div>

                    <div class="card-hover-glow bg-white p-6 border border-gray-100 rounded-xl shadow-sm">
                        <span class="icon-chip icon-chip-lg mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l2-2m2 4l-2-2m2 2l-2-2m2 2h6m6 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <p class="font-bold text-gray-900">Performance Tracking</p>
                    </div>
                </div>
            </section>

            <hr class="border-gray-100 my-8 sm:my-12">

            <!-- Disclaimer -->
            <section class="policy-card bg-gray-50 rounded-xl border border-gray-100 shadow-sm p-5 sm:p-8 mb-10 sm:mb-16">
                <div class="flex items-start gap-4">
                    <span class="icon-chip">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </span>
                    <div class="min-w-0">
                        <h2 class="PFDI text-xl sm:text-2xl text-gray-900 mb-4">Disclaimer</h2>
                        <p class="text-sm leading-relaxed text-gray-700">
                            <strong class="font-bold block mb-2 text-gray-900">Important Notice:</strong> The content, product listings, and vendor information on MJCheezain are provided 'as-is' for general information only.
                        </p>
                        <p class="text-sm leading-relaxed mb-4 text-gray-700">
                            While we strive for 100% accuracy, product images may differ slightly, and availability can change without notice.
                        </p>
                        <p class="text-sm leading-relaxed text-gray-700">
                            MJCheezain is not liable for vendor mistakes, courier delays, or buyer misuse.
                        </p>
                    </div>
                </div>
            </section>

            <hr class="border-gray-100 my-8 sm:my-12">

            <!-- Contact -->
            <section class="text-center mb-8">
                <div class="mb-8">
                    <h2 class="PFDI text-2xl sm:text-3xl text-gray-900">Let's Stay Connected</h2>
                    <div class="brand-divider"></div>
                </div>
                <p class="mb-10 max-w-lg mx-auto text-gray-700">
                    If you have privacy-related questions or need assistance, contact us through the following channels.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                    <div class="card-hover-glow bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <span class="icon-chip icon-chip-lg mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.893 20.37A8.995 8.995 0 0112 21a9 9 0 01-9-9 8.995 8.995 0 011.63-5.37l-1.38-5.38 5.38 1.38A8.995 8.995 0 0112 3a9 9 0 019 9 8.995 8.995 0 01-1.63 5.37l1.38 5.38-5.38-1.38z" /></svg>
                        </span>
                        <p class="font-bold text-lg mb-1 text-gray-900">WhatsApp Support</p>
                        <a href="#" class="text-sm text-gray-500 hover:text-pink-500 transition">Click to Chat</a>
                    </div>

                    <div class="card-hover-glow bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <span class="icon-chip icon-chip-lg mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        </span>
                        <p class="font-bold text-lg mb-1 text-gray-900">Phone Support</p>
                        <p class="text-sm text-gray-500">03XX-XXXXXXX</p>
                    </div>

                    <div class="card-hover-glow bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <span class="icon-chip icon-chip-lg mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-2 1h-2a2 2 0 00-2 2v4a2 2 0 002 2h2a2 2 0 002-2v-4a2 2 0 00-2-2z" /></svg>
                        </span>
                        <p class="font-bold text-lg mb-1 text-gray-900">Email Support</p>
                        <p class="text-sm text-gray-500 break-words">support@mjcheezain.com</p>
                    </div>
                </div>

                <p class="mt-8 text-sm text-gray-500">Office Address: Coming Soon</p>

                <p class="mt-12 sm:mt-16 text-lg sm:text-xl italic PFDI brand-text-gradient">
                    "We believe our <strong>data is your property</strong> — and our <strong>duty is to protect it.</strong>"
                </p>
            </section>

        </div>
    </main>

    <x-footer />
@endsection
