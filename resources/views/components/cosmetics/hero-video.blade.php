<!-- FULL-SCREEN VIDEO HERO SECTION -->
    <div id="video-hero" class="relative w-full min-h-[90vh] overflow-hidden">
        
        <!-- Video Element - Autoplay, loop, and muted are mandatory for background videos -->
        <video id="background-video" autoplay loop muted playsinline 
               class="absolute inset-0 w-full h-full object-cover z-0">
            <source src="{{ asset('video/cosmetics.mp4') }}" type="video/mp4">
            <!-- A fallback for browsers that don't support the video tag -->
            Your browser does not support the video tag.
        </video>

        <!-- Video Overlay (Dark gradient for text readability) - z-10 is below the text -->
        <div class="absolute inset-0 z-10 bg-black/40"></div>
    </div>