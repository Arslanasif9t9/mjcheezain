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
</style>

<div id="video-hero" class="relative w-full min-h-[60vh] sm:min-h-[80vh] flex items-center justify-center overflow-hidden">

    <!-- Video Element -->
    <video id="background-video" autoplay loop muted playsinline
           class="absolute inset-0 w-full h-full object-cover z-0">
        <source src="{{ asset('video/cosmetics.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Video Overlay -->
    <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/80 via-black/40 to-black/60"></div>

    <!-- MJ Wordmark — pinned to the top center of the hero -->
    <div class="absolute top-6 sm:top-10 left-0 right-0 z-20 text-center pointer-events-none">
        <span class="mj-wordmark text-6xl sm:text-8xl">MJ</span>
    </div>

    <!-- Hero Content -->
    <div class="relative z-20 text-center px-4 max-w-3xl mx-auto text-white flex flex-col items-center">
        <p class="text-sm sm:text-lg text-gray-200 mb-8 max-w-xl mx-auto drop-shadow-md font-light leading-relaxed">
            Elegance in every choice. Discover our exclusive collection of premium whitening creams, makeup, and organic skincare essentials.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto justify-center">
            <a href="#gym-products-section" class="hidden sm:inline-block px-8 py-3 bg-white text-gray-900 hover:bg-gray-100 font-semibold rounded-full transition duration-300 shadow-lg text-center text-sm sm:text-base cursor-pointer">
                Shop Collection
            </a>
            <a href="/product-listing" class="px-8 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-full transition duration-300 backdrop-blur-sm border border-white/30 text-center text-sm sm:text-base">
                Browse Listings
            </a>
        </div>
    </div>
</div>
