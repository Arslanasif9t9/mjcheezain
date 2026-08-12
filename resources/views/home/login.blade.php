<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register | Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-pink: #FF7DA0;
            --brand-peach: #FFC275;
            --brand-pink-dark: #E85D85;
            --brand-ink: #2B2130;
            /* Shared motion tokens for a consistent, smooth feel across the page */
            --ease-smooth: cubic-bezier(0.22, 1, 0.36, 1);
            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        html, body {
            height: 100%;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            /* Use dynamic viewport height so mobile browser chrome (address bar) never clips content — vh first as a fallback for WebViews that don't yet support dvh */
            min-height: 100vh;
            min-height: 100dvh;
            -webkit-font-smoothing: antialiased;
        }

        .font-serif-italic {
            font-family: 'PT Serif', serif;
            font-style: italic;
        }

        .btn-brand-gradient {
            background: linear-gradient(115deg, var(--brand-pink) 0%, var(--brand-peach) 100%);
            background-size: 160% 160%;
            background-position: 0% 50%;
            transition: background-position 0.5s var(--ease-smooth), transform 0.35s var(--ease-smooth), box-shadow 0.35s var(--ease-smooth);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 125, 160, 0.25);
            will-change: transform;
        }
        .btn-brand-gradient:hover {
            background-position: 100% 50%;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(232, 93, 133, 0.32);
        }
        .btn-brand-gradient:active {
            transform: translateY(0) scale(0.98);
            transition-duration: 0.15s;
        }

        .btn-outline-brand {
            transition: all 0.35s var(--ease-smooth);
        }
        .btn-outline-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 125, 160, 0.18);
        }
        .btn-outline-brand:active {
            transform: translateY(0) scale(0.98);
        }

        /* Glassmorphism styling for input fields */
        .fancy-input-container {
            position: relative;
            width: 100%;
            margin: 8px 0;
        }
        .fancy-input-container i:not(.fa-eye):not(.fa-eye-slash) {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: color 0.3s ease;
        }
        .fancy-input {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px 16px 12px 46px;
            font-size: 14px;
            color: #334155;
            border-radius: 12px;
            transition: background-color 0.3s var(--ease-smooth), border-color 0.3s var(--ease-smooth), box-shadow 0.35s var(--ease-smooth), transform 0.3s var(--ease-smooth);
        }
        .fancy-input:focus {
            background-color: #ffffff;
            border-color: var(--brand-pink);
            box-shadow: 0 0 0 4px rgba(255, 125, 160, 0.14);
            outline: none;
            transform: translateY(-1px);
        }
        .fancy-input-container:focus-within i:not(.fa-eye):not(.fa-eye-slash) {
            color: var(--brand-pink);
        }

        /* ===== HERO (top theme) section — exact 60% of the viewport, bottom action area fills the rest =====
           vh is declared first as a fallback; dvh overrides it where supported (better on mobile browser chrome) */
        .hero-section {
            height: 60vh;
            height: 60dvh;
            flex: 0 0 60vh;
            flex: 0 0 60dvh;
            padding: max(1.25rem, env(safe-area-inset-top)) 1.25rem 0 1.25rem;
        }

        .bottom-section {
            flex: 1 1 auto;
            min-height: 40vh;
            min-height: 40dvh;
        }

        /* Illustration sits centered on the seam between the pink hero and the white bottom area */
        .hero-illustration-wrap {
            position: absolute;
            top: 60vh;
            top: 60dvh;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 40;
            pointer-events: none;
        }

        .hero-illustration {
            width: clamp(190px, 52vw, 300px);
            height: auto;
            display: block;
        }

        .globe-blend {
            filter: drop-shadow(0 20px 24px rgba(60, 20, 35, 0.28)) drop-shadow(0 4px 6px rgba(60, 20, 35, 0.16));
        }

        @media (max-width: 340px) {
            .hero-section { padding-left: 1rem; padding-right: 1rem; }
        }

        /* Quiet ambient glow orbs for depth — restrained, low opacity, purely decorative */
        .ambient-orb {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.35) 0%, rgba(255,255,255,0) 70%);
            pointer-events: none;
            filter: blur(2px);
        }
        .ambient-orb.orb-a { width: 140px; height: 140px; top: -30px; left: -30px; }
        .ambient-orb.orb-b { width: 90px; height: 90px; bottom: 10%; right: -10px; opacity: 0.7; }

        /* Gentle staggered entrance for the hero content on load */
        @media (prefers-reduced-motion: no-preference) {
            .enter-fade {
                opacity: 0;
                transform: translateY(10px);
                animation: enter-up 0.7s var(--ease-smooth) forwards;
            }
            .enter-fade.d1 { animation-delay: 0.05s; }
            .enter-fade.d2 { animation-delay: 0.15s; }
            .enter-fade.d3 { animation-delay: 0.25s; }
            .enter-fade.d4 { animation-delay: 0.35s; }
        }
        @keyframes enter-up {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Slide up Drawer on Mobile / Centered Modal on Desktop */
        @media (max-width: 767px) {
            .drawer-popup {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                max-height: 88vh;
                max-height: 88dvh;
                background-color: white;
                border-top-left-radius: 24px;
                border-top-right-radius: 24px;
                z-index: 1000;
                transform: translateY(100%);
                transition: transform 0.45s var(--ease-spring);
                box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.15);
                padding-bottom: env(safe-area-inset-bottom);
            }
            .drawer-popup.active {
                transform: translateY(0);
            }
        }

        @media (min-width: 768px) {
            .drawer-popup {
                position: fixed;
                left: 50%;
                top: 50%;
                width: 100%;
                max-width: 460px;
                max-height: 90vh;
                max-height: 90dvh;
                background-color: white;
                border-radius: 24px;
                z-index: 1000;
                transform: translate(-50%, -45%) scale(0.94);
                opacity: 0;
                pointer-events: none;
                transition: transform 0.45s var(--ease-spring), opacity 0.3s var(--ease-smooth);
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            }
            .drawer-popup.active {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
                pointer-events: auto;
            }
        }

        /* Loading spinner */
        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Subtle float animation for the hero illustration — restrained, not distracting */
        @media (prefers-reduced-motion: no-preference) {
            .hero-illustration {
                animation: float-y 6s var(--ease-smooth) infinite, enter-up 0.8s var(--ease-smooth) 0.2s backwards;
            }
        }
        @keyframes float-y {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-9px); }
        }

        /* Segmented control sliding pill */
        .segmented-track {
            position: relative;
        }
        .segmented-pill {
            position: absolute;
            top: 6px;
            bottom: 6px;
            left: 6px;
            width: calc(50% - 6px);
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            transition: transform 0.4s var(--ease-smooth);
            z-index: 0;
        }
        .segmented-pill.is-vendor {
            transform: translateX(100%);
        }
        .segmented-btn {
            position: relative;
            z-index: 1;
            transition: color 0.3s var(--ease-smooth);
        }

        /* Smooth crossfade when switching between Sign In / Sign Up */
        .auth-section {
            transition: opacity 0.3s var(--ease-smooth), transform 0.3s var(--ease-smooth);
        }
        .auth-section.is-hidden {
            display: none;
        }
        /* Flows the admin has switched off (Controls page) */
        .is-access-hidden {
            display: none !important;
        }
        .auth-section.is-entering {
            opacity: 0;
            transform: translateY(6px);
        }

        /* Visible keyboard focus for accessibility */
        button:focus-visible, a:focus-visible {
            outline: 2px solid var(--brand-pink-dark);
            outline-offset: 2px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between bg-white relative overflow-x-hidden">

    <!-- Top Theme Area (Vibrant pink to soft peach gradient) -->
    <div class="hero-section w-full flex flex-col relative overflow-hidden" style="background: linear-gradient(135deg, #FF7DA0 0%, #FFC275 100%);">

        <!-- Quiet ambient glow orbs for depth -->
        <div class="ambient-orb orb-a"></div>
        <div class="ambient-orb orb-b"></div>

        <!-- Header Controls -->
        <div class="enter-fade d1 flex justify-between items-center w-full z-10">
            <div></div> <!-- Spacer -->
            <!-- Close Button linking back to Home -->
            <a href="/" class="text-black/70 hover:text-black font-bold text-2xl transition-colors cursor-pointer" aria-label="Close">
                ✕
            </a>
        </div>

        <!-- Slogan and Logo — pinned near the top so it never collides with the illustration lower down -->
        <div class="enter-fade d2 flex flex-col items-center justify-center text-center z-10 px-4" style="margin-top: clamp(0.5rem, 4vh, 1.5rem);">
            <span class="font-serif-italic font-extrabold text-black text-2xl sm:text-3xl tracking-wide drop-shadow-sm mb-1">MJ Cheezain</span>
            <p class="text-xs sm:text-sm font-medium text-black/80 tracking-wide">Elegance in every choice • Local &amp; Global Deals</p>
        </div>

        <!-- White Upward Curved Mask at the bottom of the gradient container -->
        <div class="absolute bottom-0 left-0 w-full pointer-events-none z-20" style="height: clamp(30px, 6vw, 50px);">
            <svg viewBox="0 0 1440 100" preserveAspectRatio="none" class="w-full h-full text-white fill-current">
                <path d="M0,100 C480,0 960,0 1440,100 L1440,100 L0,100 Z"></path>
            </svg>
        </div>
    </div>

    <!-- 3D-style Illustration: Globe + Delivery Truck + Parcel + Shield — positioned to straddle the seam between the pink and white sections -->
    <div class="hero-illustration-wrap">
        <svg class="hero-illustration globe-blend" viewBox="0 0 340 300" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Global shipping illustration with delivery truck, parcel and protection shield">
            <defs>
                <radialGradient id="sphereGrad" cx="32%" cy="26%" r="80%">
                    <stop offset="0%" stop-color="#FFFFFF"/>
                    <stop offset="42%" stop-color="#FFE8DC"/>
                    <stop offset="76%" stop-color="#FFC9A0"/>
                    <stop offset="100%" stop-color="#F2A868"/>
                </radialGradient>
                <linearGradient id="pinGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FF8FAE"/>
                    <stop offset="100%" stop-color="#D63A63"/>
                </linearGradient>
                <linearGradient id="truckBody" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FFFFFF"/>
                    <stop offset="100%" stop-color="#FFD3DE"/>
                </linearGradient>
                <linearGradient id="truckCargo" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FF8FAE"/>
                    <stop offset="100%" stop-color="#D63A63"/>
                </linearGradient>
                <linearGradient id="truckCargoTop" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FFFFFF" stop-opacity="0.55"/>
                    <stop offset="100%" stop-color="#FFFFFF" stop-opacity="0"/>
                </linearGradient>
                <linearGradient id="boxTop" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FFEEDA"/>
                    <stop offset="100%" stop-color="#FFC275"/>
                </linearGradient>
                <linearGradient id="boxFront" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FFCE93"/>
                    <stop offset="100%" stop-color="#EE9536"/>
                </linearGradient>
                <linearGradient id="boxSide" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#D9812A"/>
                    <stop offset="100%" stop-color="#B8661C"/>
                </linearGradient>
                <linearGradient id="shieldGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FFDA95"/>
                    <stop offset="100%" stop-color="#EE9536"/>
                </linearGradient>
                <clipPath id="sphereClip">
                    <circle cx="175" cy="145" r="82"/>
                </clipPath>
            </defs>

            <!-- ground shadow (contact shadow anchoring the whole scene to the surface below) -->
            <ellipse cx="172" cy="278" rx="118" ry="16" fill="#5A2036" opacity="0.20"/>

            <!-- orbit ring (route line) -->
            <ellipse cx="175" cy="145" rx="115" ry="44" fill="none" stroke="#FFFFFF" stroke-width="3" opacity="0.6" transform="rotate(-11 175 145)"/>
            <circle cx="286" cy="127" r="5" fill="#FFFFFF" opacity="0.9" transform="rotate(-11 175 145)"/>

            <!-- globe sphere -->
            <circle cx="175" cy="145" r="82" fill="url(#sphereGrad)"/>
            <g clip-path="url(#sphereClip)">
                <g opacity="0.5">
                    <ellipse cx="175" cy="145" rx="34" ry="82" fill="none" stroke="#E8926A" stroke-width="2"/>
                    <ellipse cx="175" cy="145" rx="68" ry="82" fill="none" stroke="#E8926A" stroke-width="2"/>
                    <ellipse cx="175" cy="112" rx="82" ry="24" fill="none" stroke="#E8926A" stroke-width="2"/>
                    <ellipse cx="175" cy="145" rx="82" ry="30" fill="none" stroke="#E8926A" stroke-width="2"/>
                    <ellipse cx="175" cy="180" rx="82" ry="22" fill="none" stroke="#E8926A" stroke-width="2"/>
                </g>
                <!-- soft rim shading for volume -->
                <ellipse cx="212" cy="185" rx="78" ry="78" fill="#8A3A1E" opacity="0.16"/>
                <!-- specular highlight -->
                <ellipse cx="140" cy="100" rx="32" ry="19" fill="#FFFFFF" opacity="0.6" transform="rotate(-18 140 100)"/>
            </g>
            <circle cx="175" cy="145" r="82" fill="none" stroke="#FFFFFF" stroke-width="2" opacity="0.55"/>

            <!-- location pin on top of globe -->
            <g transform="translate(175,42)">
                <ellipse cx="0" cy="42" rx="12" ry="4" fill="#5A2036" opacity="0.18"/>
                <path d="M0,-38 C17,-38 30,-25 30,-9 C30,14 0,40 0,40 C0,40 -30,14 -30,-9 C-30,-25 -17,-38 0,-38 Z" fill="url(#pinGrad)"/>
                <circle cx="0" cy="-10" r="10" fill="#FFFFFF"/>
                <circle cx="0" cy="-10" r="10" fill="none" stroke="#D63A63" stroke-width="1.5" opacity="0.3"/>
            </g>

            <!-- parcel box (right side, in front of globe) -->
            <g transform="translate(236,198)">
                <ellipse cx="34" cy="66" rx="42" ry="8" fill="#5A2036" opacity="0.16"/>
                <polygon points="0,10 34,-6 68,10 68,46 34,62 0,46" fill="url(#boxFront)"/>
                <polygon points="0,10 34,-6 68,10 34,26" fill="url(#boxTop)"/>
                <polygon points="34,26 68,10 68,46 34,62" fill="url(#boxSide)"/>
                <!-- ribbon -->
                <polygon points="30,-2 38,-2 38,58 30,58" fill="#FF7DA0" opacity="0.92"/>
                <polygon points="0,26 68,26 68,32 0,32" fill="#FF7DA0" opacity="0.8"/>
                <!-- edge highlight -->
                <polygon points="0,10 34,-6 34,-2 0,14" fill="#FFFFFF" opacity="0.35"/>
            </g>

            <!-- shield / protection badge -->
            <g transform="translate(270,84)">
                <ellipse cx="0" cy="36" rx="18" ry="5" fill="#5A2036" opacity="0.14"/>
                <path d="M0,-20 L18,-13 L18,6 C18,20 9,29 0,33 C-9,29 -18,20 -18,6 L-18,-13 Z" fill="url(#shieldGrad)" stroke="#FFFFFF" stroke-width="2"/>
                <path d="M-7,3 L-2,9 L9,-6" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </g>

            <!-- delivery truck (left, foreground) -->
            <g transform="translate(26,170)">
                <ellipse cx="49" cy="38" rx="58" ry="9" fill="#5A2036" opacity="0.18"/>
                <!-- cargo box -->
                <rect x="34" y="-46" width="64" height="58" rx="6" fill="url(#truckCargo)"/>
                <rect x="34" y="-46" width="64" height="58" rx="6" fill="url(#truckCargoTop)"/>
                <!-- cab -->
                <path d="M0,-6 L34,-6 L34,12 L0,12 Z" fill="url(#truckBody)"/>
                <path d="M2,-6 L28,-6 L34,10 L2,10 Z" fill="#FFF4F7"/>
                <rect x="6" y="-2" width="18" height="10" rx="2" fill="#AECBEE"/>
                <!-- chassis -->
                <rect x="-4" y="12" width="106" height="8" rx="3" fill="#D63A63"/>
                <!-- wheels -->
                <circle cx="18" cy="24" r="11" fill="#3A2E35"/>
                <circle cx="18" cy="24" r="4.5" fill="#E4E4E4"/>
                <circle cx="80" cy="24" r="11" fill="#3A2E35"/>
                <circle cx="80" cy="24" r="4.5" fill="#E4E4E4"/>
            </g>
        </svg>
    </div>

    <!-- Bottom Action Area -->
    <div class="bottom-section w-full bg-white px-5 sm:px-6 py-8 sm:py-10 text-center flex flex-col items-center justify-center z-10" style="padding-bottom: max(2rem, env(safe-area-inset-bottom));">
        <!-- Action Buttons Only -->
        <div class="flex flex-col gap-3.5 w-full max-w-md">
            <!-- Continue with Sign In -->
            <button id="btn-open-signin" onclick="openDrawer('signin')" class="enter-fade d3 btn-brand-gradient w-full py-4 px-6 rounded-xl font-bold text-sm tracking-wider uppercase">
                Continue with Sign In
            </button>

            <!-- Continue with Sign Up -->
            <button id="btn-open-signup" onclick="openDrawer('signup')" class="enter-fade d4 btn-outline-brand w-full py-3.5 px-6 border-2 border-pink-400 text-pink-600 hover:bg-pink-50 font-bold text-sm tracking-wider uppercase rounded-xl shadow-sm">
                Create New Account
            </button>
        </div>
    </div>

    <!-- Drawer Backdrop Overlay -->
    <div id="drawer-backdrop" class="fixed inset-0 bg-black/50 z-[998] hidden opacity-0 transition-opacity duration-300" style="backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);" onclick="closeDrawer()"></div>

    <!-- Drawer / Slide Up Form Container -->
    <div id="drawer-popup" class="drawer-popup overflow-y-auto">
        <!-- Slide Indicator (Mobile Only) -->
        <div class="block md:hidden w-12 h-1.5 bg-gray-200 rounded-full mx-auto mt-4 mb-2 cursor-pointer" onclick="closeDrawer()"></div>

        <!-- Header / Modal Close for Desktop -->
        <div class="hidden md:flex justify-end p-4 pb-0">
            <button onclick="closeDrawer()" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
        </div>

        <!-- Inner Content Area -->
        <div class="px-5 sm:px-6 py-4 sm:p-8">

            {{-- Admin Controls (Account Access): with only one role available the
                 segmented control is pointless (and half a pill looks broken). --}}
            @if(($siteAccess['customer_any'] ?? true) && ($siteAccess['vendor_any'] ?? true))
            <!-- Segmented Control for Role Select (Customer / Vendor Portal) -->
            <div class="segmented-track flex bg-gray-100 p-1.5 rounded-xl mb-6">
                <div id="segmented-pill" class="segmented-pill"></div>
                <button id="role-customer-btn" type="button" onclick="setRole('customer')" class="segmented-btn flex-grow py-2 text-xs font-bold rounded-lg text-pink-600">Customer</button>
                <button id="role-vendor-btn" type="button" onclick="setRole('vendor')" class="segmented-btn flex-grow py-2 text-xs font-bold rounded-lg text-gray-500 hover:text-gray-900">Vendor Portal</button>
            </div>
            @endif

            <!-- 1. SIGN IN FORM -->
            <div id="signin-section" class="auth-section">
                <h3 class="text-xl font-bold text-gray-900 mb-5">Sign In</h3>

                <form id="loginForm" class="flex flex-col">
                    <input id="userTypeLog" type="hidden" name="type" value="customer">

                    <div class="fancy-input-container">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" name="id" placeholder="Email Address" class="fancy-input" required/>
                    </div>

                    <div class="fancy-input-container">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" id="loginPassword" placeholder="Password" class="fancy-input pr-10" required/>
                        <button type="button" class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 bg-transparent border-none cursor-pointer">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <a id="forgot" href="/customer-forgot-password" class="text-pink-500 hover:text-pink-600 text-xs font-semibold text-left my-2.5 inline-block transition-colors">Forgot your password?</a>

                    <div id="loginMessage" class="message my-2 text-sm w-full"></div>
                    <button type="submit" id="loginBtn" class="btn-brand-gradient text-white border-none rounded-xl py-3 px-6 font-bold text-sm tracking-wider uppercase mt-4 w-full">Sign In</button>
                </form>

                <p id="link-to-signup" class="text-xs text-gray-500 mt-6 text-center">
                    Don't have an account?
                    <button type="button" onclick="toggleFormType('signup')" class="text-pink-500 font-bold hover:underline focus:outline-none ml-1">Sign Up</button>
                </p>
            </div>

            <!-- 2. SIGN UP FORM -->
            <div id="signup-section" class="auth-section is-hidden">
                <h3 class="text-xl font-bold text-gray-900 mb-5">Create Account</h3>

                <form id="signupForm" class="flex flex-col">
                    <input id="userTypeSign" type="hidden" name="type" value="customer">

                    <div class="fancy-input-container">
                        <i class="fa-regular fa-user"></i>
                        <input type="text" name="name" placeholder="Full Name" class="fancy-input" required/>
                    </div>

                    <div class="fancy-input-container">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" name="email" id="email" placeholder="Email Address" class="fancy-input pr-24" required/>
                        <button type="button" id="getOtpBtn" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#C57614] hover:bg-[#A35F0E] text-white font-medium border-none py-1.5 px-3 rounded-lg text-xs cursor-pointer transition-colors shadow-sm">Get OTP</button>
                    </div>

                    <div id="otpTimer" class="otp-timer text-xs font-semibold text-pink-600 my-1 text-left"></div>

                    <div class="fancy-input-container">
                        <i class="fa-solid fa-shield-halved"></i>
                        <input type="number" name="otp" placeholder="4 digit OTP" class="fancy-input" required min="1000" max="9999"/>
                    </div>

                    <div class="fancy-input-container">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password" id="signupPassword" placeholder="Create Password" class="fancy-input pr-10" required minlength="6"/>
                        <button type="button" class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 bg-transparent border-none cursor-pointer">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <div class="fancy-input-container">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password_confirmation" id="signupConfirmPassword" placeholder="Confirm Password" class="fancy-input pr-10" required minlength="6"/>
                        <button type="button" class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 bg-transparent border-none cursor-pointer">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <div id="signupMessage" class="message my-2 text-sm w-full"></div>
                    <button type="submit" id="signupBtn" class="btn-brand-gradient text-white border-none rounded-xl py-3 px-6 font-bold text-sm tracking-wider uppercase mt-4 w-full">Sign Up</button>
                </form>

                <p id="link-to-signin" class="text-xs text-gray-500 mt-6 text-center">
                    Already have an account?
                    <button type="button" onclick="toggleFormType('signin')" class="text-pink-500 font-bold hover:underline focus:outline-none ml-1">Sign In</button>
                </p>
            </div>

        </div>
    </div>

    <script>
        // Drawer toggle functions
        const backdrop = document.getElementById('drawer-backdrop');
        const drawer = document.getElementById('drawer-popup');

        function openDrawer(type = 'signin') {
            backdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                backdrop.classList.add('opacity-100');
                drawer.classList.add('active');
            }, 10);

            toggleFormType(type);
        }

        function closeDrawer() {
            backdrop.classList.remove('opacity-100');
            drawer.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(() => {
                backdrop.classList.add('hidden');
            }, 300);
        }

        function toggleFormType(type) {
            const signinSec = document.getElementById('signin-section');
            const signupSec = document.getElementById('signup-section');
            const showEl = type === 'signin' ? signinSec : signupSec;
            const hideEl = type === 'signin' ? signupSec : signinSec;

            if (!hideEl.classList.contains('is-hidden')) {
                hideEl.classList.add('is-entering');
                requestAnimationFrame(() => hideEl.classList.add('is-hidden'));
            } else {
                hideEl.classList.add('is-hidden');
            }

            showEl.classList.remove('is-hidden');
            showEl.classList.add('is-entering');
            // Force reflow so the transition reliably plays, then settle into place
            void showEl.offsetWidth;
            requestAnimationFrame(() => showEl.classList.remove('is-entering'));

            clearMessages();
        }

        @php
            // Built here rather than inline: @json() can't parse a multi-line
            // nested array literal (it miscounts the brackets).
            $authAccessCfg = [
                'customer' => [
                    'login'    => $siteAccess['customer_login'] ?? true,
                    'register' => $siteAccess['customer_register'] ?? true,
                ],
                'vendor' => [
                    'login'    => $siteAccess['vendor_login'] ?? true,
                    'register' => $siteAccess['vendor_register'] ?? true,
                ],
            ];
        @endphp
        // Which flows the admin has left open (Controls page). The server enforces
        // this too — this only keeps the UI from offering a dead-end.
        const AUTH_ACCESS = @json($authAccessCfg);

        function roleAllows(role, flow) {
            return !!(AUTH_ACCESS[role] && AUTH_ACCESS[role][flow]);
        }

        // Show/hide the sign-in vs sign-up entry points for the selected role,
        // and bounce off a section that role can't use.
        function applyAccess(role) {
            const canLogin = roleAllows(role, 'login');
            const canReg   = roleAllows(role, 'register');

            const toggle = (el, show) => { if (el) el.classList.toggle('is-access-hidden', !show); };

            toggle(document.getElementById('btn-open-signin'), canLogin);
            toggle(document.getElementById('btn-open-signup'), canReg);
            toggle(document.getElementById('link-to-signup'), canReg);
            toggle(document.getElementById('link-to-signin'), canLogin);
            toggle(document.getElementById('forgot'), canLogin);

            // If the visible section is closed for this role, switch to the open one.
            const signinSec = document.getElementById('signin-section');
            const signupSec = document.getElementById('signup-section');
            const signinVisible = signinSec && !signinSec.classList.contains('is-hidden');
            if (signinVisible && !canLogin && canReg) toggleFormType('signup');
            if (!signinVisible && !canReg && canLogin) toggleFormType('signin');
        }

        // Toggle user type/role between Customer and Vendor — animates a sliding pill for a smoother feel
        let currentRole = 'customer';
        function setRole(role) {
            // Never select a role the admin has fully switched off.
            if (!roleAllows(role, 'login') && !roleAllows(role, 'register')) return;

            currentRole = role;
            const customerBtn = document.getElementById('role-customer-btn');
            const vendorBtn = document.getElementById('role-vendor-btn');
            const pill = document.getElementById('segmented-pill');
            const forgot = document.getElementById('forgot');

            document.getElementById('userTypeSign').value = role;
            document.getElementById('userTypeLog').value = role;

            // The segmented control is absent when only one role is available.
            if (role === 'customer') {
                if (pill) pill.classList.remove('is-vendor');
                if (customerBtn) { customerBtn.classList.add('text-pink-600'); customerBtn.classList.remove('text-gray-500'); }
                if (vendorBtn) { vendorBtn.classList.add('text-gray-500'); vendorBtn.classList.remove('text-pink-600'); }
                if (forgot) forgot.href = "/customer-forgot-password";
            } else {
                if (pill) pill.classList.add('is-vendor');
                if (vendorBtn) { vendorBtn.classList.add('text-pink-600'); vendorBtn.classList.remove('text-gray-500'); }
                if (customerBtn) { customerBtn.classList.add('text-gray-500'); customerBtn.classList.remove('text-pink-600'); }
                if (forgot) forgot.href = "/vendor-forgot-password";
            }

            applyAccess(role);
        }

        // URL Tab/Role pre-selector on page load
        function handleURLTabs() {
            const params = new URLSearchParams(window.location.search);
            const type = params.get('type');

            if (!type) return;

            if (type.includes('customer')) {
                setRole('customer');
            } else if (type.includes('vendor')) {
                setRole('vendor');
            }

            // Only auto-open a drawer the current role can actually use.
            if (type.includes('signup') && roleAllows(currentRole, 'register')) {
                openDrawer('signup');
            } else if (type.includes('login') && roleAllows(currentRole, 'login')) {
                openDrawer('signin');
            }
        }

        window.onload = function() {
            // Land on whichever role is still open (customer first).
            if (!roleAllows('customer', 'login') && !roleAllows('customer', 'register')) {
                setRole('vendor');
            } else {
                setRole('customer');
            }
            handleURLTabs();
        };

        const signupForm = document.getElementById('signupForm');
        const loginForm = document.getElementById('loginForm');
        const signupBtn = document.getElementById('signupBtn');
        const loginBtn = document.getElementById('loginBtn');
        const signupMessage = document.getElementById('signupMessage');
        const loginMessage = document.getElementById('loginMessage');

        // OTP functionality
        const getOtpBtn = document.getElementById('getOtpBtn');
        const emailInput = document.getElementById('email');
        const otpTimer = document.getElementById('otpTimer');

        let otpCountdown = 0;
        let countdownInterval;

        function startOtpTimer() {
            otpCountdown = 60; // 60 seconds
            getOtpBtn.disabled = true;
            updateOtpTimer();

            countdownInterval = setInterval(() => {
                otpCountdown--;
                updateOtpTimer();

                if (otpCountdown <= 0) {
                    clearInterval(countdownInterval);
                    getOtpBtn.disabled = false;
                    getOtpBtn.textContent = 'Get OTP';
                    otpTimer.textContent = '';
                }
            }, 1000);
        }

        function updateOtpTimer() {
            otpTimer.textContent = `Resend OTP in ${otpCountdown} seconds`;
        }
        function getQueryParam(param) {
            return new URLSearchParams(window.location.search).get(param);
        }
        const page = getQueryParam('page');

        // OTP button event listener
        getOtpBtn.addEventListener('click', async () => {
            const email = emailInput.value.trim();

            if (!email) {
                showMessage(signupMessage, "Please enter your email first", "error");
                return;
            }

            // Simple email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showMessage(signupMessage, "Please enter a valid email address", "error");
                return;
            }

            // Show loading state
            getOtpBtn.innerHTML = '<span class="loading"></span> Sending...';
            getOtpBtn.disabled = true;

            try {
                const response = await fetch('/send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    // `type` lets the server apply the per-role registration switch
                    body: JSON.stringify({ type: currentRole, email: email })
                });

                const result = await response.json();

                if (response.ok) {
                    showMessage(signupMessage, "OTP sent to your email successfully!", "success");
                    getOtpBtn.innerHTML = 'Resend';
                    startOtpTimer();
                } else {
                    const errorMessage = result.message || 'Failed to send OTP. Please try again.';
                    showMessage(signupMessage, errorMessage, "error");
                    getOtpBtn.disabled = false;
                    getOtpBtn.textContent = 'Get OTP';
                }
            } catch (error) {
                console.error('OTP request error:', error);
                showMessage(signupMessage, "Network error. Please try again.", "error");
                getOtpBtn.disabled = false;
                getOtpBtn.textContent = 'Get OTP';
            }
        });

        // Clear all messages
        function clearMessages() {
            signupMessage.style.display = 'none';
            loginMessage.style.display = 'none';
        }

        // Show message function
        function showMessage(element, message, type) {
            element.textContent = message;
            element.className = `message my-2 text-sm w-full ${type === 'error' ? 'text-red-500' : 'text-green-600 font-medium'}`;
            element.style.display = 'block';
        }

        // Password visibility toggle
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Handle signup form submission
        signupForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Get form data
            const formData = new FormData(signupForm);
            const data = Object.fromEntries(formData);

            // Validate passwords match
            if (data.password !== data.password_confirmation) {
                showMessage(signupMessage, "Passwords do not match", "error");
                return;
            }
            // Validate OTP is 4 digits
            if (!data.otp || data.otp.length !== 4) {
                showMessage(signupMessage, "Please enter a valid 4-digit OTP", "error");
                return;
            }

            // Show loading state
            signupBtn.disabled = true;
            signupBtn.innerHTML = '<span class="loading"></span> Signing Up...';

            try {
                const response = await fetch('/signup', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    showMessage(signupMessage, "Account created successfully! Redirecting...", "success");
                    setTimeout(() => {
                        window.location.href = `/${result.type}/dashboard`;
                    }, 500);
                } else {
                    let errorMessage = 'Signup failed. Please try again.';
                    if (result) {
                        if (result.message) {
                            errorMessage = result.message;
                        } else if (result.errors && typeof result.errors === 'object') {
                            errorMessage = Object.values(result.errors).flat().join(', ');
                        }
                    }
                    showMessage(signupMessage, errorMessage, "error");
                }
            } catch (error) {
                console.error('Signup error:', error);
                showMessage(signupMessage, "Network error. Please try again.", "error");
            } finally {
                signupBtn.disabled = false;
                signupBtn.innerHTML = 'Sign Up';
            }
        });

        // Handle login form submission
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Get form data
            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData);

            // Show loading state
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<span class="loading"></span> Signing In...';

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    showMessage(loginMessage, "Login successful! Redirecting...", "success");
                    if (result.token) {
                        localStorage.setItem('authToken', result.token);
                    }
                    setTimeout(() => {
                        if (page === '/' || page === '%2F' || !page) {
                            window.location.href = `/${result.type}/dashboard`;
                        } else {
                            let target = decodeURIComponent(page);
                            // Only allow same-site relative paths (block //evil.com and http://evil.com)
                            if (!/^\/[^\/]/.test(target)) {
                                target = `/${result.type}/dashboard`;
                            }
                            window.location.href = target;
                        }
                    }, 500);
                } else {
                    const errorMessage = result.message || 'Login failed. Please check your information.';
                    showMessage(loginMessage, errorMessage, "error");
                }
            } catch (error) {
                console.error('Login error:', error);
                showMessage(loginMessage, "Network error. Please try again.", "error");
            } finally {
                loginBtn.disabled = false;
                loginBtn.innerHTML = 'Sign In';
            }
        });
    </script>
    {{-- MJ Guide chatbot — login-problem help right where users need it --}}
    <x-mj-guide />
</body>
</html>