<footer class="bg-gray-50 border-t border-gray-100 mt-10 sm:mt-16 relative">
    <div class="h-[3px] w-full bg-gradient-to-r from-[#FF7DA0] to-[#FFC275]"></div>
    <div class="max-w-7xl mx-auto py-8 sm:py-12 px-4 sm:px-6 lg:px-8">

        @php
            // Admin Controls (Account Access): with vendor accounts switched off the
            // whole Vendor Zone column goes, so the site reads as "no vendor side at
            // all" — the xl grid drops to 4 columns so nothing is left hanging.
            $ftVendorAny = $siteAccess['vendor_any'] ?? true;
        @endphp

        <!-- Top Half: compact 2 columns on mobile, expanding on larger screens -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 {{ $ftVendorAny ? 'xl:grid-cols-5' : 'xl:grid-cols-4' }} gap-6 sm:gap-10">
            
            <!-- Column 1: About MJ CHEEZAIN -->
            <div>
                <h3 class="footer-heading text-base sm:text-xl font-serif font-bold text-gray-900 mb-3 sm:mb-6">About MJ CHEEZAIN</h3>
                <ul class="space-y-3">
                    <li><a href="/about" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">About Us</a></li>
                    <li><a href="/future-vision" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Future Vision</a></li>
                    <li><a href="/contact-us" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Contact Us</a></li>
                </ul>
            </div>

            <!-- Column 2: Customer Service -->
            <div>
                <h3 class="footer-heading text-base sm:text-xl font-serif font-bold text-gray-900 mb-3 sm:mb-6">Customer Service</h3>
                <ul class="space-y-3">
                    <li><a href="/FAQs" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">FAQs</a></li>
                    <li><a href="/FAQs?tab=track-your-order" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Track Your Order</a></li>
                    <li><a href="/FAQs?tab=return-refund-policy" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Return & Refund Policy</a></li>
                    <li><a href="/FAQs?tab=shipping-policy" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Shipping Policy</a></li>
                    <li><a href="/FAQs?tab=secure-payment-assurance" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Secure Payment Assurance</a></li>
                </ul>
            </div>

            @if($ftVendorAny)
            <!-- Column 3: Vendor Zone -->
            <div>
                <h3 class="footer-heading text-base sm:text-xl font-serif font-bold text-gray-900 mb-3 sm:mb-6">Vendor Zone</h3>
                <ul class="space-y-3">
                    @if($siteAccess['vendor_register'] ?? true)
                    <li><a href="/vendor-zone?tab=become-seller" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Become a Seller</a></li>
                    @endif
                    <li><a href="/vendor-zone?tab=vendor-dashboard" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Vendor Dashboard Features</a></li>
                    @if($siteAccess['vendor_login'] ?? true)
                    <li><a href="/vendor-zone?tab=vendor-login" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Vendor Login</a></li>
                    @endif
                    <li><a href="/vendor-zone?tab=commission-policy" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Commission Policy</a></li>
                    <li><a href="/vendor-zone?tab=product-guidelines" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Product Guidelines</a></li>
                    {{-- <li><a href="/vendor-zone?tab=vendor-terms-consitions" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Vendor Terms & Conditions</a></li> --}}
                </ul>
            </div>
            @endif
            
            <!-- Column 4: Legal & Social (Combined/Split in the original design) -->
            <!-- Legal & Policies (Upper part of column 4 in mobile/desktop) -->
                <div>
                    <h3 class="footer-heading text-base sm:text-xl font-serif font-bold text-gray-900 mb-3 sm:mb-6 mt-0">Legal & Policies</h3>
                    <ul class="space-y-3">
                        <li><a href="/legal-policies" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Terms & Conditions</a></li>
                        <li><a href="/privacy-policy" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Privacy Policy</a></li>
                        <li><a href="/cookie-policy" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Cookie Policy</a></li>
                        <li><a href="/disclaimer" class="text-gray-600 hover:text-pink-600 hover:pl-1 transition-all duration-200 text-sm inline-block">Disclaimer</a></li>
                    </ul>
                </div>
            <div class="grid grid-cols-1 gap-10 col-span-2 md:col-span-2 lg:col-span-1">
                <!-- Social & Contact (Lower part of column 4) -->
                <div>
                    <h3 class="footer-heading text-base sm:text-xl font-serif font-bold text-gray-900 mb-3 sm:mb-6">Social & Contact</h3>
                    
                    <!-- Social Icons (Using SVG placeholders for Instagram, Facebook, etc.) -->
                    <div class="flex space-x-3 mb-8">
                        <!-- Instagram -->
                        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-700 hover:text-white hover:border-transparent hover:bg-gradient-to-r hover:from-[#FF7DA0] hover:to-[#FFC275] transition duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v6m0-6V4m0 10h6m-6 0H6m0 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </a>
                        <!-- Facebook -->
                        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-700 hover:text-white hover:border-transparent hover:bg-gradient-to-r hover:from-[#FF7DA0] hover:to-[#FFC275] transition duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.875v-6.987H8.252V12h2.186V9.465c0-2.031 1.237-3.136 3.053-3.136.877 0 1.637.155 1.867.224v2.103h-1.246c-1.218 0-1.45.578-1.45 1.419V12h2.597l-.42 2.788h-2.177v7.26C18.343 21.128 22 16.991 22 12z"></path></svg>
                        </a>
                        <!-- Youtube (Simulated) -->
                        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-700 hover:text-white hover:border-transparent hover:bg-gradient-to-r hover:from-[#FF7DA0] hover:to-[#FFC275] transition duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M20.8 5.6c-.3-.9-1-1.6-1.9-1.9C17.2 3 12 3 12 3s-5.2 0-6.9.7c-.9.3-1.6 1-1.9 1.9C3 7 3 12 3 12s0 5.2.7 6.9c.3.9 1 1.6 1.9 1.9C6.8 21 12 21 12 21s5.2 0 6.9-.7c.9-.3 1.6-1 1.9-1.9.7-1.7.7-6.9.7-6.9s0-5.2-.7-6.9zM10 16.5V7.5L16 12l-6 4.5z"></path></svg>
                        </a>
                        <!-- Placeholder icon for another social site (e.g., Pinterest/TikTok) -->
                        <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-700 hover:text-white hover:border-transparent hover:bg-gradient-to-r hover:from-[#FF7DA0] hover:to-[#FFC275] transition duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm0-4h-2V7h2v8z"></path></svg>
                        </a>
                    </div>

                    <!-- Subscription Input -->
                    <div class="space-y-3">
                        <label for="email-subscribe" class="text-gray-700 text-sm block">Join Our Luxury Circle</label>
                        <div class="relative flex items-center bg-white rounded-full border border-gray-200 focus-within:border-transparent focus-within:ring-2 focus-within:ring-[#FFC275]/60 transition duration-200 shadow-sm pl-4 pr-1.5 py-1.5">
                            <input type="email" id="email-subscribe" placeholder="Your Email Address"
                                   class="w-full bg-transparent text-gray-900 placeholder-gray-500 focus:outline-none text-sm">
                            <button type="button" id="footer-subscribe-btn" class="btn-brand-gradient p-2 rounded-full flex items-center justify-center shadow-sm">
                                <!-- Right Arrow Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </button>
                        </div>
                        <p id="footer-subscribe-msg" class="text-xs mt-1 hidden"></p>
                    </div>
                    @once
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var btn = document.getElementById('footer-subscribe-btn');
                            if (!btn) return;
                            var input = document.getElementById('email-subscribe');
                            var msg = document.getElementById('footer-subscribe-msg');
                            function show(text, ok) {
                                if (!msg) return;
                                msg.textContent = text;
                                msg.classList.remove('hidden');
                                msg.style.color = ok ? '#16a34a' : '#dc2626';
                            }
                            btn.addEventListener('click', function () {
                                var email = (input.value || '').trim();
                                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                                    show('Please enter a valid email address.', false);
                                    return;
                                }
                                btn.disabled = true;
                                fetch('/subscribe', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ email: email })
                                })
                                .then(function (r) { return r.json().catch(function () { return {}; }); })
                                .then(function (data) {
                                    show((data && data.message) ? data.message : 'Thanks for subscribing!', true);
                                    input.value = '';
                                })
                                .catch(function () { show('Something went wrong. Please try again.', false); })
                                .finally(function () { btn.disabled = false; });
                            });
                        });
                    </script>
                    @endonce
                    
                    <!-- Contact Email -->
                    <div class="mt-6">
                        <p class="text-gray-700 text-sm">support@mjcheezain.com</p>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Bottom Section: Copyright & Developer Credit -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-center text-gray-500 text-xs">
                &copy; {{ date('Y') }} MJ Cheezain. All rights reserved. | Developed by
                <span class="text-custom-gold font-medium">Arslan Asif</span>.
            </p>
        </div>

        
    </div>
