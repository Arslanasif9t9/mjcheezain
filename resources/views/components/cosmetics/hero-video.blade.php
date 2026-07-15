<!-- FULL-SCREEN VIDEO HERO SECTION -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;800&display=swap" rel="stylesheet">

<style>
    /* "MJ" wordmark — antique gold on dark, Trajan-style Roman capitals (ss3 reference) */
    .mj-wordmark {
        font-family: 'Cinzel', 'Trajan Pro', 'Times New Roman', serif;
        font-weight: 800;
        letter-spacing: 0.06em;
        line-height: 1;
        background: linear-gradient(180deg,
            #F5E4A4 0%,
            #E2C878 22%,
            #C9A84C 45%,
            #A9832F 68%,
            #86651F 88%,
            #6E511A 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 1px 0 rgba(255, 240, 190, 0.35))
                drop-shadow(0 3px 6px rgba(0, 0, 0, 0.65));
    }

    /* Thin antique-gold divider under the wordmark */
    .gold-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent 0%, #C9A84C 25%, #F5E4A4 50%, #C9A84C 75%, transparent 100%);
    }

    /* Gentle fade-up entrance for the hero content */
    @keyframes hero-fade-up {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .hero-fade-up   { animation: hero-fade-up .9s cubic-bezier(.22, .61, .36, 1) both; }
    .hero-fade-up-1 { animation-delay: .15s; }
    .hero-fade-up-2 { animation-delay: .3s; }
    .hero-fade-up-3 { animation-delay: .45s; }
</style>

<div id="video-hero" class="relative w-full min-h-[85vh] sm:min-h-screen flex items-center justify-center overflow-hidden">

    <!-- Video Element -->
    <video id="background-video" autoplay loop muted playsinline
           class="absolute inset-0 w-full h-full object-cover z-0">
        <source src="{{ asset('video/cosmetics.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Video Overlay -->
    <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/80 via-black/35 to-black/55"></div>

    <!-- Hero Content: one centered composition, well clear of the fixed header -->
    <div class="relative z-20 text-center px-4 pt-24 pb-16 sm:pt-20 max-w-3xl mx-auto text-white flex flex-col items-center">

        <span class="mj-wordmark text-7xl sm:text-9xl hero-fade-up">MJ</span>

        <p class="mt-4 text-[0.65rem] sm:text-sm uppercase tracking-[0.4em] text-white/85 font-light hero-fade-up hero-fade-up-1">
            Cosmetics &amp; Beauty
        </p>

        <div class="gold-divider w-40 sm:w-56 mt-5 mb-6 hero-fade-up hero-fade-up-1"></div>

        <p class="text-sm sm:text-lg text-gray-200 mb-9 max-w-xl mx-auto drop-shadow-md font-light leading-relaxed hero-fade-up hero-fade-up-2">
            Elegance in every choice. Discover our exclusive collection of premium whitening creams, makeup, and organic skincare essentials.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto justify-center hero-fade-up hero-fade-up-3">
            <a href="#perfumes-products-section" class="px-8 py-3 bg-white text-gray-900 hover:bg-gray-100 font-semibold rounded-full transition duration-300 shadow-lg text-center text-sm sm:text-base cursor-pointer">
                Shop Collection
            </a>
            <a href="/product-listing" class="px-8 py-3 bg-white/15 hover:bg-white/25 text-white font-semibold rounded-full transition duration-300 backdrop-blur-sm border border-white/30 text-center text-sm sm:text-base">
                Browse Listings
            </a>
        </div>
    </div>

    <!-- Scroll-down hint -->
    <a href="#perfumes-products-section" class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 text-white/70 hover:text-white transition animate-bounce" aria-label="Scroll down">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
        </svg>
    </a>
</div>
