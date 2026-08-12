@extends('layouts.structure')
@section('title', 'Contact Us')
@section('style')
    <style>
        .contact-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(255, 125, 160, 0.18);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 15px;
            background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);
            color: #fff;
        }
    </style>
@endsection
@section('body')
    <x-cosmetics.header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />
    <main class="overflow-x-hidden">

        <!-- Contact Intro Section -->
        <section class="bg-gray-50 pt-12 pb-12 sm:pt-20 md:pb-24">
            <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 70rem;">
                <div class="text-center mb-8 sm:mb-12">
                    <span class="section-kicker justify-center">Contact Us</span>
                    <h2 class="PFDI text-3xl sm:text-5xl font-display font-bold tracking-wide text-gray-900 mt-3 mb-4">
                        We're Here to Help
                    </h2>
                    <div class="brand-divider mx-auto mb-5"></div>
                    <p class="text-gray-600 max-w-2xl mx-auto">We value every customer, vendor, and visitor. If you have questions, feedback, or need help with orders or seller registration — our support team is ready to assist you.</p>
                </div>

                <!-- Contact Methods Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 mb-10 sm:mb-14">
                    <!-- Phone Support -->
                    <div class="contact-card bg-white p-6 sm:p-8 rounded-xl shadow-md border border-gray-100 text-center">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt text-2xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">Phone Support</h3>
                        <p class="text-gray-600 mb-4">
                            Call us directly for general inquiries, order updates, or vendor assistance
                        </p>
                        <div class="bg-gray-50 border border-gray-100 p-3 rounded-lg inline-block max-w-full">
                            <a href="tel:03XX-XXXXXXX" class="text-gray-900 font-bold text-base sm:text-lg hover:text-pink-600 transition break-all">
                                <i class="fas fa-phone mr-2"></i> 03XX-XXXXXXX
                            </a>
                        </div>
                        <p class="text-gray-500 text-sm mt-3">
                            <i class="far fa-clock mr-1"></i> 10:00 AM to 6:00 PM, Monday to Saturday
                        </p>
                    </div>

                    <!-- Email Support -->
                    <div class="contact-card bg-white p-6 sm:p-8 rounded-xl shadow-md border border-gray-100 text-center">
                        <div class="contact-icon">
                            <i class="fas fa-envelope text-2xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">Email Support</h3>
                        <p class="text-gray-600 mb-4">
                            For product inquiries, return/refund support, or business proposals
                        </p>
                        <div class="bg-gray-50 border border-gray-100 p-3 rounded-lg inline-block max-w-full">
                            <a href="mailto:support@mjcheezain.com" class="text-gray-900 font-bold text-base sm:text-lg hover:text-pink-600 transition break-all">
                                <i class="fas fa-envelope mr-2"></i> support@mjcheezain.com
                            </a>
                        </div>
                        <p class="text-gray-500 text-sm mt-3">
                            <i class="fas fa-reply mr-1"></i> We respond within 24–48 hours
                        </p>
                    </div>

                    <!-- Office Address -->
                    <div class="contact-card bg-white p-6 sm:p-8 rounded-xl shadow-md border border-gray-100 text-center">
                        <div class="contact-icon">
                            <i class="fas fa-building text-2xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">Office Address</h3>
                        <p class="text-gray-600 mb-4">
                            MJCheezain Headquarters
                        </p>
                        <div class="bg-gray-50 border border-gray-100 p-4 rounded-lg">
                            <p class="text-gray-700">
                                [Street Address, City, Pakistan]
                            </p>
                            <p class="text-pink-600 font-medium mt-2">
                                Coming Soon
                            </p>
                        </div>
                    </div>

                    <!-- WhatsApp Support -->
                    <div class="contact-card bg-white p-6 sm:p-8 rounded-xl shadow-md border border-gray-100 text-center">
                        <div class="contact-icon">
                            <i class="fab fa-whatsapp text-2xl"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">WhatsApp Support</h3>
                        <p class="text-gray-600 mb-4">
                            Chat with us instantly on WhatsApp for quick queries and support
                        </p>
                        <a href="https://wa.me/923XXXXXXXXX" class="btn-brand-gradient inline-block w-full sm:w-auto text-white font-bold py-3 px-6 rounded-full transition duration-300">
                            <i class="fab fa-whatsapp mr-2"></i> Click to Chat on WhatsApp
                        </a>
                        <p class="text-gray-500 text-sm mt-3">
                            <i class="fas fa-clock mr-1"></i> Available 24/7
                        </p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white p-5 sm:p-8 rounded-xl border border-gray-100 shadow-md">
                    <div class="flex items-center mb-6">
                        <div class="text-white p-3 rounded-full mr-4 flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i class="fas fa-paper-plane text-xl"></i>
                        </div>
                        <h2 class="PFDI text-xl sm:text-2xl font-bold text-gray-900">Send Us a Message</h2>
                    </div>

                    <form class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">Your Name</label>
                            <input type="text" id="name" class="w-full px-4 py-3 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#FFC275] focus:border-[#FFC275]">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                            <input type="email" id="email" class="w-full px-4 py-3 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#FFC275] focus:border-[#FFC275]">
                        </div>
                        <div class="md:col-span-2">
                            <label for="subject" class="block text-gray-700 font-medium mb-2">Subject</label>
                            <input type="text" id="subject" class="w-full px-4 py-3 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#FFC275] focus:border-[#FFC275]">
                        </div>
                        <div class="md:col-span-2">
                            <label for="message" class="block text-gray-700 font-medium mb-2">Your Message</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-3 bg-white rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#FFC275] focus:border-[#FFC275]"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="btn-brand-gradient w-full sm:w-auto text-white font-bold py-3 px-8 rounded-full transition duration-300">
                                Send Message <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <x-footer />

    <!-- Lucide icons: loaded here (not in the shared layout) so only the pages
         that actually use icons pay for it. -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Script to initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
@endsection