</footer>









{{-- <footer class="bg-gray-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
            <!-- Column 1: About MJ Group -->
            <div>
                <h3 class="text-lg font-bold mb-2">About MJ Cheezain</h3>
                <ul>
                    <li><a href="{{ asset('html_pages/about-us.html') }}" target="_blank" class="hover:text-orange-400 text-[12px]">About Us</a></li>
                    <li><a href="{{ asset('html_pages/future-vision.html') }}" target="_blank" class="hover:text-orange-400 text-[12px]">Future Vision</a></li>
                    <li><a href="{{ asset('html_pages/contact-us.html') }}" target="_blank" class="hover:text-orange-400 text-[12px]">Contact Us</a></li>
                </ul>
            </div>

            <!-- Column 2: Customer Service -->
            <div>
                <h3 class="text-lg font-bold mb-2 text-[14px]">Customer Service</h3>
                <ul>
                    <li><a href="{{ asset('html_pages/FAQs.html') }}" class="hover:text-orange-400 text-[12px]">FAQs</a></li>
                    <li><a href="{{ asset('html_pages/track-order.html') }}" class="hover:text-orange-400 text-[12px]">Track Your Order</a></li>
                    <li><a href="{{ asset('html_pages/refund-policy.html') }}" class="hover:text-orange-400 text-[12px]">Return & Refund Policy</a></li>
                    <li><a href="{{ asset('html_pages/shipping-policy.html') }}" class="hover:text-orange-400 text-[12px]">Shipping Policy</a></li>
                    <li><a href="{{ asset('html_pages/payment-assurance.html') }}" class="hover:text-orange-400 text-[12px]">Secure Payment Assurance</a></li>
                </ul>
            </div>

            <!-- Column 3: Vendor Zone -->
            <div>
                <h3 class="text-lg font-bold mb-2 text-[14px]">Vendor Zone</h3>
                <ul>
                    <li><a href="{{ asset('html_pages/vendor-zone.html') }}" class="hover:text-orange-400 text-[12px]">Become a Seller</a></li>
                    <li><a href="{{ asset('html_pages/vendor-zone.html') }}" class="hover:text-orange-400 text-[12px]">Vendor Login</a></li>
                    <li><a href="{{ asset('html_pages/vendor-zone.html') }}" class="hover:text-orange-400 text-[12px]">Vendor Dashboard Features</a></li>
                    <li><a href="{{ asset('html_pages/vendor-zone.html') }}" class="hover:text-orange-400 text-[12px]">Commission Policy</a></li>
                    <li><a href="{{ asset('html_pages/vendor-zone.html') }}" class="hover:text-orange-400 text-[12px]">Product Guidelines</a></li>
                    <li><a href="{{ asset('html_pages/vendor-zone-T&P.html') }}" class="hover:text-orange-400 text-[12px]">Vendor Terms & Conditions</a></li>
                </ul>
            </div>

            <!-- Column 4: Legal & Policies -->
            <div>
                <h3 class="text-lg font-bold mb-2 text-[14px]">Legal & Policies</h3>
                <ul>
                    <li><a href="{{ asset('html_pages/legal-policies.html') }}" class="hover:text-orange-400 text-[12px]">Terms & Conditions</a></li>
                    <li><a href="{{ asset('html_pages/legal-policies.html') }}" class="hover:text-orange-400 text-[12px]">Privacy Policy</a></li>
                    <li><a href="{{ asset('html_pages/legal-policies.html') }}" class="hover:text-orange-400 text-[12px]">Cookie Policy</a></li>
                    <li><a href="{{ asset('html_pages/legal-policies.html') }}" class="hover:text-orange-400 text-[12px]">Disclaimer</a></li>
                </ul>
            </div>

            <!-- Column 5: Social & Contact -->
            <div>
                <h3 class="text-lg font-bold mb-2 text-[14px]">Social & Contact</h3>
                <div class="flex space-x-4 mb-4">
                    <a href="#" class="text-2xl hover:text-orange-400"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-2xl hover:text-orange-400"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-2xl hover:text-orange-400"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-2xl hover:text-orange-400"><i class="fab fa-youtube"></i></a>
                </div>
                <p class="text-sm text-gray-400">Download our mobile app:</p>
                <div class="flex space-x-2 mt-2">
                    <a href="#"><img src="https://www.pakwheels.com/assets/google-play-badge-6c1f7d2f.png" alt="Google Play" class="h-10"></a>
                    <a href="#"><img src="https://www.pakwheels.com/assets/app-store-badge-0b4b1b0e.png" alt="App Store" class="h-10"></a>
                </div>
            </div>
        </div>

        <!-- Powered By / Legal Line -->
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
            <p>© 2025 MJ Group. All rights reserved.</p>
            <p>Powered by MJ Technologies | Built with ❤️ by <a href="https://www.facebook.com/arslan.asif.70412" class="text-orange-400 hover:underline">Arslan Ahmad</a></p>
            <p>All trademarks are property of respective owners</p>
        </div>
    </div>
</footer> --}}
