<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>mjcheezain.com - Coming Soon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E85D85',
                        secondary: '#C94A72',
                        accent: '#F59E0B'
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'bounce-slow': 'bounce 2s infinite',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-pink-50 to-pink-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-7xl w-full">
        <!-- Header with Logo -->
        <header class="text-center mb-12">
            <div class="flex justify-center items-center my-6">
                <!-- Logo Container -->
                {{-- <div class="w-24 h-24 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center shadow-lg"> --}}
                    <img src="{{ asset('WhatsApp_Image_2025-11-14_at_11.58.31_AM-removebg-preview.png') }}" class="w-56 md:w-64 ml-[-15px]">
                    {{-- <span class="text-white font-bold text-xl">MJC</span> --}}
                {{-- </div> --}}
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-1">mjcheezain.com</h1>
            <p class="text-gray-600 text-md md:text-lg">Elegance in every choice</p>
        </header>

        <!-- Main Content -->
        <main class="bg-white rounded-2xl shadow-xl p-8 md:p-12 mb-8">
            <div class="text-center">
                <h2 class="text-2xl md:text-4xl font-bold text-gray-800 mb-6">We're Coming Soon!</h2>
                <p class="text-gray-600 text-md md:text-lg mb-8 max-w-4xl mx-auto">
                    A powerful multivendor marketplace is on the way — vendors will join soon, and customers will enjoy a whole new shopping experience. Stay tuned for something special!
                </p>
                
                <!-- Countdown Timer -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10 max-w-md mx-auto">
                    <div class="bg-pink-50 rounded-lg p-4">
                        <div class="text-3xl font-bold text-primary" id="days">00</div>
                        <div class="text-gray-600">Days</div>
                    </div>
                    <div class="bg-pink-50 rounded-lg p-4">
                        <div class="text-3xl font-bold text-primary" id="hours">00</div>
                        <div class="text-gray-600">Hours</div>
                    </div>
                    <div class="bg-pink-50 rounded-lg p-4">
                        <div class="text-3xl font-bold text-primary" id="minutes">00</div>
                        <div class="text-gray-600">Minutes</div>
                    </div>
                    <div class="bg-pink-50 rounded-lg p-4">
                        <div class="text-3xl font-bold text-primary" id="seconds">00</div>
                        <div class="text-gray-600">Seconds</div>
                    </div>
                </div>

                <!-- Notify Form -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Get Notified When We Launch</h3>
                    <div class="flex flex-col sm:flex-row max-w-md mx-auto gap-2">
                        <input id="email" type="email" name="email" placeholder="Enter your email" class="flex-grow px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <button onclick="subscribe(email.value)" class="bg-primary hover:bg-secondary text-white font-semibold px-6 py-3 rounded-lg transition duration-300 transform hover:scale-105">
                            Notify Me
                        </button>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-10">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Our Progress</h3>
                    <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                        <div class="bg-gradient-to-r from-primary to-accent h-4 rounded-full w-3/4"></div>
                    </div>
                    <p class="text-gray-600">We're 75% complete with our development</p>
                </div>
            </div>
        </main>

        <!-- Social Links -->
        <footer class="text-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Follow Us for Updates</h3>
            <div class="flex justify-center space-x-6">
                <a href="#" class="w-12 h-12 bg-primary hover:bg-secondary text-white rounded-full flex items-center justify-center transition duration-300 transform hover:scale-110">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="w-12 h-12 bg-primary hover:bg-secondary text-white rounded-full flex items-center justify-center transition duration-300 transform hover:scale-110">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="w-12 h-12 bg-primary hover:bg-secondary text-white rounded-full flex items-center justify-center transition duration-300 transform hover:scale-110">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="w-12 h-12 bg-primary hover:bg-secondary text-white rounded-full flex items-center justify-center transition duration-300 transform hover:scale-110">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
            <p class="mt-8 text-gray-600">© 2025 mjcheezain.com. All rights reserved.</p>
        </footer>
    </div>

    <!-- Floating Elements for Visual Appeal -->
    {{-- <div class="fixed top-10 left-10 w-6 h-6 rounded-full bg-primary opacity-20 animate-bounce-slow"></div>
    <div class="fixed top-20 right-20 w-10 h-10 rounded-full bg-accent opacity-30 animate-pulse-slow"></div>
    <div class="fixed bottom-20 left-20 w-8 h-8 rounded-full bg-secondary opacity-25 animate-bounce"></div>
    <div class="fixed bottom-10 right-10 w-12 h-12 rounded-full bg-primary opacity-20 animate-pulse"></div> --}}

    <script>
        async function subscribe(email) {
            // First validate the email
            if (!isValidEmail(email)) {
                showToast('Please enter a valid email address', 'error');
                return;
            }

            try {
                let response = await fetch('/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        'email': email
                    })
                });

                // Check if response is OK
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                // Parse the JSON response
                let data = await response.json();
                
                // Display success message and email in console
                console.log('Submitted email:', data.email);
                showToast('Subscription successful!', 'success');
                
                return data;
                
            } catch (error) {
                console.error('Error:', error);
                showToast('Subscription failed. Please try again.', 'error');
            }
        }

        // Email validation function
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Toast notification function
        function showToast(message, type = 'info') {
            // Remove existing toast if any
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) {
                existingToast.remove();
            }

            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast-notification toast-${type}`;
            toast.innerHTML = `
                <div class="toast-content">
                    <span class="toast-message">${message}</span>
                    <button class="toast-close">&times;</button>
                </div>
            `;

            // Add styles
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#E85D85'};
                color: white;
                padding: 15px 20px;
                border-radius: 4px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                max-width: 400px;
                animation: slideIn 0.3s ease-out;
            `;

            toast.querySelector('.toast-content').style.cssText = `
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 15px;
            `;

            toast.querySelector('.toast-close').style.cssText = `
                background: none;
                border: none;
                color: white;
                font-size: 18px;
                cursor: pointer;
                padding: 0;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            `;

            // Add close functionality
            const closeBtn = toast.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => {
                toast.remove();
            });

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 5000);

            // Add to page
            document.body.appendChild(toast);

            // Add CSS animation
            if (!document.querySelector('#toast-styles')) {
                const style = document.createElement('style');
                style.id = 'toast-styles';
                style.textContent = `
                    @keyframes slideIn {
                        from {
                            transform: translateX(100%);
                            opacity: 0;
                        }
                        to {
                            transform: translateX(0);
                            opacity: 1;
                        }
                    }
                `;
                document.head.appendChild(style);
            }
        }




        
        // Countdown Timer
        function updateCountdown() {
            const launchDate = new Date('December 01, 2025 00:00:00').getTime();
            const now = new Date().getTime();
            const timeLeft = launchDate - now;
            
            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            
            document.getElementById('days').innerHTML = days.toString().padStart(2, '0');
            document.getElementById('hours').innerHTML = hours.toString().padStart(2, '0');
            document.getElementById('minutes').innerHTML = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').innerHTML = seconds.toString().padStart(2, '0');
        }
        
        setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
</body>
</html>