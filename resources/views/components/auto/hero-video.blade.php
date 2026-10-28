
<!-- PERFUME VIDEO-TO-IMAGE HERO -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;800&display=swap" rel="stylesheet">

<style>
    #video-hero {
        position: relative;
        width: 100%;
        min-height: 420px;
        height: 100vh;
        overflow: hidden;
        background: #111;
        isolation: isolate;
    }

    #background-video,
    #perfume-final-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition:
            transform 1s cubic-bezier(0.76, 0, 0.24, 1),
            opacity 1s ease;
    }

    #background-video {
        z-index: 1;
        transform: translateX(0);
        opacity: 1;
    }

    #perfume-final-image {
        z-index: 2;
        transform: translateX(100%);
        opacity: 1;
        pointer-events: none;
    }

    /* Video exits to the left */
    #video-hero.animation-finished #background-video {
        transform: translateX(-100%);
        opacity: 0;
    }

    /* Image enters from the right */
    #video-hero.animation-finished #perfume-final-image {
        transform: translateX(0);
    }

    /* Button */
    .perfume-shop-button {
        position: absolute;
        z-index: 5;
        left: 50%;
        bottom: 9%;
        transform: translateX(-50%) scale(0);
        transform-origin: center;
        opacity: 0;
        visibility: hidden;
        white-space: nowrap;
        pointer-events: none;
        transition:
            transform 0.65s cubic-bezier(0.34, 1.56, 0.64, 1),
            opacity 0.35s ease,
            background-color 0.3s ease;
    }

    .perfume-shop-button.button-visible {
        transform: translateX(-50%) scale(1);
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .perfume-shop-button:hover {
        background-color: #E2C878;
    }
    #background-video {
        pointer-events: none;
    }

    @media (prefers-reduced-motion: reduce) {
        #background-video,
        #perfume-final-image,
        .perfume-shop-button {
            transition-duration: 0.01ms;
        }
    }
</style>

<div id="video-hero">

    <!-- STEP 1: Existing video -->
    <video
        id="background-video"
        autoplay
        muted
        playsinline
        preload="auto"
        style="object-fit: cover;">

        <source src="{{ asset('video/perfume3.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- STEP 2: Final perfume picture -->
    <img
        id="perfume-final-image"
        src="{{ asset('img/perfume-bg.jpg') }}"
        alt="MJ Cheezain perfume collection"
        style="object-fit: cover;">

    <!-- STEP 3: Shop button -->
    <a
        id="perfume-shop-button"
        href="{{ url('/product/222') }}"
        class="perfume-shop-button inline-flex items-center gap-3 px-8 py-3.5 rounded-full bg-white text-gray-900 font-semibold shadow-xl text-sm sm:text-base">

        Shop Perfume
        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
    </a>

</div>

<script>
    const video = document.getElementById('background-video');
video.controls = false;
video.removeAttribute('controls');
document.addEventListener('DOMContentLoaded', function () {
    const hero = document.getElementById('video-hero');
    const video = document.getElementById('background-video');
    const image = document.getElementById('perfume-final-image');
    const button = document.getElementById('perfume-shop-button');

    if (!hero || !video || !image || !button) return;

    let animationStarted = false;

    function finishVideoAnimation() {
        if (animationStarted) return;
        animationStarted = true;

        // Video exits left; image enters right.
        hero.classList.add('animation-finished');

        // Wait for the image slide to complete, then wait 1 second.
        image.addEventListener('transitionend', function (event) {
            if (event.propertyName !== 'transform') return;

            setTimeout(function () {
                button.classList.add('button-visible');
            }, 1000);
        }, { once: true });
    }

    // Start the transition when the video actually ends.
    video.addEventListener('ended', finishVideoAnimation);

    // If the video has already ended before the listener is attached.
    if (video.ended) {
        finishVideoAnimation();
    }

    // Handle a missing or failed image.
    image.addEventListener('error', function () {
        console.error('Perfume image not found:', image.src);
    });
});
</script>
