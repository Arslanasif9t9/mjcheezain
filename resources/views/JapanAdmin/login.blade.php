<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Japan Products Admin | MJCheezain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .brand-gradient { background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); }
        .brand-input:focus {
            border-color: #E85D85;
            box-shadow: 0 0 0 3px rgba(232, 93, 133, 0.15);
            outline: none;
        }
    </style>
</head>
<body class="bg-[#FFF6F0]">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl bg-white rounded-2xl overflow-hidden flex flex-col md:flex-row" style="box-shadow: 0 20px 50px rgba(232, 93, 133, 0.18);">

            <!-- Brand panel -->
            <div class="brand-gradient md:w-5/12 p-10 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-10 w-56 h-56 bg-white/10 rounded-full pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2">
                        <span class="text-white font-extrabold text-3xl tracking-wider">MJ</span>
                        <span class="text-white/90 font-semibold text-lg">Cheezain</span>
                    </div>
                    <p class="text-white/80 text-sm mt-1">Japan Products</p>
                </div>
                <div class="relative z-10 mt-10 md:mt-0">
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mb-4">
                        <i class="fas fa-boxes-stacked text-white text-2xl"></i>
                    </div>
                    <h2 class="text-white text-2xl font-bold leading-snug">Japan Admin Panel</h2>
                    <p class="text-white/85 text-sm mt-2">Manage the Japan auto-parts catalog — add, edit and remove listings.</p>
                </div>
            </div>

            <!-- Login card -->
            <div class="md:w-7/12 p-8 md:p-12">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Welcome back</h1>
                    <p class="text-gray-500 text-sm mt-1">Enter your admin credentials to continue</p>
                </div>

                <div id="errorMessage" class="hidden bg-red-50 border-l-4 border-red-400 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 flex-shrink-0"></i>
                        <p id="errorText" class="ml-3 text-sm text-red-700"></p>
                    </div>
                </div>

                <form id="loginForm" class="space-y-5">
                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1.5">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" id="username" name="username" required autocomplete="username"
                                class="brand-input block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white transition-colors"
                                placeholder="Your admin username">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400 text-sm"></i>
                            </div>
                            <input type="password" id="password" name="password" required autocomplete="current-password"
                                class="brand-input block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 focus:bg-white transition-colors"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" id="loginSubmitBtn"
                        class="brand-gradient w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl text-sm font-bold text-white hover:opacity-90 transition-opacity"
                        style="box-shadow: 0 8px 20px rgba(255, 125, 160, 0.35);">
                        <i class="fas fa-right-to-bracket"></i> Sign in
                    </button>
                </form>

                <p class="text-center text-xs text-gray-400 mt-8">
                    Restricted area — authorized administrators only.
                </p>
            </div>
        </div>
    </div>

    <script>
        const errorMessage = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        const loginForm = document.getElementById('loginForm');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorMessage.classList.add('hidden');

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/japanadmin/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: new URLSearchParams({ username, password })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = '/japanadmin/products';
                } else {
                    errorText.textContent = data.message || 'Invalid credentials';
                    errorMessage.classList.remove('hidden');
                }
            } catch (error) {
                errorText.textContent = 'An error occurred. Please try again.';
                errorMessage.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
