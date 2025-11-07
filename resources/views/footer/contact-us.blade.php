@extends('layouts.structure')
@section('title', 'Contact Us')
@section('style')
    <style>
        .header-front {
            background: black;
            background-position: center;
            background-size: cover;
        }

        .header-front .container {
            grid-template-columns: 1fr;
        }

        .header-front .container .text-left {
            justify-self: start;
        }
        
        .contact-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .contact-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 15px;
        }
    </style>
@endsection
@section('body')

    <!-- 7. OUR PROMISE SECTION (New Section) -->
    <section class="bg-off-white pt-20 pb-16 md:pb-32">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 70rem;">
            <h2 class="PFDI text-4xl sm:text-5xl font-display font-bold tracking-wide text-center mb-4">
                We're Here to Help
            </h2>
            <p class="text-center mb-8">We value every customer, vendor, and visitor. If you have questions, feedback, or need help with orders or seller registration — our support team is ready to assist you.</p>
            
             <!-- Contact Methods Grid -->
                <div class="grid md:grid-cols-2 gap-8 mb-12">
                    <!-- Phone Support -->
                    <div class="contact-card bg-white p-8 rounded-lg shadow-md border border-gray-200 text-center">
                        <div class="contact-icon bg-orange-100 text-orange-500">
                            <i class="fas fa-phone-alt text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Phone Support</h3>
                        <p class="text-gray-600 mb-4">
                            Call us directly for general inquiries, order updates, or vendor assistance
                        </p>
                        <div class="bg-gray-100 p-3 rounded-lg inline-block">
                            <a href="tel:03XX-XXXXXXX" class="text-orange-600 font-bold text-lg hover:text-orange-700 transition">
                                <i class="fas fa-phone mr-2"></i> 03XX-XXXXXXX
                            </a>
                        </div>
                        <p class="text-gray-500 text-sm mt-3">
                            <i class="far fa-clock mr-1"></i> 10:00 AM to 6:00 PM, Monday to Saturday
                        </p>
                    </div>

                    <!-- Email Support -->
                    <div class="contact-card bg-white p-8 rounded-lg shadow-md border border-gray-200 text-center">
                        <div class="contact-icon bg-orange-100 text-orange-500">
                            <i class="fas fa-envelope text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Email Support</h3>
                        <p class="text-gray-600 mb-4">
                            For product inquiries, return/refund support, or business proposals
                        </p>
                        <div class="bg-gray-100 p-3 rounded-lg inline-block">
                            <a href="mailto:support@mjcheezain.com" class="text-orange-600 font-bold text-lg hover:text-orange-700 transition">
                                <i class="fas fa-envelope mr-2"></i> support@mjcheezain.com
                            </a>
                        </div>
                        <p class="text-gray-500 text-sm mt-3">
                            <i class="fas fa-reply mr-1"></i> We respond within 24–48 hours
                        </p>
                    </div>

                    <!-- Office Address -->
                    <div class="contact-card bg-white p-8 rounded-lg shadow-md border border-gray-200 text-center">
                        <div class="contact-icon bg-orange-100 text-orange-500">
                            <i class="fas fa-building text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Office Address</h3>
                        <p class="text-gray-600 mb-4">
                            MJCheezain Headquarters
                        </p>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <p class="text-gray-700">
                                [Street Address, City, Pakistan]
                            </p>
                            <p class="text-orange-500 font-medium mt-2">
                                Coming Soon
                            </p>
                        </div>
                    </div>

                    <!-- WhatsApp Support -->
                    <div class="contact-card bg-white p-8 rounded-lg shadow-md border border-gray-200 text-center">
                        <div class="contact-icon bg-orange-100 text-orange-500">
                            <i class="fab fa-whatsapp text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">WhatsApp Support</h3>
                        <p class="text-gray-600 mb-4">
                            Chat with us instantly on WhatsApp for quick queries and support
                        </p>
                        <a href="https://wa.me/923XXXXXXXXX" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                            <i class="fab fa-whatsapp mr-2"></i> Click to Chat on WhatsApp
                        </a>
                        <p class="text-gray-500 text-sm mt-3">
                            <i class="fas fa-clock mr-1"></i> Available 24/7
                        </p>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-orange-50 p-8 rounded-lg">
                    <div class="flex items-center mb-6">
                        <div class="bg-orange-500 text-white p-3 rounded-full mr-4">
                            <i class="fas fa-paper-plane text-xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Send Us a Message</h2>
                    </div>
                    
                    <form class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">Your Name</label>
                            <input type="text" id="name" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                            <input type="email" id="email" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="subject" class="block text-gray-700 font-medium mb-2">Subject</label>
                            <input type="text" id="subject" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="message" class="block text-gray-700 font-medium mb-2">Your Message</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                                Send Message <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </section>

    <!-- Script to initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
@endsection