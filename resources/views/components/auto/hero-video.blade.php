<!-- FULL-SCREEN VIDEO HERO SECTION -->
<div id="video-hero" class="relative w-full min-h-[60vh] sm:min-h-[80vh] flex items-center justify-center overflow-hidden">
    
    <!-- Video Element -->
    <video id="background-video" autoplay loop muted playsinline 
           class="absolute inset-0 w-full h-full object-cover z-0">
        <source src="{{ asset('video/auto-part.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Video Overlay -->
    <div class="absolute inset-0 z-10 bg-gradient-to-t from-black/80 via-black/40 to-black/60"></div>
    
    <!-- Hero Content -->
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
</div>