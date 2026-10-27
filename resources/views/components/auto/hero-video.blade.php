
<!-- FULL-SCREEN VIDEO HERO SECTION — Perfume -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;800&display=swap" rel="stylesheet">

<style>
    .mj-auto-wordmark {
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

    .perfume-title {
        font-family: 'Cinzel', 'Trajan Pro', 'Times New Roman', serif;
        font-weight: 700;
        letter-spacing: 0.06em;
    }
</style>

<div id="video-hero"
     class="relative w-full flex-1 min-h-[420px] flex items-end justify-center overflow-hidden">

    <!-- Background Video — unchanged -->
    <video id="background-video"
           autoplay loop muted playsinline
           class="absolute inset-0 w-full h-full object-contain z-0">
        <source src="{{ asset('video/perfume3.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Video Overlay — unchanged -->
    <!-- <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/80 via-black/40 to-black/60"></div> -->
    <div class="absolute inset-0 z-10 "></div>

    <!-- Perfume Hero Content -->
    <div class="relative z-20 text-center px-4 py-12 max-w-3xl mx-auto text-white flex flex-col items-center">

        <!-- <span class="mj-auto-wordmark text-5xl sm:text-7xl mb-5">
            MJ
        </span> -->

        <!-- <p class="text-[11px] sm:text-sm uppercase tracking-[0.35em] text-gray-300 mb-4">
            Perfumes &amp; Fragrances
        </p> -->

        <!-- <h1 class="perfume-title text-3xl sm:text-5xl lg:text-6xl text-white drop-shadow-lg uppercase leading-tight mb-5">
            Discover Your<br class="hidden sm:block"> Signature Scent
        </h1> -->

        <!-- <p class="text-sm sm:text-lg text-gray-200 mb-8 max-w-xl mx-auto drop-shadow-md font-light leading-relaxed">
            A premium fragrance, ready for you. Explore our featured perfume and find your next signature scent.
        </p> -->

        <!-- Single Product Button -->
        <a href="{{ url('/product/222') }}"
           class="inline-flex items-center justify-center gap-3 px-8 py-3.5 bg-white text-gray-900 hover:bg-[#E2C878] font-semibold rounded-full transition duration-300 shadow-lg text-center text-sm sm:text-base">
            Shop Perfume
            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </a>

    </div>
</div>
