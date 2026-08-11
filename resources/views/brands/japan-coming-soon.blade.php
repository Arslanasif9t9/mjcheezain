<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coming Soon - MJ Cheezain</title>
<meta name="description" content="MJ Cheezain — top-quality products coming soon. Flat 40% off on perfumes at launch.">
<link rel="icon" href="{{ asset('favicon.ico') }}">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Cinzel:wght@700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Poppins', sans-serif; background: #FFF6F0; }
    .cinzel { font-family: 'Cinzel', serif; }
    .brand-gradient { background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); }
    .brand-text-gradient {
        background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
        -webkit-background-clip: text; background-clip: text; color: transparent;
    }
    .brand-shadow { box-shadow: 0 6px 18px rgba(255,125,160,0.30); }
    .blob {
        position: absolute; border-radius: 50%; filter: blur(60px); opacity: .35; pointer-events: none;
    }
    @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-18px)} }
    .float { animation: float 4s ease-in-out infinite; }
    @keyframes pulse-ring { 0%{transform:scale(1);opacity:.5} 100%{transform:scale(1.6);opacity:0} }
    .pulse-ring::before{
        content:''; position:absolute; inset:0; border-radius:9999px;
        background:#25D366; animation:pulse-ring 1.8s ease-out infinite;
    }
</style>
</head>
<body class="min-h-screen relative overflow-x-hidden">

    <div class="blob w-72 h-72 bg-[#FF7DA0] -top-10 -left-10"></div>
    <div class="blob w-80 h-80 bg-[#FFC275] top-1/3 -right-16"></div>
    <div class="blob w-64 h-64 bg-[#FF7DA0] bottom-0 left-1/4"></div>

    <main class="relative z-10 min-h-screen flex flex-col items-center justify-center px-5 py-10 text-center">

        <div class="flex items-center gap-2 mb-8 float">
            <span class="cinzel text-3xl md:text-4xl font-bold brand-text-gradient">MJ</span>
            <span class="text-2xl md:text-3xl font-extrabold text-gray-800">Cheezain</span>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full brand-gradient brand-shadow text-white text-xs md:text-sm font-semibold mb-6">
            <i class="fa-solid fa-hourglass-half"></i> Launching Soon
        </div>

        <h1 class="text-3xl md:text-6xl font-extrabold text-gray-800 leading-tight mb-4">
            Something <span class="brand-text-gradient">Beautiful</span><br class="hidden md:block"> Is On The Way
        </h1>

        <p class="text-sm md:text-lg text-gray-500 max-w-xl mb-8">
            We're bringing you a brand new shopping experience — top-quality products, arriving soon. Stay tuned!
        </p>

        {{-- Perfume launch offer --}}
        <div class="brand-gradient brand-shadow rounded-2xl md:rounded-3xl px-6 py-4 md:px-10 md:py-6 mb-6 max-w-md w-full">
            <p class="text-white/90 text-xs md:text-sm font-medium mb-1">✨ Perfumes Pre-Launch Offer</p>
            <p class="text-white text-2xl md:text-4xl font-extrabold">Flat 40% OFF</p>
            <p class="text-white/85 text-xs md:text-sm mt-1">on all Perfume Brands — starting from launch day</p>
        </div>

        {{-- Trust badges --}}
        <div class="flex flex-wrap items-center justify-center gap-2 md:gap-3 mb-8 max-w-lg">
            <span class="inline-flex items-center gap-1.5 bg-white border border-pink-100 rounded-full px-3.5 py-1.5 text-xs md:text-sm font-medium text-gray-600 shadow-sm">
                <i class="fa-solid fa-shield-halved text-[#FF7DA0]"></i> 100% Original
            </span>
            <span class="inline-flex items-center gap-1.5 bg-white border border-pink-100 rounded-full px-3.5 py-1.5 text-xs md:text-sm font-medium text-gray-600 shadow-sm">
                <i class="fa-solid fa-truck-fast text-[#FF7DA0]"></i> Cash on Delivery
            </span>
            <span class="inline-flex items-center gap-1.5 bg-white border border-pink-100 rounded-full px-3.5 py-1.5 text-xs md:text-sm font-medium text-gray-600 shadow-sm">
                <i class="fa-solid fa-lock text-[#FF7DA0]"></i> Secure Shopping
            </span>
        </div>

        {{-- WhatsApp CTA --}}
        <div class="app-card bg-white rounded-2xl px-5 py-4 md:px-8 md:py-5 shadow-[0_8px_24px_rgba(232,93,133,0.10)] border border-pink-100 max-w-md w-full">
            <p class="text-gray-700 text-sm md:text-base font-medium mb-3">Have a question or want to order early?</p>
            {{--
                ⚠️ WHATSAPP NUMBER — edit karne ke liye yahan href me number badlo.
                Format: https://wa.me/<countrycode+number bina + ya spaces>?text=...
                Abhi placeholder Japan number laga hai (819012345678) — real number se replace karein.
            --}}
            <a href="https://wa.me/819012345678?text=Hi%2C%20I%27m%20interested%20in%20MJ%20Cheezain%20Japan%20products"
               target="_blank" rel="noopener"
               class="relative inline-flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] text-white font-semibold rounded-full px-6 py-3 w-full transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                <span class="relative flex h-3 w-3 pulse-ring">
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                </span>
                <i class="fa-brands fa-whatsapp text-xl"></i>
                WhatsApp Us
            </a>
        </div>

        {{-- Vendor mention (secondary, small) --}}
        <p class="text-gray-400 text-xs md:text-sm mt-8">
            Want to sell your products as a vendor?
            <a href="https://wa.me/819012345678?text=Hi%2C%20I%27m%20interested%20in%20selling%20on%20MJ%20Cheezain%20Japan"
               target="_blank" rel="noopener" class="font-semibold text-[#FF7DA0] hover:underline">Get in touch</a>
        </p>

        <p class="text-gray-400 text-xs md:text-sm mt-6">&copy; {{ date('Y') }} MJ Cheezain. All rights reserved.</p>
    </main>

</body>
</html>
