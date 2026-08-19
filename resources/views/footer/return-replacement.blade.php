@extends('layouts.structure')
@section('title', 'Return & Replacement Policy - MJCheezain')
@section('style')
    <style>
        .policy-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .policy-card:hover {
            border-left-color: #FF7DA0;
        }
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
    </style>
@endsection
@section('body')
    <x-cosmetics.header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <main class="overflow-x-hidden bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 sm:py-16">

            <!-- Page Hero -->
            <section class="text-center mb-10 sm:mb-16">
                <span class="section-kicker justify-center">Return &amp; Replacement</span>
                <h1 class="PFDI text-2xl sm:text-4xl text-gray-900 mt-3 mb-2 flex items-center justify-center flex-wrap gap-2">
                    <span class="icon-chip" style="width:40px;height:40px;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </span>
                    <span>Return &amp; Replacement Policy</span>
                </h1>
                <div class="brand-divider"></div>
            </section>

            <!-- Return Policy -->
            <section class="policy-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-8 mb-8 sm:mb-12">
                <h2 class="PFDI text-xl sm:text-2xl text-gray-900 mb-4">Return Policy</h2>
                <p class="mb-5 leading-relaxed text-gray-700">
                    If you want to return a product, you can submit a <strong>Return Request Form</strong> through MJ CHEEZAIN.
                </p>
                <p class="mb-5 leading-relaxed text-gray-700">
                    You can request a return <strong>within 7 days of receiving your order</strong>. After 7 days, the product will no longer be eligible for return.
                </p>
                <p class="mb-5 leading-relaxed text-gray-700">
                    Your request will be reviewed by both the <strong>Vendor and MJ CHEEZAIN Admin</strong>. The Vendor may provide their comments, but the <strong>final decision will be made by MJ CHEEZAIN Admin</strong> after reviewing both sides.
                </p>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        If the Vendor sent a <strong>wrong, incorrect, or different product</strong>, the Vendor will be responsible for the return courier charges.
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        If you want to return a product <strong>by your own choice</strong>, you can request a return if it meets our return conditions, but <strong>you will pay the full return courier charges</strong>.
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Some products cannot be returned once they have been <strong>opened or unsealed</strong>. These products will be clearly mentioned as <strong>Non-Returnable</strong> on the product page.
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        If your return request is rejected, the product will not be returned and no refund will be issued.
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        If your return is approved, the product will be returned to the Vendor.
                    </li>
                </ul>
                <p class="font-bold text-gray-900 mb-4">Please check the product's return conditions before opening or using it.</p>

                <div class="bg-gray-50 border border-gray-100 rounded-lg p-4 sm:p-5">
                    <p class="text-sm leading-relaxed text-gray-700 mb-2">
                        Sealed products cannot be returned once the original seal has been opened, removed, or tampered with. Customers must check the package and product before opening the original seal.
                    </p>
                    <p class="text-sm leading-relaxed text-gray-700">
                        If you receive a wrong product, please contact us before opening the original seal.
                    </p>
                </div>
            </section>

            <hr class="border-gray-100 my-8 sm:my-12">

            <!-- Replacement Policy -->
            <section class="policy-card bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-8 mb-10 sm:mb-16">
                <h2 class="PFDI text-xl sm:text-2xl text-gray-900 mb-4">Replacement Policy</h2>
                <p class="mb-5 leading-relaxed text-gray-700">
                    If you want to replace a product, you can submit a <strong>Replacement Request</strong> through MJ CHEEZAIN.
                </p>
                <ul class="space-y-3 mb-6">
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        You can request a replacement <strong>within 7 days of receiving your order</strong>. After 7 days, replacement will not be available.
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Replacement is available <strong>only from the same store</strong> where you originally purchased the product.
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        You can choose another product from the same store <strong>at the same price or a higher price</strong> than your original product.
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        If you choose a higher-priced product, you will pay the <strong>additional price difference</strong>.
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        <strong>No refund will be provided under the replacement option.</strong>
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        If the replacement is needed because the Vendor sent a <strong>wrong, incorrect, or different product</strong>, the Vendor will pay the replacement courier charges.
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        If you want a replacement <strong>by your own choice</strong>, you will pay the full replacement courier charges.
                    </li>
                    <li class="flex items-start text-base text-gray-700">
                        <span class="text-pink-600 mr-3 mt-0.5 flex-shrink-0" style="color:#FF7DA0;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Products that are <strong>opened, unsealed, activated, or used</strong> may not be eligible for replacement, according to the product's return conditions.
                    </li>
                </ul>
                <p class="text-sm italic text-gray-600">All replacement requests will be reviewed by the Return Manager, who will make the final decision.</p>
            </section>

        </div>
    </main>

    <x-footer />
@endsection
