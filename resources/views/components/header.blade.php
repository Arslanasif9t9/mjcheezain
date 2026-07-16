@props(['user', 'profile', 'dashboardPage', 'imgPath'])

{{-- Shared site header (navbar + mobile drawer + scroll animation) --}}
<x-site-header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <!-- Main Content Area (home hero) -->
    <main class="max-w-full mx-auto px-2 md:px-6 lg:px-8 py-4">

        <!-- Title Section -->
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6 flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex flex-col">
                <img src="{{ asset('img/short_logo.jpeg') }}" class="w-32 h-8 object-contain" style="margin-top: -7px;">
                <p class="text-gray-500" style="font-size: 0.6rem; font-weight: 400; margin-top: -2px;">Elegance in every choice</p>
            </div>
            <span class="hidden sm:inline text-gray-300 font-light">|</span>
            <span>MJ Cheezain</span>
        </h1>

        <!-- Filter Tags (Horizontally scrollable on Mobile, wrapped on Desktop) -->
        <div class="mb-10">
            <div class="flex flex-row overflow-x-auto pb-2 gap-2 whitespace-nowrap md:flex-wrap md:overflow-x-visible md:pb-0 scrollbar-none">
                <button class="btn-brand-gradient px-4 py-1.5 text-sm font-medium rounded-full transition duration-200 shadow-sm">All</button>
                <a href="/cosmetics" class="no-underline"><button class="px-4 py-1.5 text-sm font-medium rounded-full bg-white text-gray-700 hover:text-pink-600 hover:border-pink-200 border border-gray-200 transition duration-200 shadow-sm"><span class="PFDI">MJ</span> Cosmetics</button></a>
                <a href="/auto-parts" class="no-underline"><button class="px-4 py-1.5 text-sm font-medium rounded-full bg-white text-gray-700 hover:text-pink-600 hover:border-pink-200 border border-gray-200 transition duration-200 shadow-sm"><span class="PFDI">Auto</span> parts</button></a>
            </div>
            <!-- Golden Divider Line -->
            <div class="h-[3px] bg-gradient-to-r from-yellow-600 via-yellow-400 to-yellow-600 rounded-full w-full mt-4 shadow-sm"></div>
        </div>

        <!-- Content Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 md:gap-6 px-1 md:px-0">

            <!-- 1. Large Featured Card (Responsive height, whole card clickable) -->
            <a href="/cosmetics" class="block lg:col-span-2 relative h-[250px] sm:h-[350px] lg:h-[503px] rounded-2xl overflow-hidden shadow-xl group cursor-pointer transition transform duration-300">
                <img src="{{ asset('img/hero-1.jpeg') }}"
                     alt="Luxury Fragrance" class="w-full h-full object-cover brightness-95 group-hover:scale-110 transition duration-500">

                <!-- Content Overlay -->
                <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-8 bg-gradient-to-t from-black/60 via-black/20 to-transparent">
                    <h2 class="text-2xl sm:text-4xl font-light text-white mb-3 tracking-wide drop-shadow-lg">
                        Indulge in Elegance!
                    </h2>
                    <button type="button" onclick="location.href='/cosmetics'" class="w-fit px-5 py-2.5 bg-white text-gray-900 text-sm font-bold rounded-xl shadow-lg hover:bg-gray-200 transition duration-300">
                        Shop Now
                    </button>
                </div>
            </a>

            <!-- 2. Horizontal scrollable list on mobile, stacked column on desktop -->
            <div class="lg:col-span-1 flex overflow-x-auto gap-4 scrollbar-none snap-x snap-mandatory lg:grid lg:grid-cols-1 lg:overflow-x-visible lg:gap-6 py-2 px-1" id="mobile-scroll-container">

                <!-- Top Small Card: Lipstick (tall Amazon-style card on mobile, whole card clickable) -->
                <a href="/products/all-page?category={{ urlencode('Cosmetics') }}" class="block scroll-animate opacity-0 translate-y-12 transition-all duration-700 ease-out relative h-[430px] sm:h-[480px] lg:h-[240px] w-[80vw] lg:w-auto flex-shrink-0 snap-start rounded-2xl overflow-hidden shadow-lg group cursor-pointer">
                    <img src="{{ asset('img/hero-2.jpeg') }}"
                         alt="Red Lipstick" class="w-full h-full object-cover brightness-95 group-hover:scale-110 transition duration-500">
                    <div class="absolute top-0 left-0 p-4">
                        <h3 class="text-sm sm:text-lg font-semibold text-gray-800 bg-white/80 backdrop-blur-sm px-3 py-1 rounded-lg">
                            Matte Finish
                        </h3>
                    </div>
                </a>

                <!-- Bottom Small Card: Apparel/Model (tall Amazon-style card on mobile, whole card clickable) -->
                <a href="/products/all-page?category={{ urlencode("Women's Fashion") }}" class="block scroll-animate opacity-0 translate-y-12 transition-all duration-700 ease-out relative h-[430px] sm:h-[480px] lg:h-[240px] w-[80vw] lg:w-auto flex-shrink-0 snap-start rounded-2xl overflow-hidden shadow-lg group cursor-pointer" style="transition-delay: 150ms;">
                    <img src="{{ asset('img/hero-3.jpeg') }}"
                         alt="Summer Apparel" class="w-full h-full object-cover brightness-95 group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/50 to-transparent">
                        <h3 class="text-lg sm:text-xl font-bold text-white drop-shadow-md">
                            The Coastal Look
                        </h3>
                    </div>
                </a>

            </div>

        </div>

    </main>

    <script>
        // Scroll Entrance Animation using IntersectionObserver
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', 'translate-y-12');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            const animElements = document.querySelectorAll('.scroll-animate');
            animElements.forEach(el => observer.observe(el));

            // Mobile hero cards auto-scroll carousel: every few seconds the row
            // gently slides to the other card (ping-pong left <-> right).
            // Pauses when the user touches or scrolls it, then resumes.
            const container = document.getElementById('mobile-scroll-container');
            if (container && window.innerWidth < 1024) {
                const SLIDE_EVERY_MS = 3500;
                const RESUME_AFTER_MS = 5000;
                let slideDirection = 1;
                let autoTimer = null;
                let resumeTimer = null;

                function autoSlide() {
                    const maxScroll = container.scrollWidth - container.clientWidth;
                    if (maxScroll <= 0) return;
                    const target = slideDirection > 0 ? maxScroll : 0;
                    try {
                        container.scrollTo({ left: target, behavior: 'smooth' });
                    } catch (e) {
                        container.scrollLeft = target;
                    }
                    slideDirection *= -1;
                }

                function startAutoSlide() {
                    if (!autoTimer) autoTimer = setInterval(autoSlide, SLIDE_EVERY_MS);
                }

                function pauseAutoSlide() {
                    clearInterval(autoTimer);
                    autoTimer = null;
                    clearTimeout(resumeTimer);
                    resumeTimer = setTimeout(startAutoSlide, RESUME_AFTER_MS);
                }

                container.addEventListener('touchstart', pauseAutoSlide, { passive: true });
                container.addEventListener('wheel', pauseAutoSlide, { passive: true });
                startAutoSlide();
            }
        });
    </script>
