<!-- FULL-SCREEN VIDEO HERO SECTION (Coming Soon mode — fills the viewport below the header) -->
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
    .coming-soon-title {
        font-family: 'Cinzel', 'Trajan Pro', 'Times New Roman', serif;
        font-weight: 700;
        letter-spacing: 0.18em;
    }
    @media (prefers-reduced-motion: no-preference) {
        .cs-fade {
            opacity: 0;
            transform: translateY(14px);
            animation: cs-enter 0.9s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }
        .cs-fade.cs-d1 { animation-delay: 0.1s; }
        .cs-fade.cs-d2 { animation-delay: 0.3s; }
        .cs-fade.cs-d3 { animation-delay: 0.5s; }
    }
    @keyframes cs-enter {
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div id="video-hero" class="relative w-full flex-1 min-h-[420px] flex items-center justify-center overflow-hidden">

    <!-- Video Element -->
    <video id="background-video" autoplay loop muted playsinline
           class="absolute inset-0 w-full h-full object-cover z-0">
        <source src="{{ asset('video/cosmetics.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Video Overlay -->
    <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/80 via-black/40 to-black/60"></div>

    <!-- COMING SOON Content -->
    <div class="relative z-20 text-center px-4 max-w-3xl mx-auto text-white flex flex-col items-center">
        <span class="mj-auto-wordmark cs-fade cs-d1 text-5xl sm:text-7xl mb-4">MJ</span>
        <p class="cs-fade cs-d2 text-[11px] sm:text-sm uppercase tracking-[0.35em] text-gray-300 mb-3">Auto Parts &amp; Accessories</p>
        <h1 class="coming-soon-title cs-fade cs-d3 text-3xl sm:text-5xl lg:text-6xl text-white drop-shadow-lg uppercase">
            Coming&nbsp;Soon
        </h1>
    </div>

    {{-- ============================================================
         COMING SOON MODE — original hero content preserved below.
         To restore: delete the "COMING SOON Content" block above and
         un-comment this block (also restore min-h-[60vh] sm:min-h-[80vh]
         on the #video-hero div in place of flex-1 min-h-[420px]).

    <div class="relative z-20 text-center px-4 max-w-3xl mx-auto text-white flex flex-col items-center">
        <span class="PFDI text-4xl sm:text-6xl text-white font-extrabold tracking-widest mb-2 animate-pulse">MJ</span>
        <h1 class="font-serif text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight mb-4 drop-shadow-lg">
            Premium Auto Parts & Accessories
        </h1>
        <p class="text-sm sm:text-lg text-gray-200 mb-8 max-w-xl mx-auto drop-shadow-md font-light leading-relaxed">
            Engineered for excellence. Experience superior performance, reliability, and precision with our premium selection of genuine global auto parts.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto justify-center">
            <button onclick="document.getElementById('sidebar').scrollIntoView({ behavior: 'smooth', block: 'start' })" class="px-8 py-3 bg-white text-gray-900 hover:bg-gray-100 font-semibold rounded-full transition duration-300 shadow-lg text-center text-sm sm:text-base cursor-pointer">
                Browse Parts
            </button>
            <a href="/" class="px-8 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-full transition duration-300 backdrop-blur-sm border border-white/30 text-center text-sm sm:text-base">
                Go to Homepage
            </a>
        </div>
    </div>
    ============================================================ --}}
</div>
