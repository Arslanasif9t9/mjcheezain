<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy & Policy Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        h1,h2,h3 {
            font-family: "Playfair Display", serif;
            font-weight: 900;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: italic;
        }
    </style>
    <script>
        // Optional: Configure Tailwind's colors to better match the design's gold/beige tones
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-gold': '#A08A5E', // A warm gold color
                        'light-beige': '#FAF7F1', // The light background color
                        'dark-text': '#333333',
                    },
                    fontFamily: {
                        'serif-display': ['Georgia', 'serif'], // Used for the large titles
                        'sans-body': ['Arial', 'sans-serif'], // Used for body text
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white font-sans-body text-dark-text">

    <div class="max-w-4xl mx-auto px-4 py-12">

        <section class="mb-16">
            <h1 class="text-3xl sm:text-4xl font-serif-display font-bold text-center mb-10">
                <span class="text-primary-gold inline-block align-middle mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6-6h6m6 0h-6m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                Your Data, Our Responsibility
            </h1>
            <p class="text-center text-lg italic mb-12">We Value Your Privacy and Strive to Protect it at Every Step.</p>

            <h2 class="text-2xl font-bold mb-4">We Value Your Privacy</h2>
            <p class="mb-8 leading-relaxed">
                At MJCheezain, we are fully committed to protecting your personal information. When you use our website, we collect only the necessary data to process your orders, improve your shopping experience, and provide customer support.
            </p>

            <h3 class="text-xl font-bold mb-4">What We Collect</h3>
            <ul class="space-y-3 mb-10">
                <li class="flex items-center text-lg">
                    <span class="text-primary-gold mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11V3m0 8h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    Name, address, and contact information
                </li>
                <li class="flex items-center text-lg">
                    <span class="text-primary-gold mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </span>
                    Order history and preferences
                </li>
                <li class="flex items-center text-lg">
                    <span class="text-primary-gold mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </span>
                    Payment and transaction details (via secure gateways)
                </li>
            </ul>

            <p class="font-bold text-lg mb-4">We never sell, rent, or misuse your personal data. All information is encrypted and stored securely.</p>
            <p>You have full control over your account and can request data updates or deletion at any time.</p>
        </section>

        <hr class="border-gray-200 my-12">

        <section class="mb-16">
            <h2 class="text-3xl font-serif-display font-bold text-center mb-4">Cookie Policy</h2>
            <p class="text-center mb-10">
                MJCheezain uses cookies to enhance your browsing experience. Cookies help us remember what you've added to your cart, your login status, and browsing preferences. You can accept or reject cookies anytime from your browser settings.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="p-6 border border-gray-100 rounded-lg shadow-sm hover:shadow-lg transition duration-300">
                    <span class="text-primary-gold inline-block mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </span>
                    <p class="font-bold">Shopping Experience</p>
                </div>

                <div class="p-6 border border-gray-100 rounded-lg shadow-sm hover:shadow-lg transition duration-300">
                    <span class="text-primary-gold inline-block mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v10m2 4v.01m-2-4h2m4-2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </span>
                    <p class="font-bold">Preferences & Login</p>
                </div>

                <div class="p-6 border border-gray-100 rounded-lg shadow-sm hover:shadow-lg transition duration-300">
                    <span class="text-primary-gold inline-block mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l2-2m2 4l-2-2m2 2l-2-2m2 2h6m6 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </span>
                    <p class="font-bold">Performance Tracking</p>
                </div>
            </div>
        </section>

        <hr class="border-gray-200 my-12">

        <section class="mb-16 bg-light-beige p-8 rounded-lg border border-gray-200">
            <h2 class="text-2xl font-serif-display font-bold mb-4 flex items-center">
                <span class="text-yellow-600 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </span>
                Disclaimer
            </h2>
            <p class="text-sm leading-relaxed">
                <strong class="font-bold block mb-2">Important Notice:</strong> The content, product listings, and vendor information on MJCheezain are provided 'as-is' for general information only.
            </p>
            <p class="text-sm leading-relaxed mb-4">
                While we strive for 100% accuracy, product images may differ slightly, and availability can change without notice.
            </p>
            <p class="text-sm leading-relaxed">
                MJCheezain is not liable for vendor mistakes, courier delays, or buyer misuse.
            </p>
        </section>

        <hr class="border-gray-200 my-12">

        <section class="mb-16 text-center">
            <h2 class="text-3xl font-serif-display font-bold mb-10">Let's Stay Connected</h2>
            <p class="mb-12 max-w-lg mx-auto">
                If you have privacy-related questions or need assistance, contact us through the following channels.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6">
                    <span class="text-primary-gold inline-block mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.893 20.37A8.995 8.995 0 0112 21a9 9 0 01-9-9 8.995 8.995 0 011.63-5.37l-1.38-5.38 5.38 1.38A8.995 8.995 0 0112 3a9 9 0 019 9 8.995 8.995 0 01-1.63 5.37l1.38 5.38-5.38-1.38z" /></svg>
                    </span>
                    <p class="font-bold text-lg mb-1">WhatsApp Support</p>
                    <a href="#" class="text-sm text-gray-500 hover:text-primary-gold">Click to Chat</a>
                </div>

                <div class="p-6">
                    <span class="text-primary-gold inline-block mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    </span>
                    <p class="font-bold text-lg mb-1">Phone Support</p>
                    <p class="text-sm text-gray-500">03XX-XXXXXXX</p>
                </div>

                <div class="p-6">
                    <span class="text-primary-gold inline-block mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-2 1h-2a2 2 0 00-2 2v4a2 2 0 002 2h2a2 2 0 002-2v-4a2 2 0 00-2-2z" /></svg>
                    </span>
                    <p class="font-bold text-lg mb-1">Email Support</p>
                    <p class="text-sm text-gray-500">support@mjcheezain.com</p>
                </div>
            </div>

            <p class="mt-8 text-sm text-gray-500">Office Address: Coming Soon</p>

            <p class="mt-16 text-xl italic text-primary-gold">
                "We believe our **data is your property** — and our **duty is to protect it.**"
            </p>

            </section>

    </div>

</body>
</html>